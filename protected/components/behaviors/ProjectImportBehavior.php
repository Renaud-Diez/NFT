<?php
class ProjectImportBehavior extends CBehavior
{
	public $importSubTasks = false;
	public $importParticipants = false;
	public $importRelationships = false;
	
	protected $row = false;
	
	public function importIssues($filePath = false, $version = null, $milestone = null)
	{
		$records = 0;
		if($filePath && is_file($filePath)){
			$arrSheet = Yii::app ()->yexcel->readActiveSheet ( $filePath );
			$first = false;
			foreach ( $arrSheet as $rec ) {
				//$row = false;
				$this->row = false;
				if($first){
					/*foreach ( $rec as $record => $value ) {
						if(!is_null($value) && $value != ''){
							$field = $this->mapImportedAttributes( strtolower($arrSheet[1][$record]), $value);
							if($field)
								$row[$field['attribute']] = $field['value'];
						}
					}*/
					
					foreach ( $rec as $record => $value ) {
						if(!is_null($value) && $value != '')
							$this->mapImportedAttributes( strtolower($arrSheet[1][$record]), $value);
					}

					//print_r($row);
					if($this->row){
						$model = $this->importRow($this->row, $version, $milestone);
						if($model->id)
							$records++;
						//Yii::trace('Import OK:' . $model->id,'models.issue');
						if(!is_null($model) && !empty($this->row['sub-tasks'])){
						 	$relations[] = array('id' => $model->id, 'sub' => $this->row['sub-tasks'], 'type' => Issue::RELATED_PARENT);
						}
						
						if(!is_null($model) && !empty($this->row['linked-issues'])){
							$relations[] = array('id' => $model->id, 'sub' => $this->row['linked-issues'], 'type' => Issue::RELATED_TO);
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
			$this->row['code'] = $value;
		elseif (in_array ( $attribute, array (
				'label',
				'summary' 
		) ))
			$this->row['label'] = $value;
		elseif (in_array ( $attribute, array (
				'issue_type',
				'type' 
		) ))
			$this->mapType ( $value );
		elseif ($attribute == 'status')
			$this->mapStatus ( $value );
		elseif ($attribute == 'priority')
			$this->mapPriority ( $value );
		elseif ($attribute == 'assignee')
			$this->mapAssignee ( $value );
		elseif (in_array ( $attribute, array (
				'owner',
				'reporter'
		) ))
			$this->mapOwner ( $value );
		elseif (in_array ( $attribute, array (
				'original_estimate',
				'estimated',
				'estimate',
				'effort',
				'estimated_time'
		) ))
			$this->mapEstimate($value);
		elseif ($attribute == 'progress')
			$this->row['completion'] = substr ( $value, 0, - 1 );
		elseif ($attribute == 'description')
			$this->row['description'] = $value;
		elseif ($attribute == 'participants')
			$this->mapParticipants ( $value );
		elseif ($attribute == 'subtasks')
			$this->mapSubTasks ( $value );
		elseif (in_array ( $attribute, array (
				'related',
				'linked_issues'
		) ))
			$this->mapRelationships ( $value );
		elseif ($attribute == 'remaining')
			$this->mapRemaining ( $value );
		else
			return false;
	}
	
	protected function mapEstimate($value)
	{
		$value = str_replace(',', '.', $value);
		
		if($value >= 1000){
			$value = round(($value/3600),2);
		}
		
		$this->row['estimated_time'] = $value;
	}
	
	protected function mapRemaining($value)
	{
		if($value != ''){
			$value = str_replace(',', '.', $value);
			if($value >= 1000)
				$value = round(($value/3600),2);
			
			$model = $this->getIssueByCode($this->row['code']);
			
			if(!is_null($model)){
				if(isset($this->row['estimated_time']))
					$estimated_time = $this->row['estimated_time'];
				else
					$estimated_time = $model->estimated_time;
				
				if(isset($this->row['completion']))
					$completion = $this->row['completion'];
				else
					$completion = $model->completion;
			}
			
			
			
			$spent_time = $model->getLoggedEffort();
			if(!is_numeric($spent_time))
				$spent_time = 0;
			
			//Yii::trace('KSPENT:' . $estimated_time,'models.issue');
			if(is_numeric($value) && $value >= 0){
				if($spent_time == 0){
					$completion = round(100-(($value/$estimated_time)*100), 0);
				}else
					$completion = round((1-($value/($spent_time+$value)))*100, 0);
				//Yii::trace('KCOMP:' . $completion,'models.issue');
			}
			
			if((is_null($estimated_time) || $estimated_time == 0) && $completion > 0){
				$estimated_time = round($spent_time*(100/$completion), 1);
			}
			
			
			$this->row['completion'] = $completion;
			$this->row['estimated_time'] = $estimated_time;
		}
	}
	
	protected function mapRelationships($value)
	{
		$value = str_replace(' ', '', $value);
		
		$this->row['linked-issues'] = str_replace(' ', '', $value);
	}
	
	protected function mapSubTasks($value)
	{
		$value = str_replace(' ', '', $value);
		
		$this->row['sub-tasks'] = str_replace(' ', '', $value);
	}
	
	protected function mapType($value)
	{
		$model = IssueType::model()->findByAttributes(array('label' => $value));
		
		if(is_null($model)){
			$model = new IssueType;
			$model->label = $value;
			$model->save();
		}
		
		
		$this->row['type_id'] = $model->id;
	}
	
	protected function mapStatus($value)
	{
		$model = IssueStatus::model()->findByAttributes(array('label' => $value));
	
		if(is_null($model)){
			$model = new IssueStatus;
			$model->label = $value;
			$model->save();
		}
	
		$this->row['status_id'] = $model->id;
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
	
		$this->row['priority'] = $value;
	}
	
	protected function mapParticipants($value)
	{
		if(strstr($value, ' and '))
			$participants = split(' and ', $value);
		else
		$participants = split(', ', $value);
		
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
			$this->row['owner_id'] = $ownerID;
		}
		
		return false;
	}
	
	protected function mapAssignee($value)
	{
		$userID = $this->getUserId($value);
		
		if($userID){
			$this->importParticipants[] = $userID;
			$this->row['assignee_id'] = $userID;
		}
		
		return false;
	}
	
	protected function mapOwner($value)
	{
		$userID = $this->getUserId($value);
	
		if($userID){
			$this->importParticipants[] = $userID;
			$this->row['owner_id'] = $userID;
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