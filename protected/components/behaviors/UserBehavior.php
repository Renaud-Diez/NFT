<?php
class UserBehavior extends CActiveRecordBehavior
{
	public function openedQuestion($project = false, $direction = 'received')
	{
		$criteria=new CDbCriteria;
		$criteria->with['type'] = array('together' => true);
		$criteria->with['status'] = array('together' => true);
		
		if($project){
			$criteria->with['project'] = array('together' => true);
			$criteria->compare('project.label', $project, true);
			$criteria->compare('project.code', $project, true, 'OR');
		}
		
		if($direction == 'received')
			$criteria->compare('assignee_id', $this->owner->id);
		else
			$criteria->compare('t.user_id', $this->owner->id);
		$criteria->compare('type.label', 'Question');
		$criteria->addCondition('status.closed_alias != 1');
		$criteria->order = 'due_date DESC';
		
		return new CActiveDataProvider(
		'Issue', array(
						'criteria' => $criteria,
						'pagination' => array('pageSize' => 10,),
		));
	}
	
	protected function workload($search = null)
	{
		$usersArray[] = $this->owner->id;
		$usersName[$this->owner->id] = $this->owner->username;
		$arr = array('id' => $usersArray, 'data' => $usersName);
		
		if(is_array($arr)){
			$arrIds = $arr['id'];
			$arrUsers = $arr['data'];
				
			$criteria=new CDbCriteria;
			$criteria->with['issueUsers'] = array('together' => true);
			$criteria->compare('t.user_id', $this->owner->id, false,'OR');
			$criteria->compare('i.assignee_id', $this->owner->id, false,'OR');
				
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
			
			//echo $sql;
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
				$series[$i]['type'] = 'spline';
				$series[$i]['name'] = 'Average';
				$series[$i]['data'][] = $avg;
				$series[$i]['marker'] = array('lineWidth' => 2, 'lineColor' => "js:Highcharts.getOptions().colors[$i]", 'fillColor' => 'white');
				$i++;
				$series[$i]['type'] = 'spline';
				$series[$i]['name'] = 'Theorical';
				$series[$i]['data'][] = 38;
				$series[$i]['marker'] = array('lineWidth' => 2, 'lineColor' => "js:Highcharts.getOptions().colors[$i]", 'fillColor' => 'white');
			}
			
				
			if(!empty($categories) && !empty($series))
				return array('categories' => $categories, 'series' => $series);
		}
			
