<?php
class ProjectStatsBehavior extends CBehavior
{
	public $importSubTasks = false;
	public $importParticipants = false;
	public $importRelationships = false;
	
	public function importIssues($filePath = false)
	{
		if($filePath && is_file($filePath)){
			$arrSheet = Yii::app ()->yexcel->readActiveSheet ( $filePath );
				
			foreach ( $arrSheet as $row ) {
				$row = false;
				foreach ( $row as $record => $value ) {
					$field = $this->mapImportedAttributes($arrSheet[1][$record], $value);
					if($field)
						$row[$field['attribute']] = $field['value'];
				}
	
				if($row){
					$model = $this->importRow($row);
					/*if(!is_null($model) && !empty($row['sub-tasks'])){
						$relations[] = array('id' => $model->id, 'sub' => $row['sub-tasks']);
					}*/
					if(is_array($this->importParticipants) && count($this->importParticipants) > 0){
						$this->importParticipants($model);
					}
				}
			}
		}
	}
	
	protected function mapImportedAttributes($attribute, $value = false)
	{
		if (in_array ( $attribute, array (
				'id',
				'key',
				'code' 
		) ))
			return array (
					'attribute' => 'code',
					'value' => $value 
			);
		elseif (in_array ( $attribute, array (
				'label',
				'summary' 
		) ))
			return array (
					'attribute' => 'label',
					'value' => $value 
			);
		elseif (in_array ( $attribute, array (
				'issue_type',
				'type' 
		) ))
			return $this->mapType ( $attribute, $value );
		elseif ($attribute == 'status')
			return $this->mapStatus ( $attribute, $value );
		elseif ($attribute == 'priority')
			return $this->mapPriority ( $attribute, $value );
		elseif ($attribute == 'assignee')
			return $this->mapAssignee ( $attribute, $value );
		elseif ($attribute == 'original-estimate')
			return array (
					'attribute' => 'estimated_time',
					'value' => $value 
			);
		elseif ($attribute == 'progress')
			return array (
					'attribute' => 'completion',
					'value' => substr ( $value, - 1 ) 
			);
		elseif ($attribute == 'description')
			return $this->mapStatus ( $attribute, $value );
		elseif ($attribute == 'participants')
			return $this->mapParticipants ( $attribute, $value );
		elseif ($attribute == 'sub-tasks')
			return $this->mapSubTasks ( $attribute, $value );
		elseif ($attribute == 'linked-issues')
			return $this->mapRelationships ( $attribute, $value );
		else
			return false;
	}
	
	protected function mapSubTasks($attribute, $value)
	{
	
	}
	
	protected function mapParticipants($attribute, $value)
	{
		$participants = split(' and ', $value);
		foreach($participants as $participant){
			$userID = $this->getUserId($participant);
			if($userID){
				if(!$ownerID){
					$ownerID = $userID;
				}
				
				$this->importParticipants[] = $userID;
			}
			
		}
		
		if($ownerID){
			return array (
					'attribute' => 'user_id',
					'value' => $ownerID
			);
		}
		
		return false;
	}
	
	protected function getUserId($participant)
	{
		$arrName = split(' ', $participant);
		
		$criteria = new CDbCriteria;
		$criteria->compare('firstname', $arrName[0]);
		$criteria->compare('lastname', $arrName[1]);
		
		$model = User::model()->find($criteria);
		
		if(is_null($model)){
			//CREATE NEW USER
			$model = new User;
			$model->username = ucfirst($arrName[0]) . '.' . ucfirst($arrName[1]);
			$model->firstname = ucfirst($arrName[0]);
			$model->lastname = ucfirst($arrName[1]);
			$model->save;
		}
			
		if(!is_null($model))
			return $model->id;
		
		return false;
	}
	
	
	protected function importRow($row)
	{
		$criteria=new CDbCriteria;
		$criteria->compare('code', $row['code']);
	
		$model = Issue::model()->find($criteria);
	
		if(is_null($model)){
			$model = new Issue;
		}
	
		$model->attributes = $row;
		$model->project_id = $this->owner->id;
		$model->save();
		
		if(!is_null($model)){
			$model->registerParticipant(array($row['user_id']));
		}
	
		return $model->id;
	}
	
	protected function importParticipants($model)
	{
		foreach($this->importParticipants as $participant){
			//TODO import into model ProjectUser + IssueUser
			$arr[] = $participant;
		}
		
		if(is_array($arr) && count($arr) > 0)
			$model->registerParticipant($arr);
	}
	
	protected function importRelations($relations)
	{
		foreach($relations as $relation){
			$arr = split(',', $relation['sub']);
			foreach($arr as $rel){
				$model = IssueRelation::model()->findByAttributes(array('related_id' => $rel, 'issue_id' => $relation->id));
				if(is_null($model)){
					$model = new IssueRelation;
					$model->issue_id = $relation->id;
					$model->related_id = $rel;
						
					$model->relation = 7;
					$model->save();
				}
			}
		}
	
	}
}