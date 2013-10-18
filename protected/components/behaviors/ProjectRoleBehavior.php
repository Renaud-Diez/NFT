<?php
class ProjectRoleBehavior extends CActiveRecordBehavior
{
	public function getRoles()
	{
		$criteria = new CDbCriteria();
		$criteria->compare('r.name','Project',true);
		$criteria->compare('r.type','2',true);
		//$criteria->compare('p.project_id', $this->owner->project_id);
		$criteria->params['projectId'] = $this->owner->project_id;
		$criteria->addNotInCondition('r.name', array('Project Owner', 'Project Access', 'Project Reader'));
		$criteria->order = 'name ASC';
		
		$where=$criteria->condition;
		$params=$criteria->params;
		
		$sql = Yii::app()->db->createCommand()
		->select('IFNULL(p.id, 0) as id, IFNULL(p.minimum, 0) as minimum, r.name as name')
		->from('project_role p')
		->rightJoin('AuthItem r', 'r.name = p.role AND p.project_id = :projectId')
		->where($where, $params)
		//->group('p.status_id')
		->order('r.name ASC')
		->queryAll();
		
		return $sql;
	}
	
	public function setRoles($array = false)
	{
		if(is_array($array)){
			$projectId = $this->owner->project_id;
			$creationDate = $this->owner->creation_date;
			
			foreach($array['Id'] as $rec => $id){
				unset($model);
				if($id > 0){
					$model = ProjectRole::model()->findByPk($id);
				}else{
					if($array['Min'][$rec] > 0){
						$model = New ProjectRole;
						$model->role = $array['Role'][$rec];
					}
					
				}
				
				if(!is_null($model) && ($array['Min'][$rec] != $model->minimum)){
					if($array['Min'][$rec] == 0){
						$model->delete();
					}else{
						$model->project_id = $projectId;
						$model->creation_date = $creationDate;
						$model->minimum = $array['Min'][$rec];
						$model->save();
					}
					
				}
			}
			return true;
		}
		return false;
	}
}