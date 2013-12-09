<?php
class TeamBehavior extends CActiveRecordBehavior
{	
	public function getActivities($search = null)
	{
		$arr = $this->getAllMembers();
		if(is_array($arr)){
			$arrIds = $arr['id'];
			
			$criteria=new CDbCriteria;
			$criteria->addInCondition('t.user_id', $arrIds);
			
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
		}
	
		return false;
	}
	
	public function getActivityDetail($search = null)
	{
		$arr = $this->getAllMembers();
		if(is_array($arr)){
			$arrIds = $arr['id'];
				
			$criteria=new CDbCriteria;
			$criteria->addInCondition('t.user_id', $arrIds);
			
			$this->setDateRangeCriteria($criteria, 't.log_date', $search->from, $search->to);
			
			$where=$criteria->condition;
			$params=$criteria->params;
			
			$sql = Yii::app()->db->createCommand()
			->select('SUM(t.time_spent) as time_spent, ta.label as activity, i.label as issue, p.label as project')
			->from('issue i')
			->join('project p', 'p.id = i.project_id')
			->join('timetracker t', 't.issue_id = i.id')
			->join('time_activity ta', 'ta.id = t.activity_id')
			->where($where, $params)
			->group('i.label, ta.label')
			->order('p.label, i.label, ta.label')
			->queryAll();
			
			
			if(count($sql) > 0){
				return new CArrayDataProvider($sql, array(
						'id' => 'weekActivities-'.$this->owner->id,
						'pagination' => array('pageSize' => 10)
				));
			}
		}
			
		return false;
	}
	
	public function getTeamWeeklyIssues($search = null)
	{
		$arr = $this->getAllMembers();
		if(is_array($arr)){
			$arrIds = $arr['id'];
				
			$criteria=new CDbCriteria;
			$criteria->addInCondition('user_id', $arrIds);
			
			$this->setDateRangeCriteria($criteria, 'creation_date', $search->from, $search->to);
			
			$criteria->addCondition('comment != \'\'');
			
			return new CActiveDataProvider(
					'IssueLogs', array(
							'criteria' => $criteria,
							'pagination' => array('pageSize' => 50,),
					));
		}
	
		return false;
	}
	
	public function getTeamWeekly($search = null)
	{
		$arr = $this->getAllMembers();
		if(is_array($arr)){
			$arrIds = $arr['id'];
			
			$criteria=new CDbCriteria;
			$criteria->addInCondition('t.user_id', $arrIds);
			
			$this->setDateRangeCriteria($criteria, 't.log_date', $search->from, $search->to);
			
			$criteria->addCondition('t.comment != \'\'');
			
			
			$where=$criteria->condition;
			$params=$criteria->params;
			
			$sql = Yii::app()->db->createCommand()
			->select('t.time_spent as time_spent, t.comment, t.log_date, ta.label as activity, i.label as issue, p.label as project')
			->from('issue i')
			->join('project p', 'p.id = i.project_id')
			->join('timetracker t', 't.issue_id = i.id')
			->join('time_activity ta', 'ta.id = t.activity_id')
			->where($where, $params)
			//->group('i.label, ta.label')
			->order('p.label, i.label, ta.label, t.log_date')
			->queryAll();
			
			
			if(count($sql) > 0){
				return new CArrayDataProvider($sql, array(
						'id' => 'weekActivities-'.$this->owner->id,
						'pagination' => array('pageSize' => 100)
				));
			}
		}

		return false;
	}
	
