<?php
class ProjectBehavior extends CActiveRecordBehavior
{
	/**
	 * PUBLIC
	 */
	
	/**
	 * CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `remaining_completion` AS 
	 * select `i`.`id` AS `id`,`i`.`project_id` AS `project_id`,`i`.`version_id` AS `version_id`,`i`.`milestone_id` AS `milestone_id`,
	 * ifnull(`i`.`completion`,0) AS `completion`,ifnull(`i`.`estimated_time`,0) AS `estimated_time`,ifnull(sum(`t`.`time_spent`),0) AS `time_spent` 
	 * from (`issue` `i` left join `timetracker` `t` on((`t`.`issue_id` = `i`.`id`))) 
	 * where `i`.`status_id` in (select `is`.`id` from `issue_status` `is` where (`is`.`closed_alias` = 0)) group by `i`.`id`
	 */
	
	public function evalStatus()
	{
		//evaluate if there is enough resources in regards of number of tasks and related efforts set for each of them 
	}
	
	public function getStartDate()
	{
		$criteria=new CDbCriteria;
		$criteria->compare('project_id', $this->owner->id);
		//$criteria->order = 'id ASC';
		
		$model = ProjectLogs::model()->find($criteria);
		return Yii::app()->dateFormatter->format('y-MM-d', $model->creation_date);
	}
	
	/**
	 *
	 * Save the current data into the Project Logs object before saving the new data ...
	 */
	public function beforeSave($event)
	{
		if(empty($this->owner->allowed_budget))
			$this->owner->allowed_budget = 0;
	
		if(empty($this->owner->allowed_effort))
			$this->owner->allowed_effort = 0;
	
		if(empty($this->owner->hours))
			$this->owner->hours = Project::HOURSBYDAY;
	
		if(empty($this->owner->days))
			$this->owner->days = Project::DAYSBYWEEK;
	
		$projectLog = new ProjectLogs;
		$projectLog->project_id = $this->owner->id;
		$projectLog->user_id = Yii::app()->user->id;
		$projectLog->owner_id = $this->owner->user_id;
		$projectLog->topic_id = $this->owner->topic_id;
		$projectLog->label = $this->owner->label;
		$projectLog->allowed_budget = $this->owner->allowed_budget;
		$projectLog->allowed_effort = $this->owner->allowed_effort;
		$projectLog->hours = $this->owner->hours;
		$projectLog->days = $this->owner->days;
		$projectLog->creation_date = date('Y-m-d H:i:s');
	
		if(!is_null($this->owner->description))
			$projectLog->description = $this->owner->description;
	
		if(!is_null($this->owner->parent_id))
			$projectLog->parent_id = $this->owner->parent_id;
	
		$projectLog->save();
	
		return parent::beforeSave($event);
	}
	
	public function afterFind($event)
	{
		$this->owner->oldRecord = clone $this->owner;
		return parent::afterFind($event);
	}
	
	public function afterSave($event)
	{
		$this->registerParticipant(array($this->owner->user_id) , 'Project Owner');
	
		$this->owner->eventLog();
	}
	
