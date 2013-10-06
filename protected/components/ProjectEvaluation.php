<?php
class ProjectEvaluation
{
	public $model;
	public $version_id = null;
	public $milestone_id = null;
	CONST HOURSBYDAY = 6;
	
	public function __construct($model, $version_id = null, $milestone_id = null)
	{
		$this->model = $model;
		$this->setAttributes();
		
		if(!is_null($milestone_id)){
			$this->milestone_id = $milestone_id;
		}
		elseif(!is_null($version_id))
			$this->version_id = $version_id;
	}
	
	protected function setAttributes()
	{
		$attributes = $model->getAttributes();
		foreach($attributes as $name => $value)
		{
			$this->{$name} = $value;
		}
	}
	
	public function evalResources()
	{
		
	}
	
	protected function availableResources()
	{
		// depends of Project and Issues Types
		// Get remaining days
		// Get remaining effort
		// if remaining days < remaining effort => RISK ALERT
	}
	
	protected function getEffort()
	{		
		//get SUM of Opened Issue.estimated_time linked to the Project
		$effort = Yii::app()->db->createCommand()
		->select('SUM(estimated_time), SUM(completion)')
		->from('issue i')
		->join('project_issues p', 'i.id=p.issue_id');
		//->where('i.status_id IN ()');
		
		if(!is_null($this->milestone_id))
			$effort->andWhere('p.version_id = :version_id', array(':version_id' => $this->version_id));
		elseif(!is_null($this->version_id))
			$effort->andWhere('p.milestone_id = :milestone_id', array(':milestone_id' => $this->milestone_id));
		else
			$effort->andWhere('p.project_id = :project_id', array(':project_id' => $this->id));
		
		$effort->queryRow();
		
		return $effort;
	}
	
	protected function projectedEffort()
	{
		// = estimated effort - completion
		// compare spent time to estimlated effort eg: estimated effort = 10 days, spent time = 6 days, completion = 80%
		// => 10-6 = theorical remaining effort 4 days => remaining effort 40% < 20% (completion = 100%-80%)
		// if computed remaining effort is greater than completion then RISK ALERT ...
	}
	
	protected function remainingEffort()
	{
		//in man days => estimated_time / nbr of hours in a day (config)
		
		
	}
	
	protected function computedCompletion()
	{
		// consumed effort / (consumed effort + remaining effort)
		// eg: 20 / (20+30) = 2/5 = 40%
	}
	
	protected function remainingDays()
	{
		//
	}
}