<?php
class IssueUserBehavior extends CActiveRecordBehavior
{	
	public function setMembership($issue_id, $user_id)
	{
		$model = $this->getMembership($issue_id, $user_id);
		
		if(is_null($model)){
			$model = new IssueUser;
		    $model->issue_id = $issue_id;
		    $model->user_id = $user_id;
		    
		}

		return $model;
	}
	
	public function getMembership($issue_id, $user_id = null)
	{
		$model = $this->owner->findByAttributes(array('issue_id' => $issue_id, 'user_id' => $user_id));
		
		return $model;
	}
	
	public function beforeSave($event)
	{
		//Get Project User membership
		$model = ProjectUser::model()->findByAttributes(array('project_id' => $this->owner->issue->project_id, 'user_id' => $this->owner->user_id));
		
		//If Relationship doesn't exist create it
		if(is_null($model)){
			ProjectUser::model()->setMembership($this->owner->issue->project_id, $this->owner->user_id);
		}
		
		return parent::beforeSave();
	}

}