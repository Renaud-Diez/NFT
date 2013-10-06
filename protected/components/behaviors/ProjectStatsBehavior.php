<?php
class ProjectStatsBehavior extends CBehavior
{
	public function remainingBudget()
	{
		if(is_a($this->owner, 'Project'))
		{
			$arrCompletion = $this->owner->todayCompletion();
			$budget = $this->owner->allowed_effort;
			$spent_time = $arrCompletion['spent_time'];
			
			return $budget-$spent_time;
		}
		
		return false;
	}
	
	public function estimatedRemainingBudget()
	{
		if(is_a($this->owner, 'Project'))
		{
			$arrCompletion = $this->owner->todayCompletion();
			$budget = $this->owner->allowed_effort;
			$ere = $arrCompletion['estimated_remaining_effort'];
			
			return $budget-$ere;
		}
		
		return false;
	}
	
	public function remainingEffortVsTime()
	{
		//Get Project due date
		$date = Yii::app()->db->createCommand()
		->select('due_date')
		->from('version v')
		->where('v.project_id = :projectId', array(':projectId' => $this->owner->id))
		->andWhere('v.status NOT IN (4,5)')
		->order('due_date DESC')
		->queryScalar();
		
		$diff = DateTimeHelper::timeDiff($date);
		$availableTime = ($diff['d']*8)+$diff['h'];
		
		$arrCompletion = $this->owner->todayCompletion();
		$ere = $arrCompletion['estimated_remaining_effort'];
		$spent_time = $arrCompletion['spent_time'];
		
		$remainingTime = ($ere-$spent_time);
		
		return array('remainingTime' => $remainingTime, 'availableTime' => $diff);
	}
	
	public function versionRemainingEffort()
	{
		$records = $this->versionDueDate();
		
		foreach($records as $record){
			$dueDate = $record['due_date'];
			$label = $record['label'];
			
			
		}
	}
	
	protected function versionDueDate()
	{
		$records = Yii::app()->db->createCommand()
		->select('id, due_date, label')
		->from('version v')
		->where('v.project_id = :projectId', array(':projectId' => $this->owner->id))
		->andWhere('v.status NOT IN (4,5)')
		->order('due_date ASC')
		->queryAll();
		
		return $records;
	}
	
	protected function versionCompletion()
	{
		$criteria=new CDbCriteria;
		$criteria->compare('version_id', $id);
		$criteria->compare('creation_date', date('Y-m-d'));
			
		$arrCompletion = RemainingCompletion::model()->find($criteria);
		
		if(!is_null($arrCompletion))
			return $arrCompletion;
			
		return false;
	}
}