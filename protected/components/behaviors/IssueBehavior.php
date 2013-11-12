<?php
class IssueBehavior extends CActiveRecordBehavior
{
	public $delay = null;
	public $available_time = null;
	public $arr = null;

	private $_oldAttributes = array();
	private $_event = null;

	public function loadMetaData()
	{

	}
	
	public function lastComment()
	{
		$criteria = new CDbCriteria;
		$criteria->compare('issue_id', $this->owner->id);
		$criteria->addCondition('comment <> \'\'');
		$criteria->order = 'id DESC';
		
		return IssueLogs::model()->find($criteria);
	}
	
	public function delayedIssues($criteria)
	{
		if($this->owner->overdue){
			if(is_numeric($this->owner->overdue))
				$operator = '>= ' . $this->owner->overdue;
			else
				$operator = $this->owner->overdue;
			
			$criteria->addCondition('TO_DAYS(NOW())-TO_DAYS(due_date) ' . $operator);
		}
		else{
			$criteria->addCondition('TO_DAYS(NOW())-TO_DAYS(due_date) > 0');
		}
	
		return $criteria;
	}
	
	public function overrunIssues($criteria)
	{
		if($this->owner->overrun)
			$criteria->compare('overrun', $this->owner->overrun, false, 'AND');
		elseif(!$this->owner->overdue)
			$criteria->compare('overrun', '> 0', false, 'OR');
		
		return $criteria;
	}
	
	
	public function getSubissues($parent_id = null)
	{
		$criteria = new CDbCriteria;
		
		if(!is_null($parent_id)){
			$criteria->condition = 'parent_id=:parent_id';
			$criteria->params[':parent_id'] = $parent_id;
		}else{
			$criteria->condition = 'parent_id IS NULL';
		}
		
		$criteria->order = 'label ASC';
		
		$models = Issue::model()->findAll($criteria);

		if(count($models) > 0)
		{
			foreach($models as $model){
				unset($arr);
				$arr['text'] = '&nbsp;<i class="'.$this->treeIcon($model->type->label).'" style="margin-right:5px;"></i>'. CHtml::link($model->type->label. ': <i>' . $model->label . '</i>', array('issue/view', 'id'=>$model->id));
				$arr['id'] = $model->id;
				
				$children = $this->getSubissues($model->id);
				if($children){
					$arr['hasChildren'] = true;
					$arr['children'] = $children;
				}
				$array[] = $arr;
			}
			
			return $array;
			//return array($arr);
		}

		return false;
	}
	
	public function subtasks($issue, $filter)
	{
		$criteria = new CDbCriteria;
	
		/*$criteria->with['issueRelations'] = array('together' => true);
		$criteria->compare('related_id', $this->owner->id);*/
		
		$criteria->compare('parent_id', $this->owner->id);
		
	
		$models = Issue::model()->findAll($criteria);
	
		if(count($models) > 0)
		{
			foreach($models as $model){
				$GLOBALS['ids'][] = $model->id;
				$GLOBALS['arr'][$model->status->alias][] = $model;
				if(is_array($GLOBALS['ids']) && !in_array($model->id, $GLOBALS['ids']));
					$model->subtasks($issue, $filter);
			}
		}

		return $GLOBALS['arr'];
	}

	
	protected function treeIcon($type = null)
	{
		if($type == 'Todo')
			return $icon='icon-check';
		elseif($type == 'Info')
			return $icon='icon-info-sign';
		elseif($type == 'Bug Fix')
			return $icon='icon-wrench';
		elseif($type == 'Feature')
			return $icon='icon-cog';
		elseif($type == 'Support')
			return $icon='icon-signal';
		elseif($type == 'Change Request')
			return $icon='icon-random';
		elseif($type == 'Analyse')
			return $icon='icon-search';
		elseif($type == 'Incident')
			return $icon='icon-fire';
		else
			return $icon='icon-star-empty';
	}
	
