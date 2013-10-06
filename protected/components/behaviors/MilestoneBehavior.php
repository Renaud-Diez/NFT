<?php
class MilestoneBehavior extends CActiveRecordBehavior
{
	public $_oldRecord;
	
	public function afterFind($event)
	{
		$this->_oldRecord = clone $this->owner;
	}
	
	public function computeCompletion()
	{
		$arrOpenIssues = $this->computeIssueCompletion();
		$arrClosedIssues = $this->computeIssueCompletion('closed', '=');

		$arrCompletion['count'] = $arrClosedIssues['rows']+$arrOpenIssues['rows'];
		$arrCompletion['closed'] = $arrClosedIssues['rows'];
		$arrCompletion['opened'] = $arrOpenIssues['rows'];
		$arrCompletion['open'] = floor($arrOpenIssues['done']);
		
		$arrCompletion['success'] = floor(($arrClosedIssues['rows']/($arrOpenIssues['rows']+$arrClosedIssues['rows']))*100);
		$arrCompletion['warning'] = floor((100-$arrCompletion['success'])*($arrCompletion['open']/100));
		
		return $arrCompletion;
	}
	
	public function computeIssueCompletion($status = 'closed', $operator = '!=')
	{
		$statusId = Yii::app()->db->createCommand()
		->select('id')
		->from('issue_status i')
		->where('i.label = :status', array(':status' => $status))
		->queryScalar();
		
		$value = Yii::app()->db->createCommand()
    	->select('avg(completion) as done, count(i.id) as rows')
    	->from('issue i')
    	->join('project_issues pi', 'pi.issue_id = i.id')
    	->where('pi.milestone_id = :milestoneId', array(':milestoneId' => $this->owner->id))
    	->andWhere('i.status_id '.$operator.' :statusId', array(':statusId' => $statusId))
    	->queryRow();
    	
    	return $value;
	}
	
	public function getStatusOptions($value = null)
	{
	    $return = array( 
	        Milestone::STATUS_SCHEDULED => 'Scheduled', 
	        Milestone::STATUS_OPEN => 'Open', 
	        Milestone::STATUS_CLOSED => 'Closed',
	    );
	    
	    Yii::trace('Milestone status value:  ' . $return[$value],'models.milestone');
	    
	    if(!is_null($value))
		$return = $return[$value];
		
		return $return;
	}
	
	public function getVersionOptions($projectId)
	{
		$criteria=new CDbCriteria;
		$criteria->compare('project_id',$projectId);
		$arrVersion = CHtml::listData(Version::model()->findAll($criteria),'id', 'label');
		return $arrVersion;
	}
	
	public function beforeValidate()
	{
		$this->checkDate();
		
		return parent::beforeValidate();
	}
	
	public function afterSave($event)
	{
		if($this->_oldRecord->due_date != $this->owner->due_date)
			$this->adaptVersionDate($this->owner->due_date);
		
		if($this->_oldRecord->start_date != $this->owner->start_date)
			$this->adaptVersionDate($this->owner->start_date, 'start_date');
			
		//$this->eventLog();
	}
	
	protected function adaptVersionDate($date, $attribute = 'due_date')
	{
		if($attribute == 'start_date')
			$sql = "UPDATE version SET start_date = '$date' WHERE id = ".$this->owner->version_id." AND start_date > '$date';";
		else
			$sql = "UPDATE version SET due_date = '$date' WHERE id = ".$this->owner->version_id." AND due_date < '$date';";
		
		$connection = Yii::app()->db;
		$transaction = $connection->beginTransaction();
		try{
			$connection->createCommand($sql)->execute();
			
			$transaction->commit();
		}
		catch(Exception $e){
			$transaction->rollBack();
		}
	}
	
	protected function checkDate()
	{
		$startDate = new DateTime($this->owner->start_date);
		$dueDate = new DateTime($this->owner->due_date);
		
		if($startDate >= $dueDate){
			$this->owner->addError('start_date', 'Start Date can not be greater or equal to Due Date');
		}
	}
}