	public function registerParticipant($userIds = false, $role = 'Project Member')
	{
		if(is_array($userIds) && count($userIds) > 0){
			
			foreach($userIds as $userID){
				$member = ProjectUser::model()->findByAttributes(array('project_id'=>$this->owner->id, 'user_id'=>$userID));
					
				if(is_null($member)){
					$member = new ProjectUser;
					$member->project_id = $this->owner->id;
					$member->user_id = $userID;
					$member->role = 'Project Owner';
					$member->save();
				}
				/*elseif($member->role != 'Project Owner'){
					$member->role = 'Project Owner';
					$member->save();
				}*/
			}
			
			
			return true;
		}
		
		return false;
	}
	
	
	protected function eventLog()
	{
		$event = new Event;
		$event->user_id = Yii::app()->user->id;
		$event->project_id = $event->ref_id = $this->owner->id;
		$event->ref_object = 'Project';
		$event->creation_date = date('Y-m-d H:i:s');
			
		if(!is_null($this->owner->oldRecord->id))
		{
			if($this->owner->oldRecord->label != $this->owner->label)
				$changeLog = '<br>Name has been modified from <i>'.$this->owner->oldRecord->label.'</i> to <i>' . $this->owner->label . '</i>';
			if($this->owner->oldRecord->code != $this->owner->code)
				$changeLog .= '<br>Code has been modified from <i>'.$this->owner->oldRecord->code.'</i> to <i>' . $this->owner->code. '</i>';
			if($this->owner->oldRecord->user_id != $this->owner->user_id){
				$changeLog .= '<br>Owner has been modified from <i>'.$this->owner->oldRecord->owner->owner->username.'</i> to <i>' . $this->owner->owner->username. '</i>';
				$event->criticity = $event->CRITICITY_HIGH;
			}
				
			if($this->owner->oldRecord->topic_id != $this->owner->topic_id)
				$changeLog .= '<br>Topic has been modified from <i>'.$this->owner->oldRecord->topic->label.'</i> to <i>' . $this->owner->topic->label. '</i>';
	
			$event->description = '<b>Project has been updated</b>' . $changeLog;
		}
		else {
			$changeLog .= '<br>Owner is <i>'.$this->owner->owner->username.'</i>';
				
			if(!is_null($this->owner->parent_id)){
				$changeLog .= '<br>Project is a subproject of <i>' . $this->owner->parent->label .'</i>';
			}
	
				
			$event->description = '<b>New Project <i>'.$this->owner->label.'</i> has been added</b>' . $changeLog;
		}
	
		$event->save();
	}
	
	
	public function getVisibility()
	{
		$criteria=new CDbCriteria;
		$criteria->compare('user_id', Yii::app()->user->id);
		$criteria->compare('project_id', $this->owner->id);
		Yii::trace('OWNER ID: ' . $this->owner->id,'models.project');
		
		
		$model = ProjectUser::model()->find($criteria);
		if(!is_null($model))
			return $model;
			
		return false;
	}
	
	public function getWorkload($type = 'issue', $search = null)
	{
		if($type == 'issue-workload')
			return $this->workloadIssue($type, $search);
		elseif($type == 'issue-workload-type')
			return $this->workloadIssue($type);
		elseif($type == 'issue-type')
			return $this->issueType($search);
		elseif($type == 'activity')
			return $this->workloadActivity($search);
		elseif($type == 'users')
			return $this->workloadUsers($search);
		
		return false;
	}
	
	public function typeByTopics()
	{
		$criteria=new CDbCriteria;
		$criteria->compare('topic_id', $this->owner->topic_id);

		$arrType = IssueTypeTopic::model()->findAll($criteria);
		
		if(!is_null($arrType))
			return $arrType;
			
		return false; 
	}

	public function statusByType($type)
	{
		$criteria=new CDbCriteria;
		$criteria->compare('i.project_id', $this->owner->id);
		$criteria->compare('i.type_id', $type->type_id);
		
		$where=$criteria->condition;
		$params=$criteria->params;
		
		$sql = Yii::app()->db->createCommand()
		->select('i.status_id as id, is.label as label')
		->from('issue i')
		->join('issue_status is', 'is.id = i.status_id')
		->where($where, $params)
		->group('i.status_id')
		->order('is.rank ASC')
		->queryAll();
		
		return $sql;
	}
	
	public function issueByTypeAndStatus($typeId, $statusId, $pageSize)
	{
		$criteria=new CDbCriteria;
		$criteria->compare('project_id', $this->owner->id);
		$criteria->compare('type_id', $typeId);
		$criteria->compare('status_id', $statusId);
		$criteria->order = 'due_date ASC';
		
		$issues = new CActiveDataProvider(Issue, array(
			'criteria'=>$criteria,
			'pagination' => array('pageSize' => $pageSize)
		));
		
		if(!is_null($issues))
			return $issues;
			
		return false; 
	}
	
