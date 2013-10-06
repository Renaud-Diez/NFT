<?php
class ProjectUserBehavior extends CActiveRecordBehavior
{
	public function setVisibility($project_id, $visibility = 1)
	{
		$model = $this->setMembership($project_id, Yii::app()->user->id);

		if(!is_null($model)){
			$model->visibility = $visibility;
			$model->save();
		}
		
		return $model;
	}
	
	public function setMembership($project_id, $user_id, $role = 'Project Member')
	{
		$model = $this->getMembership($project_id, $user_id);
		
		if(is_null($model)){
			$model = new ProjectUser;
		    $model->project_id = $project_id;
		    $model->user_id = $user_id;
		    $model->role = $role;
		    
		    if($model->save())
		    	Rights::assign($role, $user_id);
		}

		return $model;
	}
	
	public function getMembership($project_id, $user_id = null)
	{
		$model = $this->owner->findByAttributes(array('project_id' => $project_id, 'user_id' => $user_id));
		
		return $model;
	}

}