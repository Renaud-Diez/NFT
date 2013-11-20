<?php
class ProjectImportBehavior extends CBehavior
{
	public $importSubTasks = false;
	public $importParticipants = false;
	public $importRelationships = false;
	
	public function importIssues($filePath = false, $version = null, $milestone = null)
	{
		$records = 0;
		if($filePath && is_file($filePath)){
			$arrSheet = Yii::app ()->yexcel->readActiveSheet ( $filePath );
			$first = false;
			foreach ( $arrSheet as $rec ) {
				$row = false;
				if($first){
					foreach ( $rec as $record => $value ) {
						if(!is_null($value) && $value != ''){
							$field = $this->mapImportedAttributes( strtolower($arrSheet[1][$record]), $value);
							if($field)
								$row[$field['attribute']] = $field['value'];
						}
					}

					//print_r($row);
					if($row){
						$model = $this->importRow($row, $version, $milestone);
						if($model->id)
							$records++;
						//Yii::trace('Import OK:' . $model->id,'models.issue');
						if(!is_null($model) && !empty($row['sub-tasks'])){
						 	$relations[] = array('id' => $model->id, 'sub' => $row['sub-tasks'], 'type' => Issue::RELATED_PARENT);
						}
						
						if(!is_null($model) && !empty($row['linked-issues'])){
							$relations[] = array('id' => $model->id, 'sub' => $row['linked-issues'], 'type' => Issue::RELATED_TO);
						}
						
						if(is_array($this->importParticipants) && count($this->importParticipants) > 0){
							$this->importParticipants($model);
						}
					}
				}
				$first = true;
			}
			
			if(is_array($relations) && count($relations) > 0){
				$this->importRelations($relations);
			}
			
			return $records . ' Issues succesfully imported!';
		}
		
		return 'No file provided!';
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
			return $this->mapType ( $value );
		elseif ($attribute == 'status')
			return $this->mapStatus ( $value );
		elseif ($attribute == 'priority')
			return $this->mapPriority ( $value );
		elseif ($attribute == 'assignee')
			return $this->mapAssignee ( $value );
		elseif (in_array ( $attribute, array (
				'owner',
				'reporter'
		) ))
			return $this->mapOwner ( $value );
		elseif (in_array ( $attribute, array (
				'original_estimate',
				'estimated',
				'estimate',
				'effort',
				'estimated_time'
		) ))
			return array (
					'attribute' => 'estimated_time',
					'value' => round(($value/3600),2)
			);
		elseif ($attribute == 'progress')
			return array (
					'attribute' => 'completion',
					'value' => substr ( $value, 0, - 1 ) 
			);
		elseif ($attribute == 'description')
			return array (
					'attribute' => 'description',
					'value' => $value 
			);
		elseif ($attribute == 'participants')
			return $this->mapParticipants ( $value );
		elseif ($attribute == 'subtasks')
			return $this->mapSubTasks ( $value );
		elseif (in_array ( $attribute, array (
				'related',
				'linked_issues'
		) ))
			return $this->mapRelationships ( $value );
		else
			return false;
	}
	
	protected function mapRelationships($value)
	{
		$value = str_replace(' ', '', $value);
		
		return array (
				'attribute' => 'linked-issues',
				'value' => str_replace(' ', '', $value)
		);
	}
	
	protected function mapSubTasks($value)
	{
		$value = str_replace(' ', '', $value);
		
		return array (
				'attribute' => 'sub-tasks',
				'value' => str_replace(' ', '', $value)
		);
	}
	
	protected function mapType($value)
	{
		$model = IssueType::model()->findByAttributes(array('label' => $value));
		
		if(is_null($model)){
			$model = new IssueType;
			$model->label = $value;
			$model->save();
		}
		
		return array (
					'attribute' => 'type_id',
					'value' => $model->id
			);
	}
	
	protected function mapStatus($value)
	{
		$model = IssueStatus::model()->findByAttributes(array('label' => $value));
	
		if(is_null($model)){
			$model = new IssueStatus;
			$model->label = $value;
			$model->save();
		}
	
		return array (
				'attribute' => 'status_id',
				'value' => $model->id
		);
	}
	