		return false;
	}
	
	public function assignedIssues($typeId = false, $project = false)
	{
		$criteria=new CDbCriteria;
		$criteria->with = array('type' => array('together' => true), 'status' => array('together' => true));
		
		if($project){
			$criteria->with['project'] = array('together' => true);
			$criteria->compare('project.label', $project, true);
			$criteria->compare('project.code', $project, true, 'OR');
		}
		
		$criteria->compare('assignee_id', $this->owner->id);
		$criteria->addCondition('type.label != :type');//'Question'
		$criteria->params['type'] = 'Question';
		$criteria->addCondition('status.alias != 3');//'Question'
		$criteria->order = 'due_date DESC, project_id ASC, t.label ASC';
		
		return new CActiveDataProvider(
		'Issue', array(
						'criteria' => $criteria,
						'pagination' => array('pageSize' => 10,),
		));
	}
	
	public function issueList($issue, $statusAlias = false, $order = false, $project = false, $length = 20)
	{
		$criteria=new CDbCriteria;
		$criteria->with = array('status' => array('together' => true), 'issueUsers' => array('together' => true));
		
		$criteria->compare('t.user_id', $this->owner->id, false, 'OR');
		$criteria->compare('assignee_id', $this->owner->id, false, 'OR');
		$criteria->compare('issueUsers.user_id', $this->owner->id, false, 'OR');
		
		if(Yii::app()->session['criticalIssues'] == true){
			$criteriaOver = new CDbCriteria;
			
			$this->delayedIssues($criteriaOver, $issue);
			$this->overrunIssues($criteriaOver, $issue);
		}elseif($issue->overdue){
			$criteriaOver = new CDbCriteria;
			
			$this->delayedIssues($criteriaOver, $issue);
		}
		
		if($criteriaOver)
			$criteria->mergeWith($criteriaOver, true);
		
		if($statusAlias == 'todo'){
			$criteria->addCondition('status.alias = :alias');
			$criteria->params['alias'] = 1;
		}elseif($statusAlias == 'open'){
			$criteria->addCondition('status.alias = :alias');
			$criteria->params['alias'] = 2;
		}elseif($statusAlias == 'done'){
			$criteria->addCondition('status.alias = :alias');
			$criteria->params['alias'] = 3;
		}elseif(Yii::app()->session['openIssues'] == true){
			$criteria->compare('status.closed_alias', 0);
		}
		
		$criteria->compare('overrun',$issue->overrun);
		$criteria->compare('project_id',$issue->project_id);
		$criteria->compare('t.user_id',$issue->user_id);
		$criteria->compare('assignee_id',$issue->assignee_id);
		$criteria->compare('status_id',$issue->status_id);
		$criteria->compare('type_id',$issue->type_id);
		$criteria->compare('priority',$issue->priority);
		$criteria->compare('t.label',$issue->label,true);
		$criteria->compare('t.description',$issue->description, true);
		
		if($project){
			$criteria->with['project'] = array('together' => true);
			$criteria->addCondition('project.label LIKE :project  OR project.code LIKE :project');
			$criteria->params['project'] = '%'.$project.'%';
		}
		
		if($order)
			$criteria->order = $order;
		else
			$criteria->order = 'due_date DESC, project_id ASC, t.label ASC';
		
		return new CActiveDataProvider(
				$issue, array(
						'criteria' => $criteria,
						'pagination' => array('pageSize' => $length),
				));
	}
	
	public function delayedIssues($criteriaOver, $issue)
	{
		if($issue->overdue){
			if(is_numeric($issue->overdue))
				$operator = '>= ' . $issue->overdue;
			else
				$operator = $issue->overdue;
				
			$criteriaOver->addCondition('TO_DAYS(NOW())-TO_DAYS(due_date) ' . $operator);
		}
		else{
			$criteriaOver->addCondition('TO_DAYS(NOW())-TO_DAYS(due_date) > 0');
		}
	
		return $criteriaOver;
	}
	
	public function overrunIssues($criteriaOver, $issue)
	{
		if($issue->overrun)
			$criteriaOver->compare('overrun', $issue->overrun, false, 'AND');
		elseif(!$issue->overdue)
		$criteriaOver->compare('overrun', '> 0', false, 'OR');
	
		return $criteriaOver;
	}
	
	
	public function getUserNotInTeam($teamId)
	{
		$criteria=new CDbCriteria;
		$criteria->addCondition('id NOT IN (SELECT user_id FROM user_team WHERE team_id = :teamId)');
		$criteria->params['teamId'] = $teamId;
		
		$criteria->compare('username',$this->owner->username,true);
		$criteria->compare('email',$this->owner->email,true);
		
		return new CActiveDataProvider('User', array(
				'criteria'=>$criteria,
		));
	}
	
	
	public function registeredInProject($owned = false, $highlight = false, $label = false)
	{
		$criteria=new CDbCriteria;
		if($owned){
			$criteria->compare('user_id', $this->owner->id);
		}else{
			$criteria->with = 'projectUsers';
			$criteria->together = true;
			$criteria->compare('projectUsers.user_id', $this->owner->id);
			if($highlight){
				$criteria->compare('projectUsers.visibility', 1);
			}
		}
		
		$criteria->compare('label', $label, true);
		$criteria->compare('code', $label, true, 'OR');

		$criteria->order = 'label DESC';
		
		return new CActiveDataProvider(
		'Project', array(
						'criteria' => $criteria,
						'pagination' => array('pageSize' => 50,),
		));
	}
	
	public function getActivities($search = null)
	{
		$date = date('Y-m-d', strtotime('monday this week'));//monday this week
		
		$criteria=new CDbCriteria;
		$criteria->compare('t.user_id', $this->owner->id);
		
		$this->setDateRangeCriteria($criteria, 't.log_date', $search->from, $search->to);
		
		
		/*if($from && $to){
			$criteria->addCondition('t.log_date between :from AND :to');
			$criteria->params['from'] = $from;
			$criteria->params['to'] = $to;
		}elseif($from){
			$criteria->addCondition('t.log_date >= :date');
			$date = $from;
			$criteria->params['date'] = $date;
		}elseif($to){
			$criteria->addCondition('t.log_date <= :date');
			$date = $to;
			$criteria->params['date'] = $date;
		}else{
			$criteria->addCondition('t.log_date >= :date');
			$date = date('Y-m-d', strtotime('monday this week'));//strtotime('monday this week')
			$criteria->params['date'] = $date;
		}*/
		//$criteria->addCondition('t.log_date >= :date');
		//$criteria->params['date'] = $date;
		
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
	
	public function getActivityDetail($search = null)
	{
		$criteria=new CDbCriteria;
		$criteria->compare('t.user_id', $this->owner->id);
		
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
			
		return false;
	}
	
	public function getUserWeekly($search = null)
	{
		$criteria=new CDbCriteria;
		$criteria->compare('t.user_id', $this->owner->id);
		
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
			
		return false;
	}
	
	public function getUserWeeklyIssues($search = null)
	{
		//$date = date('Y-m-d', strtotime('monday this week'));
		
		$criteria=new CDbCriteria;
		$criteria->compare('user_id', $this->owner->id);
		
		$this->setDateRangeCriteria($criteria, 'creation_date', $search->from, $search->to);
		
		$criteria->addCondition('comment != \'\'');
		
		return new CActiveDataProvider(
		'IssueLogs', array(
						'criteria' => $criteria,
						'pagination' => array('pageSize' => 50,),
		));
	}
	
	public function afterFind($event)
	{
		$this->setUname();
	}
	
	/*public function getUname()
	{
		return $this->setUname();
	}*/
	
	public function setUname()
	{
		if(!empty($this->owner->firstname)){
			$this->owner->uname = $this->owner->firstname;
		}elseif(!empty($this->owner->lastname)){
			if(!empty($this->uname))
				$this->owner->uname .= ' ' . $this->owner->lastname;
			else
				$this->owner->uname = $this->owner->lastname;
		}else{
				$this->owner->uname = $this->owner->username;}
				
				
		return $this->owner->uname;
	}
	
	protected function setDateRangeCriteria($criteria, $field, $from = false, $to = false)
	{
		if($from && $to){
			//$criteria->addCondition($field .' between :from AND :to');
			$criteria->addCondition($field . ' >= :from');
			$criteria->addCondition($field . ' <= :to');
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