	/**
	 * $array['Todo']['New'] = $dataProvider;
	 * return array
	 */
	public function kanbanIsues($pageSize = 10)
	{
		$types = $this->typeByTopics();
		
		foreach($types as $type){
			Yii::trace('TOPIC LABEL: ' . $type->type_id,'models.project');
			
			$status = $this->statusByType($type);
			
			foreach($status as $record){
				$arr[$type->type->label][$record['label']] = $this->issueByTypeAndStatus($type->type_id, $record['id'], $pageSize);
			}
		}
		
		if(!is_null($arr))
			return $arr;
			
		return false; 
	}
	
	
	protected function issueType($search = null)
	{
		$from = $to = false;
		
		$criteria=new CDbCriteria;
		$criteria->compare('i.project_id', $this->owner->id);

		$this->setDateRangeCriteria($criteria, 'i.creation_date', $search->from, $search->to);
		
		$where=$criteria->condition;
		$params=$criteria->params;
		
		$sql = Yii::app()->db->createCommand()
		->select('COUNT(i.id) as value, it.label as label')
		->from('issue i')
		->join('issue_type it', 'it.id = i.type_id')
		->where($where, $params)
		->group('it.id')
		->queryAll();
		
		//echo $sql;
		
		foreach($sql as $record){
			$arrData[] = array($record['label'], (float) $record['value']);
		}
		
		if(count($arrData) > 0)
			return $arrData;
			
		return false;
	}
	
	protected function workloadIssue($type, $search = null)
	{
		$criteria=new CDbCriteria;
		$criteria->compare('i.project_id', $this->owner->id);
		
		$this->setDateRangeCriteria($criteria, 't.log_date', $search->from, $search->to);
		
		$where=$criteria->condition;
		$params=$criteria->params;
		
		$sql = Yii::app()->db->createCommand()
		->select('SUM(t.time_spent) as value, COUNT(i.id) as count, it.label as label')
		->from('issue i')
		->join('issue_type it', 'it.id = i.type_id')
		->leftjoin('timetracker t', 't.issue_id = i.id')
		->where($where, $params)
		->group('it.id')
		->queryAll();
		
		//echo $sql->text;
		
		foreach($sql as $record){
			if($type == 'issue-workload'){
				$arrData[] = array($record['label'], (float) $record['value']);
				$sum += $record['value'];
			}else{
				$arrData[] = array($record['label'], (float) $record['count']);
				$sum += $record['count'];
			}
		}
		
		if(count($arrData) > 0 && $sum > 0)
			return $arrData;
			
		return false;
	}
	
	protected function workloadActivity($search = null)
	{
		$criteria=new CDbCriteria;
		$criteria->compare('i.project_id', $this->owner->id);
		
		$this->setDateRangeCriteria($criteria, 't.log_date', $search->from, $search->to);
		
		$where=$criteria->condition;
		$params=$criteria->params;
		
		$sql = Yii::app()->db->createCommand()
		->select('SUM(t.time_spent) as value, ta.label as label')
		->from('issue i')
		->join('timetracker t', 't.issue_id = i.id')
		->join('time_activity ta', 'ta.id = t.activity_id')
		->where($where, $params)
		->group('ta.label')
		->queryAll();
		
		foreach($sql as $record){
			
			$arrData[] = array($record['label'], (float) $record['value']);
		}//$arrData[] = array('Coding', (float) 33.0);
		
		if(count($arrData) > 0)
			return $arrData;
			
		return false;
	}
	
	protected function workloadUsers($search = null)
	{
		$criteria=new CDbCriteria;
		$criteria->compare('i.project_id', $this->owner->id);
		
		$this->setDateRangeCriteria($criteria, 't.log_date', $search->from, $search->to);
		
		$where=$criteria->condition;
		$params=$criteria->params;
		
		$sql = Yii::app()->db->createCommand()
		->select('SUM(t.time_spent) as value, u.username as label')
		->from('issue i')
		->join('timetracker t', 't.issue_id = i.id')
		->leftjoin('user u', 'u.id = t.user_id')
		//->join('time_activity ta', 'ta.id = t.activity_id')
		->where($where, $params)
		->group('u.username')
		->queryAll();
		
		foreach($sql as $record){
			
			$arrData[] = array($record['label'], (float) $record['value']);
		}//$arrData[] = array('Coding', (float) 33.0);
		
		if(count($arrData) > 0)
			return $arrData;
			
		return false;
	}
	
	public function todayCompletion($id = null)
	{
		if(is_null($id))
			$id = $this->owner->id;
		
		$criteria=new CDbCriteria;
		$criteria->compare('project_id', $id);
		$criteria->compare('creation_date', date('Y-m-d'));
			
		$arrCompletion = ProjectCompletion::model()->find($criteria);
		
		if(!is_null($arrCompletion))
			return $arrCompletion;
			
		return false;
	}
	