	public function getWorkload()
	{
		$criteria = new CDbCriteria;
		
		$criteria=new CDbCriteria;
		$criteria->compare('t.issue_id', $this->owner->id);
		
		$where=$criteria->condition;
		$params=$criteria->params;
		
		$sql = Yii::app()->db->createCommand()
		->select('SUM(t.time_spent) as value, ta.label as label')
		->from('timetracker t')
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
	
	public function getLoggedEffort($id = null)
	{
		if(is_null($id))
			$id = $this->owner->id;
		
		return Yii::app()->db->createCommand()
		->select('sum(time_spent) as timetrack')
		->from('timetracker t')
		->where('t.issue_id = :issueId', array(':issueId' => $id))
		->queryScalar();
	}
	
	public function estimatedRemainingEffort()
	{
		$loggedEffort = round($this->getLoggedEffort());
		if(empty($loggedEffort))
			$loggedEffort = 0;
		
		$estimatedEffort = round($this->owner->estimated_time);
		if(empty($estimatedEffort))
			$estimatedEffort = 0;
			
		$completion = $this->owner->completion;
		if(empty($completion))
			$completion = 0;
			
		$theoricalEfforForCompletion = round($estimatedEffort*$completion/100);
		$theoricalRemainingEffort = $estimatedEffort-$theoricalEfforForCompletion;
		
		//$estimatedRemainingEffort = $loggedEffort*$estimatedEffort*(100-$completion)/100;//19*39*(100-49)
		if($completion > 0)
			$estimatedRemainingEffort = round($loggedEffort*100/$completion)-$loggedEffort;
		else
			$estimatedRemainingEffort = 0;

		return array(	'estimatedRemainingEffort' => $estimatedRemainingEffort, 
						'estimatedEffort' => $estimatedEffort, 
						'loggedEffort' => $loggedEffort,
						'theoricalRemainingEffort' => $theoricalRemainingEffort,
						'theoricalEfforForCompletion' => $theoricalEfforForCompletion);
	}
	
	public function beforeSave($event)
	{
		$this->owner->user_id = Yii::app()->user->id;
		if($this->owner->isNewRecord)
			$this->owner->creation_date = date('Y-m-d H:i:s');
			
		if(is_null($this->owner->completion))
			$this->owner->completion = 0;
		
		$arrEffort = $this->estimatedRemainingEffort();
		
		$this->owner->logged_effort = $arrEffort['loggedEffort'];
		$this->owner->overrun = $arrEffort['loggedEffort']-$arrEffort['theoricalEfforForCompletion'];
		$this->owner->theorical_remaining_effort = $arrEffort['theoricalRemainingEffort'];
		$this->owner->optimistic_remaining_effort = $arrEffort['estimatedRemainingEffort'];
		
		if($arrEffort['theoricalEfforForCompletion'] > 0)
			$this->owner->pessimistic_remaining_effort = $arrEffort['estimatedRemainingEffort']*$arrEffort['estimatedRemainingEffort']/$arrEffort['theoricalEfforForCompletion'];
		else
			$this->owner->pessimistic_remaining_effort = 0;
		
	
		return parent::beforeSave($event);
	}
	
	
	
	public function getParticipants()
	{
		$criteria = new CDbCriteria;
		$criteria->with = array('issueUsers');
		$criteria->together = true;
		$criteria->condition = 'issue_id=:issue_id';
		$criteria->params[':issue_id'] = $this->owner->id;
		$criteria->order = 'username ASC';
		
		return new CActiveDataProvider(
		'User', array(
						'criteria' => $criteria,
						'pagination' => array('pageSize' => 10,),
		));
	}

	public function unregisterParticipant($userId)
	{
		IssueUser::model()->deleteAll('issue_id = :issueId AND user_id = :userId', array('issueId' => $this->owner->id, 'userId' => $userId));
	}

	public function registerParticipant($userIds)
	{
		//Yii::trace('Participants ' . count($userIds),'models.issue');
		if(count($userIds)>0)
		{
			$member = $error = 0;
			foreach($userIds as $userId)
			{
				$model = IssueUser::model()->findByAttributes(array('issue_id' => $this->owner->id, 'user_id' => $userId));
				if(is_null($model))
				{
					$model = new IssueUser;
					$model->issue_id = $this->owner->id;
					$model->user_id = $userId;

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
				$error .= ' Participant(s) unsuccessfully added!';
			if($member > 0)
				$member .= ' Participant(s) successfully added!';
		}
		
		return array('error' => $error, 'member' => $member);
	}
	
	public function imOnIt()
	{
		if($this->owner->assignee_id == Yii::app()->user->id){
			$this->owner->assignee_id = null;
			$message = 'You are not anymore assigned to the ' . $this->owner->type->label;
		}
		else{
			$this->owner->assignee_id = Yii::app()->user->id;
			$message = 'You are now assigned to this ' . $this->owner->type->label;
		}
		
		if($this->owner->save())
			return $message;
		else
			return 'Impossible to be assigned to this ' . $this->owner->type->label;
	}

	public function getAvailableType($topic_id)
	{
		$criteria = new CDbCriteria();
		$criteria->with = array('topics');
		$criteria->together = true;
		$criteria->condition = 'topics.id=:topic_id';
		$criteria->params = array(':topic_id' => $topic_id);

		return CHtml::listData(IssueType::model()->findAll($criteria), 'id', 'label');
	}

	public function getPriorities($value)
	{
		Yii::trace('Returned value ' . $value,'models.issue');
		$return = array(
			Issue::PRIORITY_LOW 		=> 'Low',
			Issue::PRIORITY_NORMAL		=> 'Normal',
			Issue::PRIORITY_HIGH		=> 'High',
			Issue::PRIORITY_URGENT		=> 'Urgent',
			Issue::PRIORITY_IMMEDIATE	=> 'Immediate',
		);

		if(!is_null($value)){
			$return = $return[$value];
		}

		return $return;
	}

	public function getAssignableUsers($projectId = null)
	{
		if(is_null($projectId))
		$projectId = $this->owner->project_id;

		$dataProvider = new CActiveDataProvider('User',
		array('criteria'=> array('with' => array('projectUsers' => array('condition' => 'project_id=:project_id',
																											'params'=>array('project_id'=>$projectId), 
																											'together' => true)
		),
		)
		)
		);
			
		$users = CHtml::listData($dataProvider->getData(), 'id', 'username');

		return $users;
	}
	
	
	
	public function getTransitions()
	{
		$criteria=new CDbCriteria;
		$criteria->compare('issue_id', $this->owner->id);
		$criteria->order = 'label ASC';
		
		$dataProvider = new CActiveDataProvider('IssueTransition', array(
									'criteria'=>$criteria,
									'pagination' => array('pageSize' => 3)
		));
		
		return $dataProvider;
	}
	

	public function getLogs()
	{
		$criteria=new CDbCriteria;
		$criteria->compare('issue_id',$this->owner->id);
		$criteria->order = 'creation_date ASC';

		$dataProvider = new CActiveDataProvider('IssueLogs', array(
									'criteria'=>$criteria,
									'pagination' => array('pageSize' => 100)
		));

		return $dataProvider;
	}
	
	public function getDocuments()
	{
		$criteria=new CDbCriteria;
		$criteria->with = array('document');
		$criteria->together = true;
		$criteria->compare('issue_id',$this->owner->id);
		$criteria->order = 'document.label ASC,creation_date ASC';

		$dataProvider = new CActiveDataProvider('IssueDocument', array(
									'criteria'=>$criteria,
									'pagination' => array('pageSize' => 100)
		));

		return $dataProvider;
	}
	
	public function getEvents($type = null, $date = null)
	{
		$criteria=new CDbCriteria;

		$criteria->compare('ref_id',$this->owner->id);
		$criteria->compare('ref_object', 'Issue');
		
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
	
	public function getActivities($from = null, $to = null)
	{
		$date = date('Y-m-d', strtotime('monday this week'));
		
		$criteria=new CDbCriteria;
		$criteria->compare('t.issue_id', $this->owner->id);
		$criteria->addCondition('t.log_date >= :date');
		$criteria->params['date'] = $date;
		
		$where=$criteria->condition;
		$params=$criteria->params;
		
		$sql = Yii::app()->db->createCommand()
		->select('SUM(t.time_spent) as value, ta.label as label')
		->from('timetracker t')
		->join('time_activity ta', 'ta.id = t.activity_id')
		->where($where, $params)
		->group('ta.label, t.log_date')
		->queryAll();
		
		foreach($sql as $record){
			$arrData[] = array($record['label'], (float) $record['value']);
		}//$arrData[] = array('Coding', (float) 33.0);
		
		if(count($arrData) > 0)
			return $arrData;
			
		return false;
	}
	
	public function getActivityDetail($from = null, $to = null)
	{
		//$date = date('Y-m-d', strtotime('monday this week'));
		
		$criteria=new CDbCriteria;
		$criteria->compare('issue_id', $this->owner->id);
		//$criteria->addCondition('t.log_date >= :date');
		//$criteria->params['date'] = $date;
		
		$where=$criteria->condition;
		$params=$criteria->params;
		
		$sql = Yii::app()->db->createCommand()
		->select('SUM(t.time_spent) as time_spent, ta.label as activity')
		->from('timetracker t')
		->join('time_activity ta', 'ta.id = t.activity_id')
		->where($where, $params)
		->group('ta.label')
		->queryAll();

		
		if(count($sql) > 0){
			return new CArrayDataProvider($sql, array(
			'id' => 'activities-'.$this->owner->id,
			'pagination' => array('pageSize' => 10)
			));
		}
			
		return false;
	}
	
	public function getTimeLog($from = null, $to = null)
	{
		$date = date('Y-m-d', strtotime('monday this week'));
		
		$criteria=new CDbCriteria;
		$criteria->with = array('activity');
		$criteria->together = true;
		$criteria->compare('issue_id', $this->owner->id);
		$criteria->addCondition('log_date >= :date');
		$criteria->params['date'] = $date;
		$criteria->order = 'activity.label, log_date';

		return new CActiveDataProvider(Timetracker, array(
			'criteria'=>$criteria,
			'pagination' => array('pageSize' => 100)
		));
	}

	public function afterFind($event)
	{
		$this->setOldAttributes($this->owner->getAttributes());
	}

	public function afterSave($event)
	{
		$newAttributes = $this->owner->getAttributes();
		$oldAttributes = $this->getOldAttributes();

		//if($this->owner->isNewRecord){
			$this->projectIssue();
		//}

		$this->issueLog($newAttributes);
		$this->eventLog($newAttributes, $oldAttributes);
		//$this->issueStats();
	}

	public function checkDelay()
	{
		$assigneeId = $this->owner->assignee_id;
		$interval = $this->eventDelay();//$this->available_time

		if($interval <= 0){
			//INSERT NEW EVENT
		}
		else{
			if(empty($assigneeId)){
				//INSERT NEW EVENT -> CRITICAL
			}
			else{
				//CHECK ASSIGNEE WORKLOAD
				if($interval < 24){
					//INSERT NEW EVENT -> CRITICAL
				}elseif($interval < 48){
					//INSERT NEW EVENT -> VERYHIGH
				}
			}
		}
	}

	public function getRelatedValue($attribute, $data)
	{
		$field = 'label';
		if(strstr($attribute, '_id')){
			$relation = substr($attribute, 0, -3);
				
			if($relation == 'user' || $relation == 'assignee')
			$field = 'username';

			return $data->{$relation}->{$field};
		}
		elseif($attribute == 'due_date'){
			return Yii::app()->dateFormatter->format('y-MM-d', $data->due_date);
		}

		return $data->{$attribute};
	}

	public function getRelatedOptions($value = null)
	{
		$return = array(
		Issue::RELATED_TO 			=> 'Related',
		Issue::RELATED_DUPLICATES	=> 'Duplicates',
		Issue::RELATED_DUPLICATEBY	=> 'Duplicate by',
		Issue::RELATED_BLOCKS		=> 'Blocks',
		Issue::RELATED_BLOCKEDBY	=> 'Blocked by',
		Issue::RELATED_PRECEDES		=> 'Precedes',
		Issue::RELATED_FOLLOWS		=> 'Follows',
		Issue::RELATED_PARENT		=> 'Parent',
		Issue::RELATED_CHILD		=> 'Child',
		);

		if(!is_null($value))
		$return = $return[$value];
		 
		return $return;
	}

	public function getRelatedIssues($relation = null)
	{
		$criteria=new CDbCriteria;

		$criteria->compare('issue_id',$this->owner->id);

		if(!is_null($relation))
		$criteria->compare('relation',$relation);

		return new CActiveDataProvider(IssueRelation, array(
			'criteria'=>$criteria,
			'pagination' => array('pageSize' => 10)
		));
	}

	protected function getOldAttributes()
	{
		return $this->_oldAttributes;
	}

	protected function setOldAttributes($value)
	{
		$this->_oldAttributes=$value;
	}

	protected function projectIssue()
	{
		$model = ProjectIssues::model()->findByAttributes(array('project_id' => $this->owner->project_id, 'issue_id' => $this->owner->id));

		if(is_null($model))
		{
			$model = new ProjectIssues;
			$model->project_id = $this->owner->project_id;
			$model->issue_id = $this->owner->id;
				
			if(!empty($this->owner->version_id))
			$model->version_id = $this->owner->version_id;

			if(!empty($this->owner->milestone_id))
			$model->milestone_id = $this->owner->milestone_id;
		}
		else
		{
			if(!empty($this->owner->version_id) && $model->version_id != $this->owner->version_id)
				$model->version_id = $this->owner->version_id;
			
			if(!empty($this->owner->milestone_id) && $model->milestone_id != $this->owner->milestone_id)
				$model->milestone_id = $this->owner->milestone_id;
		}
		$model->save();
	}

	protected function issueLog($newAttributes)
	{
		$log = new IssueLogs;
			
		foreach($newAttributes as $field => $value){
			if($field == 'id')
			$log->issue_id = $value;
			elseif(!in_array($field, array('project_id','user_id','creation_date', 'parent_id')))
			$log->{$field} = $value;
		}

		Yii::trace('Comment Behavior: ' . $this->owner->comment,'models.issue');

		$log->user_id = Yii::app()->user->id;
		$log->creation_date = date('Y-m-d H:i:s');
		$log->comment = $this->owner->comment;

		$log->save();
	}

	protected function eventLog($newAttributes, $oldAttributes)
	{
		$this->_event = new Event;
		$this->_event->user_id = Yii::app()->user->id;
		$this->_event->project_id = $this->owner->project_id;
		$this->_event->ref_id = $this->owner->id;
		$this->_event->ref_object = 'Issue';
		$this->_event->creation_date = date('Y-m-d H:i:s');

		if($this->owner->isNewRecord){
			$this->_event->description = '<b>New '.$this->owner->type->label.' "<i>'.$this->owner->label.'</i>" <small>#'.$this->owner->id.'</small> has been added</b>';
		}
		else{
			$this->_event->description = '<b>'.$this->owner->type->label.' "<i>'.$this->owner->label.'</i>" <small>#'.$this->owner->id.'</small> has been updated</b>';
		}

		//ATTRIBUTES THAT INFLUENCE CRITICITY LEVEL
		$this->_event->description .= $this->eventPriority($newAttributes['priority_id'], $oldAttributes['priority_id']);
		$this->_event->description .= $this->eventVersion($newAttributes['version_id'], $oldAttributes['version_id']);
		$this->_event->description .= $this->eventMilestone($newAttributes['milestone_id'], $oldAttributes['milestone_id']);
		$this->_event->description .= $this->eventCompletion($newAttributes['completion'], $oldAttributes['completion']);
		$this->_event->description .= $this->eventEstimatedTime($newAttributes['estimated_time'], $oldAttributes['estimated_time']);
		$this->_event->description .= $this->eventAssignee($newAttributes['assignee_id'], $oldAttributes['assignee_id']);
		$this->_event->description .= $this->eventDueDate($newAttributes['due_date'], $oldAttributes['due_date']);
		//$this->_event->description .= $this->eventDelay();

		//OTHER ATTRIBUTES
		$this->_event->description .= $this->eventType($newAttributes['type_id'], $oldAttributes['type_id']);


		Yii::trace('Issue Event: ' . $this->_event->description,'models.event');

		$this->_event->save();
	}

	protected function eventVersion($new = null, $old = null)
	{
		if(!empty($new))
		return '<br>The '.$this->owner->type->label.' has been set for version/phase <i>' . $this->owner->version->label .'</i>';
		elseif(!empty($old) && $old != $new)
		return '<br>The '.$this->owner->type->label.' has been moved to version/phase <i>' . $this->owner->version->label .'</i>';
	}

	protected function eventMilestone($new = null, $old = null)
	{
		if($this->owner->isNewRecord && !is_null($new))
		return '<br>The '.$this->owner->type->label.' has been set for Milestone <i>' . $this->owner->milestone->label .'</i>';
		elseif(!is_null($old) && $old != $new)
		return '<br>The '.$this->owner->type->label.' has been moved to Milestone <i>' . $this->owner->milestone->label .'</i>';
	}

	protected function eventPriority($new = null, $old = null)
	{
		if($this->owner->isNewRecord && !is_null($new))
		return '<br>Priority has been set to <i>' . $this->getPriorities($new) .'</i>';
		elseif(!is_null($old) && $old != $new){
			if($new > $old){
				$this->_event->criticity = Event::CRITICITY_HIGH;
				return '<br>Priority has been increased to <i>' . $this->getPriorities($new) .'</i>';
			}
			elseif($new < $old){
				return '<br>Priority has been decreased to <i>' . $this->getPriorities($new) .'</i>';
			}
		}
	}

	protected function eventAssignee($new = null, $old = null)
	{
		if($this->owner->isNewRecord && !empty($new)){
			return '<br>Assignee is <i>' . $this->owner->user->username .'</i>';
		}
		elseif($this->owner->isNewRecord && empty($new)){
			$this->_event->criticity = Event::CRITICITY_HIGH;
			return '<br>No Assignee defined yet!';
		}
		elseif(!empty($old) && $old != $new){
			if($new > $old){
				$this->_event->criticity = Event::CRITICITY_HIGH;
				return '<br>Re-assigned to <i>' . $this->owner->user->username .'</i>';
			}
		}
	}

	protected function eventDueDate($new = null, $old = null)
	{
		if($this->owner->isNewRecord && !empty($new)){
			return '<br>Due date has been set to ' . $new;
		}
		elseif($old != $new){
			if(empty($old)){
				$this->_event->criticity = Event::CRITICITY_HIGH;
				return '<br>Due date has been unset!';
			}
			elseif($diffTime < 0){
				$this->_event->criticity = Event::CRITICITY_HIGH;
				return '<br>Due date has advanced!';
			}
			else{
				return '<br>Due date has been moved back';
			}
		}
	}

	protected function eventCompletion($new = null, $old = null)
	{
		if(!$this->owner->isNewRecord && empty($new)){
			if($new < $old){
				$this->_event->criticity = Event::CRITICITY_VERYHIGH;
				return '<br>The ' .$this->owner->type->label . ' completion has decreased!';
			}
			elseif($new > $old){
				return '<br>The ' .$this->owner->type->label . ' completion has increased.';
			}
		}
	}

	protected function eventEstimatedTime($new = null, $old = null)
	{
		if($this->owner->isNewRecord && empty($new)){
			$this->_event->criticity = Event::CRITICITY_HIGH;
			return '<br>No effort has yet been set for the '.$this->owner->type->label;
		}
		else{
			if(empty($new) && !empty($old)){
				$this->_event->criticity = Event::CRITICITY_VERYHIGH;
				return '<br>Effort has been reset!';
			}
			elseif($old < $new){
				$this->_event->criticity = Event::CRITICITY_VERYHIGH;
				return '<br>The requested effort for completion has been increased!';
			}
			elseif($old != $new){
				return '<br>The requested effort for completion has been decreased.';
			}
				
		}
	}

	protected function eventStatus($new, $old = null)
	{
		if($this->owner->isNewRecord && !is_null($new))
		return '<br>Status has been set to <i>' . $this->owner->status->label .'</i>';
		elseif(!is_null($old) && $old != $new){
			return '<br>Status has been changed to <i>' . $this->owner->status->label .'</i>';
		}
	}

	protected function eventType($new, $old = null)
	{
		if(!$this->owner->isNewRecord){
			if($new != $old){
				$this->_event->criticity = Event::CRITICITY_HIGH;
				return '<br>Issue has been redefined as <i>' . strtoupper($this->owner->type->label) .'</i>';
			}
		}
	}

	protected function eventDelay()
	{
		$from = $this->owner->due_date;
		$availableTime = DateTimeHelper::timeDiff($from);

		$estimatedTime = $this->owner->estimated_time;

		if($availableTime['d'] <= 0 && $availableTime['h'] <= 0){
			$this->_event->criticity = Event::CRITICITY_CRITICAL;
		}
		elseif((($availableTime['d']*8)+$availableTime['h']) > $estimatedTime){
			$this->_event->criticity = Event::CRITICITY_VERYHIGH;
		}

		return $availableTime;
	}
}