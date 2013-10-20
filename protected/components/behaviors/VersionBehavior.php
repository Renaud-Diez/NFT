<?php
class VersionBehavior extends CActiveRecordBehavior
{
	//private $_oldAttributes = array();
	private $_oldRecord;
	public $stepLabel = 'Phase';
	
	public function afterFind($event)
	{
		//$this->setOldAttributes($this->owner->getAttributes());
		$this->_oldRecord = clone $this->owner;
		
		if(!empty($this->owner->project->topic->steps))
			$this->stepLabel = ucfirst($this->owner->project->topic->steps);
	}

	public function getStatusOptions()
	{
	    return array( 
	        Version::STATUS_SCHEDULED =>'Scheduled', 
	        Version::STATUS_OPEN =>'Open',
	        Version::STATUS_ACCEPTANCE =>'Acceptance',
	        Version::STATUS_TORELEASE =>'To Release',
	        Version::STATUS_RELEASED =>'Released',
	        Version::STATUS_CLOSED =>'Closed',
	    );
	}
	
	public function getScheduledMilestones($arrMilestone)
	{
		$arr = array();
		$count = 1;
		$p = 0;
		foreach($arrMilestone as $milestone){
			$categories[] = $milestone->label;
			
			if(!empty($milestone->start_date) && !empty($milestone->due_date)){
				$start = $this->formatJSDate($milestone->start_date);
				$end = $this->formatJSDate($milestone->due_date);
						
				$arr[$p][] = array($start, $end);
			}
			$p++;
		}

	
		$series = $this->formatArrayHC($arr, $count, 'Milestone');
		
		if(!empty($categories) && !empty($series))
			return array('categories' => $categories, 'series' => $series);
			
		return false;
	}
	
	protected function formatArrayHC($arr, $count, $label = 'Phase')
	{
		//array('name' => 'V1', 'data' => array(array($t1,$t1b), array($t2,$t2b))),
		foreach($arr as $data){
			for($i=0;$count > $i;$i++){
				$series[$i]['name'] = $label . ' ' . ($i+1);
				if(!empty($data[$i]))
					$series[$i]['data'][] = $data[$i];
				else
					$series[$i]['data'][] = array();
			}
		}
		
		return $series;
	}
	
	protected function formatJSDate($date)
	{
		$date = new DateTime($date);
		$start = $date->getTimestamp();
		$jsDate = $start*1000;
		
		return $jsDate;
	}
	
	public function getMilestones($model, $data, $itemView = '_viewInProject')
	{
		$dataProvider = new CActiveDataProvider('Milestone', array(
            						'data'=>$data,
   							 ));
   		$dataProvider->sort->defaultOrder='due_date ASC';
   		
   		Yii::trace('COUNT DATA: ' . count($dataProvider->getData()),'models.project');
   		
   		if(count($dataProvider->getData()) > 0)
   		{
   			return array('id' => 'milestones-grid',
							'ajaxUpdate'=>true,
							'dataProvider' => $dataProvider,
							'itemView' => '/milestone/'.$itemView,
							'enableSorting' => true,
							'viewData' => array('model' => $model));
   		}
   		
   		return false;
	}
	
	public function getAvailableMilestones($status = null)
	{
		return Milestone::model()->findAll(array('order'=>'start_date ASC', 'condition'=>'version_id=:version_id', 'params'=>array(':version_id'=>$this->owner->id)));
	}
	
	public function computeCompletion($milestone = null)
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
    	->where('pi.version_id = :versionId', array(':versionId' => $this->owner->id))
    	->andWhere('i.status_id '.$operator.' :statusId', array(':statusId' => $statusId))
    	->queryRow();
    	
    	return $value;
	}
	
	public function beforeValidate($event)
	{
		$this->checkDate();
		
		return parent::beforeValidate($event);
	}
	
	public function afterSave($event)
	{
		if($this->_oldRecord->due_date != $this->owner->due_date)
			$this->adaptMilestoneDate($this->owner->due_date);
		
		if($this->_oldRecord->start_date != $this->owner->start_date)
			$this->adaptMilestoneDate($this->owner->start_date, '<');
			
		$this->eventLog();
	}
	
	protected function checkDate()
	{
		$startDate = new DateTime($this->owner->start_date);
		$dueDate = new DateTime($this->owner->due_date);
		
		if($startDate >= $dueDate){
			$this->owner->addError('start_date', 'Start Date can not be greater or equal to Due Date');
		}
	}
	
	protected function adaptMilestoneDate($date, $sign = '>')
	{
		$sql1 = "UPDATE milestone SET start_date = '$date' WHERE version_id = ".$this->owner->id." AND start_date $sign '$date';";
		$sql2 = "UPDATE milestone SET due_date = '$date' WHERE version_id = ".$this->owner->id." AND due_date $sign '$date';";
		
		$connection = Yii::app()->db;
		$transaction = $connection->beginTransaction();
		try{
			$connection->createCommand($sql1)->execute();
			$connection->createCommand($sql2)->execute();
			
			$transaction->commit();
		}
		catch(Exception $e){
			$transaction->rollBack();
		}
	}
	
	protected function eventLog()
	{
		$event = new Event;
		$event->user_id = Yii::app()->user->id;
		$event->project_id = $this->owner->project_id;
		$event->version_id = $event->ref_id = $this->owner->id;
		$event->ref_object = 'Version';
		$event->creation_date = date('Y-m-d H:i:s');
		
		if(!is_null($this->_oldRecord->id))
		{
			if($this->_oldRecord->label != $this->owner->label)
				$changeLog = '<br>Name has been modified from <i>'.$this->_oldRecord->label.'</i> to <i>' . $this->owner->label . '</i>';
			if($this->_oldRecord->due_date != $this->owner->due_date){
				$changeLog .= '<br>Due date has been modified from <i>'.$this->_oldRecord->due_date.'</i> to <i>' . $this->owner->due_date. '</i>';
				$event->criticity = Event::CRITICITY_MEDIUM;
			}
			
			if($this->_oldRecord->start_date != $this->owner->start_date){
				$changeLog .= '<br>Starting date has been modified from <i>'.$this->_oldRecord->start_date.'</i> to <i>' . $this->owner->start_date. '</i>';
				if($this->_oldRecord->start_date > $this->owner->start_date)
					$event->criticity = Event::CRITICITY_HIGH;
				else
					$event->criticity = Event::CRITICITY_MEDIUM;
			}
				
			if(!empty($this->owner->status) && $this->_oldRecord->status != $this->owner->status)
				$changeLog .= '<br>Status has been modified from <i>'.$this->_oldRecord->status.'</i> to <i>' . $this->owner->status. '</i>';
			
			$event->description = '<b>'.$this->stepLabel.' has been updated</b>' . $changeLog;
		}
		else
		{
			$changeLog .= '<br>Due date has been set to <i>'.$this->owner->due_date.'</i>';
			$event->description = '<b>New '.$this->stepLabel.' <i>'.$this->owner->label.'</i> has been added</b>' . $changeLog;
		}
		
		$event->save();
	}
}