	public function dataCompletion($id = null, $search = null)
	{
		if(is_null($id))
			$id = $this->owner->id;
		
		$hoursbyday = Project::HOURSBYDAY;
		if($model->hours > 0)
			$hoursbyday = $model->hours;
			
			
		Yii::trace('IDD: ' . $id,'models.project');
		
		$criteria=new CDbCriteria;
		$criteria->compare('project_id', $id);
		
		//$this->setDateRangeCriteria($criteria, 'creation_date', $search->from, $search->to);
		
		$criteria->order = 'creation_date ASC';
			
		$arrCompletion = ProjectCompletion::model()->findAll($criteria);
		
		foreach($arrCompletion as $record){
			$date = $this->formatJSDate($record->creation_date);
			
			//$tre = ($record->theorical_remaining_effort-$record->theorical_effort)/$hoursbyday;
			$tre = $record->theorical_remaining_effort/$hoursbyday;
			if($tre < 0)
				$tre = 0;
			//$ere = ($record->estimated_remaining_effort-$record->spent_time)/$hoursbyday;
			$ere = $record->estimated_remaining_effort/$hoursbyday;
			if($ere < 0)
				$ere = 0;
			
			$arr[1][] = array($date, (float) round($tre, 2));
			$arr[0][] = array($date, (float) round($ere, 2));
			$arr[2][] = array($date, (float) round($this->greaterThanZero($record->overrun/$hoursbyday), 2));
			$arr[3][] = array($date, (float) round($this->greaterThanZero($record->theorical_effort/$hoursbyday), 2));
			$arr[4][] = array($date, (float) round($this->greaterThanZero($record->spent_time/$hoursbyday), 2));
			$arr[5][] = array($date, (float) round($this->greaterThanZero($record->budget/$hoursbyday), 2));
		}
		
		$arrData = array(
					array('name' => 'Estimated Remaining Effort', 'data' => $arr[0]),
					array('name' => 'Theorical Remaining Effort', 'data' => $arr[1]),
					array('name' => 'Overrun', 'data' => $arr[2]),
					array('name' => 'Theorical Effort', 'data' => $arr[3], 'visible' => false),
					array('name' => 'Spent Time', 'data' => $arr[4], 'visible' => false),
					array('name' => 'Budget', 'data' => $arr[5], 'visible' => false),
					);
					
		if(!empty($arrData))
			return $arrData;
			
		return false;
	}
	
	protected function greaterThanZero($value)
	{
		if($value < 0)
			return 0;
			
		return $value;
	}
	