	public function getTeamIssues($issue = null, $filter = null, $order = null, $project = null)
	{
		$arr = $this->getAllMembers();
		if(is_array($arr)){
			$arrIds = $arr['id'];
			
			$criteria=new CDbCriteria;
			$criteria->with['issueUsers'] = array('together' => true);		
			$criteria->addInCondition('issueUsers.user_id', $arrIds, 'OR');
			$criteria->addInCondition('t.assignee_id', $arrIds, 'OR');
			
			
			if(!is_null($filter)){
				$criteria->join = 'LEFT OUTER JOIN issue_status status ON (status.id = t.status_id)';
				
				if($filter == 'todo'){
					$criteria->addCondition('status.alias = :alias');
					$criteria->params['alias'] = 1;
				}elseif($filter == 'open'){
					$criteria->addCondition('status.alias = :alias');
					$criteria->params['alias'] = 2;
				}elseif($filter == 'done'){
					$criteria->addCondition('status.alias = :alias');
					$criteria->params['alias'] = 3;
				}
			}
			
			if(!is_null($project)){
				$criteria->with['project'] = array('together' => true);
				$criteria->addCondition('project.label LIKE :project  OR project.code LIKE :project');
				$criteria->params['project'] = '%'.$project.'%';
			}
			

			$criteria->compare('assignee_id', $issue->assignee_id);
			$criteria->compare('priority', $issue->priority);
			$criteria->compare('status_id', $issue->status_id);
			$criteria->compare('project_id', $issue->project_id);
			$criteria->compare('type_id', $issue->type_id);
			$criteria->compare('label', $issue->label, true);
			
			$order = 't.project_id ASC, t.due_date ASC';
			
			if(!is_null($order))
				$criteria->order = $order;
			
			return new CActiveDataProvider(
					'Issue', array(
							'criteria' => $criteria,
							'pagination' => array('pageSize' => 10,),
					));
		}
		
		return false;
	}
	
	public function getAllMembers()
	{
		$models = UserTeam::model()->findAllByAttributes(array('team_id' => $this->owner->id));
		
		if(!is_null($models)){
			foreach ($models as $model){
				$usersArray[] = $model->user_id;
				$usersName[$model->user_id] = $model->user->username;
			}
			
			return array('id' => $usersArray, 'data' => $usersName);
		}
		return false;
	}
	
	
	public function registerMember($userIds, $role = 0)
	{
		Yii::trace('Members ' . count($userIds),'models.issue');
		if(count($userIds)>0)
		{
			$member = $error = 0;
			foreach($userIds as $userId)
			{
				$model = UserTeam::model()->findByAttributes(array('team_id' => $this->owner->id, 'user_id' => $userId));
				if(is_null($model))
				{
					$model = new UserTeam;
					$model->team_id = $this->owner->id;
					$model->user_id = $userId;
					$model->role = $role;
		
					if($model->save())
					{
						$member++;
					}
					else
					{
						$error++;
					}
				}
			}
			if($error > 0)
				$error .= ' Member(s) unsuccessfully added!';
			if($member > 0)
				$member .= ' Member(s) successfully added!';
		}
		
		return array('error' => $error, 'member' => $member);
	}
	
	public function unregisterMember($userId)
	{
		UserTeam::model()->deleteAll('team_id = :teamId AND user_id = :userId', array('teamId' => $this->owner->id, 'userId' => $userId));
	}
	
	public function getMembers()
	{
		$criteria = new CDbCriteria;
		$criteria->with = array('teamUsers');
		$criteria->together = true;
		$criteria->condition = 'team_id=:team_id';
		$criteria->params[':team_id'] = $this->owner->id;
		$criteria->order = 'username ASC';
	
		return new CActiveDataProvider(
				'User', array(
						'criteria' => $criteria,
						'pagination' => array('pageSize' => 10,),
				));
	}
	