	protected function mapPriority($value)
	{
		$model = new Issue;
		
		if(strtolower($value) == $model->getPriorities(Issue::PRIORITY_LOW))
			$value = Issue::PRIORITY_LOW;
		elseif(strtolower($value) == $model->getPriorities(Issue::PRIORITY_HIGH))
			$value = Issue::PRIORITY_HIGH;
		elseif(strtolower($value) == $model->getPriorities(Issue::PRIORITY_URGENT))
			$value = Issue::PRIORITY_URGENT;
		elseif(strtolower($value) == $model->getPriorities(Issue::PRIORITY_IMMEDIATE))
			$value = Issue::PRIORITY_IMMEDIATE;
		else
			$value = Issue::PRIORITY_NORMAL;
	
		return array (
				'attribute' => 'priority',
				'value' => $value
		);
	}
	
	protected function mapParticipants($value)
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
					'attribute' => 'owner_id',
					'value' => $ownerID
			);
		}
		
		return false;
	}
	
	protected function mapAssignee($value)
	{
		$userID = $this->getUserId($value);
		
		if($userID){
			$this->importParticipants[] = $userID;
			return array (
					'attribute' => 'assignee_id',
					'value' => $userID
			);
		}
		
		return false;
	}
	
	protected function mapOwner($value)
	{
		$userID = $this->getUserId($value);
	
		if($userID){
			$this->importParticipants[] = $userID;
			return array (
					'attribute' => 'owner_id',
					'value' => $userID
			);
		}
	
		return false;
	}
	
	protected function getUserId($participant)
	{
		$arrName = split(' ', $participant);
		
		$criteria = new CDbCriteria;
		$criteria->compare('firstname', $arrName[0], true);
		$criteria->compare('lastname', $arrName[1], true);
		
		$model = User::model()->find($criteria);
		
		if(is_null($model)){
			//CREATE NEW USER
			$model = new User;
			$model->username = ucfirst($arrName[0]) . '.' . ucfirst($arrName[1]);
			$model->firstname = ucfirst($arrName[0]);
			$model->lastname = ucfirst($arrName[1]);
			$model->save();
		}
			
		if(!is_null($model))
			return $model->id;
		
		return false;
	}
	
	
	protected function importRow($row, $version = null, $milestone = null)
	{
		$criteria=new CDbCriteria;
		$criteria->compare('code', $row['code']);
		$criteria->compare('project_id', $this->owner->id);
	
		$model = Issue::model()->find($criteria);
	
		if(is_null($model)){
			$model = new Issue;
		}
	
		$model->attributes = $row;
		
		if(is_null($model->status_id)){
			$status = $this->mapStatus('Undefined');
			$model->status_id = $status['value'];
		}
		
		if(is_null($model->type_id)){
			$type = $this->mapType('Undefined');
			$model->type_id = $type['value'];
		}
		
		$model->project_id = $this->owner->id;
		
		
		
		if(!is_null($milestone)){
			$milestone = Milestone::model()->findByPk($milestone);
			
			if($milestone->project_id == $model->project_id){
				$model->milestone_id = $milestone->id;
				$model->version_id = $milestone->version_id;
			}
		}elseif(!is_null($version)){
			$version = Version::model()->findByPk($version);
			
			if($version->project_id == $model->project_id)
				$model->version_id = $version->id;
		}
			

		$model->user_id = Yii::app()->user->id;
		$model->save();
		
		Yii::trace('Import CODE:' . $model->id,'models.issue');
		
		if(!is_null($model)){
			$model->registerParticipant(array($row['user_id']));
		}
	
		return $model;
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
				$relModel = $this->getIssueByCode($rel);
				
				if(!is_null($relModel)){
					$model = IssueRelation::model()->findByAttributes(array('related_id' => $relModel->id, 'issue_id' => $relation['id']));
					if(is_null($model)){
						$model = new IssueRelation;
						$model->issue_id = $relation['id'];
						$model->related_id = $relModel->id;
					
						$model->relation = $relation['type'];
						$model->save();
					}
				}
				
			}
		}
	
	}
	
	
	protected function getIssueByCode($code)
	{
		$criteria=new CDbCriteria;
		$criteria->compare('code', $code);
		$criteria->compare('project_id', $this->owner->id);
		
		$model = Issue::model()->find($criteria);
		
		return $model;
	}
}