	public function effortCompletion($id = null)
	{
		if(is_null($id))
			$id = $this->owner->id;
					
		$criteria=new CDbCriteria;
		$criteria->compare('project_id', $id);
		
		$where=$criteria->condition;
		$params=$criteria->params;
		
		$sql = Yii::app()->db->createCommand()
		->select('SUM(estimated_time) as estimated_time,
				SUM(time_spent) as spent_time,
				AVG(completion) as completion,
				SUM(estimated_time*completion/100) as theorical_effort,
				SUM(time_spent-(estimated_time*completion/100)) as overrun,
				SUM(estimated_time*(100-completion)/100) as theorical_remaining_effort,
				IFNULL(SUM(estimated_time*(100-completion)/100)*SUM(time_spent)/SUM(estimated_time*completion/100), 0) as estimated_remaining_effort')
				
		->from('remaining_completion')
		->where($where, $params)
		->group('project_id')
		->queryRow();
		//IFNULL(SUM(time_spent*(100/completion)), 0) as estimated_remaining_effort')
		//SUM(estimated_time*(100-completion)/100)*SUM(time_spent)/SUM(estimated_time*completion/100)
		//IFNULL(SUM((time_spent*(100/completion))-time_spent), 0) as estimated_remaining_effort')
		return $sql;
	}
	
	public function estimatedRemainingEffort($id = null)
	{
		$date = date('Y-m-d');
		
		if(is_null($id))
			$id = $this->owner->id;
			
		$arrEffort = $this->effortCompletion($id);
		
		$model = ProjectCompletion::model()->findByAttributes(array('project_id' => $id, 'creation_date' => $date));
		
		if(is_null($model)){
			$model = new ProjectCompletion;
			$model->creation_date = $date;
			$model->project_id = $id;
		}
		
		$model->estimated_effort = round($arrEffort['estimated_time'],2);
		$model->spent_time = round($arrEffort['spent_time'],2);
		$model->theorical_effort = round($arrEffort['theorical_effort'],2);
		$model->overrun = round($arrEffort['overrun'],2);
		$model->completion = round($arrEffort['completion'],2);
		$model->theorical_remaining_effort = round($arrEffort['theorical_remaining_effort'],2);
		$model->estimated_remaining_effort = round($arrEffort['estimated_remaining_effort'],2);
		$model->budget = $this->owner->allowed_effort;
		Yii::trace('Budget: ' . $model->budget,'models.project');
		$model->save();
		
		return $arrEffort;
	}
	
	public function getScheduledProjects($dataProvider)
	{
		$arrProject = $dataProvider->getData();
		$arr = array();
		$count = $p = 0;
		foreach($arrProject as $project){
			$arrVersions = $project->getVersionsDataProvider()->getData();

			$i = 0;
			if(!empty($arrVersions)){
				$categories[] = $project->label;
				
				foreach($arrVersions as $version){
					if(!empty($version->start_date) && !empty($version->due_date)){
						$start = $this->formatJSDate($version->start_date);
						$end = $this->formatJSDate($version->due_date);
						
						$arr[$p][] = array($start, $end);
						$i++;
					}
				}
				
			}
			if($i > $count)
				$count = $i;
			$p++;
		}


		$series = $this->formatArrayHC($arr, $count);

		
		if(!empty($categories) && !empty($series))
			return array('categories' => $categories, 'series' => $series);
			
		return false;
	}
	
	public function getScheduledVersions($dataProvider)
	{
		$arrVersion = $dataProvider->getData();
		$arr = array();
		$count = 0;
		$p = 0;
		foreach($arrVersion as $version){
			$categories[] = $version->label;

			$i = 0;
			$arrMilestones = $version->getAvailableMilestones();
			if(!empty($arrMilestones)){
				foreach($arrMilestones as $milestone){
					if(!empty($version->start_date) && !empty($milestone->due_date)){
						$start = $this->formatJSDate($milestone->start_date);
						$end = $this->formatJSDate($milestone->due_date);
						
						$arr[$p][] = array($start, $end);
						$i++;
					}
				}
				
			}else{
				if(!empty($version->start_date) && !empty($version->due_date)){
					$start = $this->formatJSDate($version->start_date);
					$end = $this->formatJSDate($version->due_date);
				
					$arr[$p][] = array($start, $end);
					$i++;
				}
			}
			
			if($i > $count)
				$count = $i;
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
	
	public function computeCompletion($milestone = null, $version = null)
	{
		$arrOpenIssues = $this->computeIssueCompletion();
		$arrClosedIssues = $this->computeIssueCompletion('closed', '=');

		$arrCompletion['count'] = $arrClosedIssues['rows']+$arrOpenIssues['rows'];
		$arrCompletion['closed'] = $arrClosedIssues['rows'];
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
		->where('pi.project_id = :projectId', array(':projectId' => $this->owner->id))
		->andWhere('i.status_id '.$operator.' :statusId', array(':statusId' => $statusId))
		->queryRow();
		 
		return $value;
	}
	
	public function getRelatedOptions($value = null)
	{
		$return = array(
		Project::RELATED_TO 			=> 'Related',
		Project::RELATED_DUPLICATES		=> 'Duplicates',
		Project::RELATED_DUPLICATEBY	=> 'Duplicate by',
		Project::RELATED_BLOCKS			=> 'Blocks',
		Project::RELATED_BLOCKEDBY		=> 'Blocked by',
		Project::RELATED_PRECEDES		=> 'Precedes',
		Project::RELATED_FOLLOWS		=> 'Follows',
		Project::RELATED_PARENT			=> 'Parent',
		Project::RELATED_CHILD			=> 'Child',
		);

		if(!is_null($value))
		$return = $return[$value];
	  
		return $return;
	}
	
	public function getRelatedProject($relation = null)
	{
		$criteria=new CDbCriteria;

		$criteria->compare('project_id',$this->owner->id);

		if(!is_null($relation))
			$criteria->compare('relation',$relation);

		return new CActiveDataProvider(ProjectRelation, array(
			'criteria'=>$criteria,
			'pagination' => array('pageSize' => 10)
		));
	}
	
	public function checkAccess($itemName = null, $userId = null)
	{
		if(Yii::app()->user->id == $this->owner->user_id)
		return true;

		if(is_null($itemName))
		$itemName = 'Project.'. ucfirst(Yii::app()->controller->action->id);
			
		Yii::trace('Check access to ' . $itemName,'models.project');

		$oProjectUser = $this->getMembership($userId);
		if(!is_null($oProjectUser))
		{
			$role = $oProjectUser->role;
				
			$authorizer = Rights::getAuthorizer();
			$permissions = $authorizer->getPermissions($role);
				
			if($authorizer->hasPermission($itemName, $parentName=null, $permissions))
			{
				Yii::trace('Grant access to ' . $itemName . ' for ' . $role,'models.project');
				return true;
			}
				
		}

		Yii::trace('Deny access to ' . $itemName . ' for ' . $role,'models.project');
		return false;
	}

	public function isUserInProject()
	{
		$model = ProjectUser::model()->findByAttributes(array('project_id' => $this->owner->id, 'user_id' => Yii::app()->user->id));

		if(is_null($model))
		return false;
		else
		return true;
	}
	
	public function getUserOptions()
	{
		$usersArray = Chtml::listData($this->owner->users, 'id', 'username');
		return $usersArray;
	}
	
	public function getMembership($userId = null)
	{
		if(is_null($userId))
		$userId = Yii::app()->user->id;
			
		$oProjectUser = ProjectUser::model()->findByAttributes(array('project_id' => $this->owner->id, 'user_id' => $userId));
		return $oProjectUser;
	}
	
	public function getMembers()
	{
		$criteria = new CDbCriteria;
		$criteria->with = array('projectUsers');
		$criteria->together = true;
		$criteria->condition = 'project_id=:project_id';
		$criteria->params[':project_id'] = $this->owner->id;
		$criteria->order = 'role DESC';
		
		return new CActiveDataProvider(
		'User', array(
						'criteria' => $criteria,
						'pagination' => array('pageSize' => 10,),
		));
	}
	
	public function getVersions($status = null)
	{
		$criteria=new CDbCriteria;

		$criteria->compare('project_id',$this->owner->id);

		if(!is_null($status))
		$criteria->compare('relation',$status);

		$versionsDataProvider = new CActiveDataProvider(Version, array(
			'criteria'=>$criteria,
			'pagination' => array('pageSize' => 1)
		));

		$versionsDataProvider->sort->defaultOrder='due_date ASC';
		
		$steps = 'Phases';
		if(!empty($this->owner->topic->steps))
			$steps = $this->owner->topic->steps;
		 

		return array('id' => 'versions-grid',
							'ajaxUpdate'=>true,
							'dataProvider' => $versionsDataProvider,
							'itemView' => '/version/_viewInProject',
							'enableSorting' => true,
							'viewData' => array('model' => $model, 'steps' => $steps));
	}
	
	public function getVersionsDataProvider()
	{
		$criteria=new CDbCriteria;

		$criteria->compare('project_id',$this->owner->id);

		if(!is_null($status))
		$criteria->compare('relation',$status);

		$versionsDataProvider = new CActiveDataProvider(Version, array(
			'criteria'=>$criteria,
			//'pagination' => array('pageSize' => 1)
		));
		
		$versionsDataProvider->sort->defaultOrder='start_date ASC';
		
		return $versionsDataProvider;
	}
	
	public function getAvailableMilestones($status = null)
	{
		$versions = Version::model()->findAll(array('order'=>'due_date ASC', 'condition'=>'project_id=:project_id', 'params'=>array(':project_id'=>$this->owner->id)));
	}
	
	public function getEvents($type = null, $date = null)
	{
		$criteria=new CDbCriteria;

		$criteria->compare('project_id',$this->owner->id);
		
		if(!is_null($type)){
			$criteria->addCondition('criticity >= :criticity');
			$criteria->params[':criticity'] = $type;
		}
		
		$criteria->addCondition('creation_date BETWEEN :from AND :to');
		
		$date = new DateTime();
		$date->modify('-30 days');
		$date->format('Y-m-d H:i:s');
		
		$criteria->params[':to'] = date('Y-m-d H:i:s');
		$criteria->params[':from'] = $date->format('Y-m-d H:i:s');
		
		$criteria->order = "creation_date DESC";

		return new CActiveDataProvider(Event, array(
			'criteria'=>$criteria,
			'pagination' => array('pageSize' => 100)
		));
	}
	
	public function issueFilter($attributes = false, $type, $gridId)
	{
		$issue=new Issue('search');
		$issue->unsetAttributes();
		
		if($attributes)
			$issue->attributes = $attributes;
		
		if($type == 'version')
			$issue->attributes=array('version_id' => $gridId);
		elseif($type == 'milestone')
			$issue->attributes=array('milestone_id' => $gridId);
		elseif($type == 'project')
			$issue->attributes=array('version_id' => array(0=>NULL), 'milestone_id' => NULL);
		
		return $issue;
	}
	
	public function getDataProviderIssues($issue, $order = null)
	{
		$criteria=new CDbCriteria;

		$criteria->with = array('projectIssues');
		$criteria->together = true;
		$criteria->join = 'LEFT OUTER JOIN issue_status ist ON (ist.id = t.status_id)';
		$criteria->condition = 'projectIssues.project_id=:project_id';
		$criteria->params = array('project_id' => $this->owner->id);


		$criteria = $this->buildCriteria($issue, $criteria);
	  	$criteria = $this->buildCriteria($issue, $criteria,'milestone_id');


		$criteria->compare('t.label',$issue->label,true);
		$criteria->compare('type_id',$issue->type_id,true);
		$criteria->compare('status_id',$issue->status_id,true);
		$criteria->compare('assignee_id',$issue->assignee_id,true);
		$criteria->compare('user_id',$issue->user_id,true);

		$criteria->compare('projectIssues.milestone_id',$issue->milestone_id);
		$criteria->compare('priority',$issue->priority);
		$criteria->compare('estimated_time',$issue->estimated_time,true);
		$criteria->compare('private',$issue->private,true);
		
		if(!is_null($order))
			$criteria->order = $order;
		else
			$criteria->order = 'type_id ASC, ist.rank ASC';

		$dataProvider = new CActiveDataProvider(Issue, array(
			'criteria'=>$criteria,
			'pagination' => array('pageSize' => 10)
		));

		return $dataProvider;
	}
	
	public function getIssues($issue, $filter = null, $order = null, $project = null)
	{
		$criteria=new CDbCriteria;

		if(in_array($filter, array('info', 'question'))){
			$criteria->with = array('projectIssues' => array('together' => true), 'type' => array('together' => true));
		}else{
			$criteria->with = array('projectIssues');
			$criteria->together = true;
		}
		
		if(!is_null($project)){
			$criteria->with['project'] = array('together' => true);
			$criteria->addCondition('project.label LIKE :project  OR project.code LIKE :project');
			$criteria->params['project'] = $project;
		}
	
		
		
		if(strstr('ist', $order) || $filter)
			$criteria->join = 'LEFT OUTER JOIN issue_status ist ON (ist.id = t.status_id)';
			
		if(!empty($this->owner->id)){
			$criteria->condition = 'projectIssues.project_id=:project_id';
			$criteria->params = array('project_id' => $this->owner->id);
		}elseif(is_null($order)){
			$order = 't.project_id ASC, t.due_date ASC';
		}
		
		
		if($filter == 'todo'){
			$criteria->addCondition('ist.alias = :alias');
			$criteria->params['alias'] = 1;
		}elseif($filter == 'open'){
			$criteria->addCondition('ist.alias = :alias');
			$criteria->params['alias'] = 2;
		}elseif($filter == 'done'){
			$criteria->addCondition('ist.alias = :alias');
			$criteria->params['alias'] = 3;
		}
		elseif($filter == 'info'){
			$criteria->addCondition('type.label = :info  OR type.label = :question');
			$criteria->params['info'] = 'Info';
			$criteria->params['question'] = 'Question';
			
			$criteria->addCondition('ist.closed_alias = 0');
		}

		$criteria = $this->buildCriteria($issue, $criteria);
	  	$criteria = $this->buildCriteria($issue, $criteria,'milestone_id');


		$criteria->compare('label',$issue->label,true);
		$criteria->compare('type_id',$issue->type_id);
		$criteria->compare('status_id',$issue->status_id);
		$criteria->compare('assignee_id',$issue->assignee_id);
		$criteria->compare('user_id',$issue->user_id);

		$criteria->compare('projectIssues.milestone_id',$issue->milestone_id);
		$criteria->compare('priority',$issue->priority);
		$criteria->compare('estimated_time',$issue->estimated_time,true);
		$criteria->compare('private',$issue->private);
		
		if(!is_null($order))
			$criteria->order = $order;
		
		//return Issue::model()->findAll($criteria);
		return new CActiveDataProvider(Issue, array(
			'criteria'=>$criteria,
			'pagination' => array('pageSize' => 10)
		));
	}
	
	public function getSubprojects($parent_id = null)
	{
		$array = array();
		$criteria = new CDbCriteria;
		
		if(!is_null($parent_id)){
			$criteria->condition = 'parent_id=:parent_id';
			$criteria->params[':parent_id'] = $parent_id;
		}else{
			$criteria->condition = 'parent_id IS NULL';
		}
		
		$criteria->order = 'label ASC';
		
		$models = Project::model()->findAll($criteria);
		
		if(count($models) > 0)
		{
			
			foreach($models as $model){
				
				//$arr['text'] = '&nbsp;<i class="'.$this->treeIcon($model->topic->label).'" style="margin-right:5px;"></i>'. CHtml::link($model->topic->label. ': <i>' . $model->label . '</i>', array('project/view', 'id'=>$model->id));
				$arr['text'] = '&nbsp;<i class="'.$this->treeIcon($model->topic->label).'" style="margin-right:5px;"></i>'. CHtml::link('<i>' . $model->label . '</i>', array('project/view', 'id'=>$model->id));
				$arr['id'] = $model->id;
				
				$children = $this->getSubprojects($model->id);
				if($children){
					$arr['hasChildren'] = true;
					$arr['children'] = $children;
				}
				$array[] = $arr;
				$arr = array();
			}
			
			return $array;
			//return array($arr);
		}

		return false;
	}
	
	public function treeIcon($topic = null)
	{
		if($topic == 'Application')
			return $icon='icon-hdd';
		elseif($topic == 'Administration')
			return $icon='icon-briefcase';
		elseif($topic == 'Component')
			return $icon='icon-cog';
		elseif($topic == 'Development')
			return $icon='icon-wrench';
		elseif($topic == 'Operation')
			return $icon='icon-signal';
		elseif($topic == 'Migration')
			return $icon='icon-globe';
		elseif($topic == 'Analyse')
			return $icon='icon-search';
		elseif($topic == 'Tests' || $topic == 'UAT')
			return $icon='icon-check';
		elseif($topic == 'Documentation')
			return $icon='icon-book';
		elseif($topic == 'Sales')
			return $icon='icon-star';
		else
			return $icon='icon-star-empty';
	}
	
	
	protected function buildCriteria($issue, $criteria, $field = 'version_id')
	{
		if (is_array($issue->{$field}) && count($issue->{$field}) > 0)
		{
			$hasNull = false;
			$values = array();
			foreach ($issue->{$field} as $value)
			{
				if (is_null($value))
				{
					$hasNull = true;
				}
				else
				{
					array_push($values, $value);
				}
			}

			$condition = array();
			if ($hasNull) array_push($condition, 'projectIssues.'.$field.' IS NULL');
			if (count($values)) array_push($condition, "projectIssues.'.$field.' IN ('" . implode("', '", $values) . "')");

			$criteria->addCondition(implode(' OR ', $condition));
		}
		else
		{
			$criteria->compare('projectIssues.'.$field.'',$issue->{$field});
		}
		
		return $criteria;
	}
	
	protected function setDateRangeCriteria($criteria, $field, $from = false, $to = false)
	{
		if($from && $to){
			$criteria->addCondition($field .' between :from AND :to');
			$criteria->params['from'] = $from;
			$criteria->params['to'] = $to . ' 23:59:59';
		}elseif($from){
			$criteria->addCondition($field . ' >= :date');
			$date = $from;
			$criteria->params['date'] = $date;
		}elseif($to){
			$criteria->addCondition($field . ' <= :date');
			$date = $to;
			$criteria->params['date'] = $date . ' 23:59:59';
		}else{
			$criteria->addCondition($field . ' >= :date');
			$date = date('Y-m-d', strtotime('monday this week'));//strtotime('monday this week')
			$criteria->params['date'] = $date;
		}
		
		return $criteria;
	}
}