	protected function workloadUsers($search = null)
	{
		$arr = $this->getAllMembers();
		if(is_array($arr)){
			$arrIds = $arr['id'];
			$arrUsers = $arr['data'];
			
			$criteria=new CDbCriteria;
			$criteria->with['issueUsers'] = array('together' => true);
			$criteria->addInCondition('t.user_id', $arrIds, 'OR');
			$criteria->addInCondition('i.assignee_id', $arrIds, 'OR');
			
			if(!is_null($search))
				$this->setDateRangeCriteria($criteria, 't.log_date', $search->from, $search->to);
			
			$where=$criteria->condition;
			$params=$criteria->params;
			
			
			$sql = Yii::app()->db->createCommand()
			->select('u.id as uid, u.username as label, SUM(t.time_spent) as value, WEEK(t.log_date, 1) as week')
			->from('issue i')
			->join('timetracker t', 't.issue_id = i.id')
			->join('user u', 'u.id = t.user_id')
			->where($where, $params)
			->group('week, uid')
			->queryAll();
			
			$total = $a = 0;
			foreach($sql as $record){
				$categories[] = $record['week'];
				
				foreach($arrUsers as $id => $name){
					if($record['uid'] == $id)
						$value = $record['value'];
					else
						$value = 0;
					
					$total += $value;
					$a++;
					
					$array[] = array($id, (float) $value);
				}
				
			}//$arrData[] = array('Coding', (float) 33.0);
			$avg = floor($total/$a);

			foreach($array as $data){
				$i = 0;
				foreach($arrUsers as $id => $name){
					if($data[0] == $id){
						$series[$i]['type'] = 'column';
						$series[$i]['name'] = $name;
						$series[$i]['data'][] = $data[1];
					}
					$i++;
				}
			}
			
			$u = count($arrUsers);
			$v = $u+1;
			$w = count($array)/$u;
			for($i=0;$i<$w;$i++){
				$series[$u]['type'] = 'spline';
				$series[$u]['name'] = 'Average';
				$series[$u]['data'][] = $avg;
				$series[$u]['marker'] = array('lineWidth' => 2, 'lineColor' => "js:Highcharts.getOptions().colors[$u]", 'fillColor' => 'white');
					
				$series[$v]['type'] = 'spline';
				$series[$v]['name'] = 'Theorical';
				$series[$v]['data'][] = 38;
				$series[$v]['marker'] = array('lineWidth' => 2, 'lineColor' => "js:Highcharts.getOptions().colors[$v]", 'fillColor' => 'white');	
			}
			
			
			if(!empty($categories) && !empty($series))
				return array('categories' => $categories, 'series' => $series);
		}
			
		return false;
	}
	
	
	protected function timesheet($search = null)
	{
		$arr = $this->getAllMembers();
		if(is_array($arr)){
			$arrIds = $arr['id'];
			$arrUsers = $arr['data'];
				
			$sql = $this->getTimesheetQuery($arr, $search);
				
			$total = $a = 0;
			foreach($sql as $record){
				$categories[] = $record['date'];
	
				foreach($arrUsers as $id => $name){
					if($record['uid'] == $id)
						$value = $record['value'];
					else
						$value = 0;
						
					$array[] = array($id, (float) $value);
				}
	
			}
			
			return $array;
	
			/*foreach($array as $data){
				$i = 0;
				foreach($arrUsers as $id => $name){
					if($data[0] == $id){
						$series[$i]['type'] = 'column';
						$series[$i]['name'] = $name;
						$series[$i]['data'][] = $data[1];
					}
					$i++;
				}
			}*/
			
		}
			
		return false;
	}
	
	protected function getTimesheetQuery($arr, $search = null)
	{
		$arrIds = $arr['id'];
		$arrUsers = $arr['data'];
		
		$criteria=new CDbCriteria;
		//$criteria->with['issueUsers'] = array('together' => true);
		$criteria->addInCondition('t.user_id', $arrIds);
		
		if(!is_null($search))
			$this->setDateRangeCriteria($criteria, 't.log_date', $search->from, $search->to);
		
		if($criteria->params['date'] != '')
			$timeDiff = DateTimeHelper::timeDiff($criteria->params['date']);
		elseif($criteria->params['to'] != '')
			$timeDiff = DateTimeHelper::timeDiff($criteria->params['from'], $criteria->params['to']);
		
		$where=$criteria->condition;
		$params=$criteria->params;
		
		if($timeDiff['d'] > 15){
			$select = 'u.id as uid, u.username as label, SUM(t.time_spent) as value, WEEK(t.log_date, 1) as date';
			$groubBy = 'date, uid';
		}
		else{
			$select = 'i.id as id, i.label as issue, u.id as uid, u.username as label, SUM(t.time_spent) as value, DATE(t.log_date) as date';
			$groubBy = 'id, date, uid';
		}
		
		
		$sql = Yii::app()->db->createCommand()
		->select($select)
		->from('issue i')
		->join('timetracker t', 't.issue_id = i.id')
		->join('user u', 'u.id = t.user_id')
		->where($where, $params)
		->group($groubBy)
		->queryAll();
		
		return $sql;
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