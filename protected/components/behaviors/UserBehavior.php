<?php
class UserBehavior extends CActiveRecordBehavior
{
	public function openedQuestion()
	{
		$criteria=new CDbCriteria;
		$criteria->with = 'type';
		$criteria->together = true;
		$criteria->compare('assignee_id', $this->owner->id);
		$criteria->compare('type.label', 'Question');
		$criteria->order = 'due_date DESC';
		
		return new CActiveDataProvider(
		'Issue', array(
						'criteria' => $criteria,
						'pagination' => array('pageSize' => 10,),
		));
	}
	
	public function assignedIssues($typeId = false, $projectId = false)
	{
		$criteria=new CDbCriteria;
		$criteria->with = array('type' => array('together' => true), 'status' => array('together' => true));
		//$criteria->with = 'status';
		//$criteria->together = true;
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
	
	public function registeredInProject($owned = false, $highlight = false)
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

		$criteria->order = 'label DESC';
		
		return new CActiveDataProvider(
		'Project', array(
						'criteria' => $criteria,
						'pagination' => array('pageSize' => 50,),
		));
	}
	
	public function getActivities($from = null, $to = null)
	{
		$date = date('Y-m-d', strtotime('monday this week'));//monday this week
		
		$criteria=new CDbCriteria;
		$criteria->compare('i.user_id', $this->owner->id);
		
		
		if($from && $to){
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
		}
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
	
	public function getActivityDetail($from = false, $to = false)
	{
		$criteria=new CDbCriteria;
		$criteria->compare('i.user_id', $this->owner->id);
		
		if($from && $to){
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
		}
		
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
	
	public function getUserWeekly($from = null, $to = null)
	{
		$date = date('Y-m-d', strtotime('monday this week'));
		
		$criteria=new CDbCriteria;
		$criteria->compare('i.user_id', $this->owner->id);
		$criteria->addCondition('t.log_date >= :date');
		$criteria->addCondition('t.comment != \'\'');
		$criteria->params['date'] = $date;
		
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
	
	public function getUserWeeklyIssues($date = null)
	{
		$date = date('Y-m-d', strtotime('monday this week'));
		
		$criteria=new CDbCriteria;
		$criteria->compare('user_id', $this->owner->id);
		$criteria->addCondition('creation_date >= :date');
		$criteria->params['date'] = $date;
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
}