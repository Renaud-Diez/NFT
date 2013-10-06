<?php

/**
 * This is the model class for table "project".
 *
 * The followings are the available columns in table 'project':
 * @property integer $id
 * @property integer $owner_id
 * @property string $code
 * @property string $label
 * @property string $description
 * @property integer $topic_id
 *
 * The followings are the available model relations:
 * @property Milestone[] $milestones
 * @property User $owner
 * @property Topic $topic
 * @property ProjectDocument[] $projectDocuments
 * @property Issue[] $issues
 * @property ProjectLogs[] $projectLogs
 * @property ProjectRelation[] $projectRelations
 * @property ProjectRelation[] $projectRelations1
 * @property ProjectUser[] $projectUsers
 * @property Version[] $versions
 */
class Project extends CActiveRecord
{
	public $oldRecord;

	const RELATED_TO			=0;
	const RELATED_DUPLICATES	=1;
	const RELATED_DUPLICATEBY	=2;
	const RELATED_BLOCKS		=3;
	const RELATED_BLOCKEDBY		=4;
	const RELATED_PRECEDES		=5;
	const RELATED_FOLLOWS		=6;
	const RELATED_PARENT		=7;
	const RELATED_CHILD			=8;
	
	const HOURSBYDAY			=7.6;
	const DAYSBYWEEK			=5;

	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Project the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'project';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
		array('code, topic_id', 'required'),
		array('user_id, topic_id, budget, allowed_effort, hours, days', 'numerical', 'integerOnly'=>true),
		array('code, label', 'length', 'max'=>45),
		array('description', 'safe'),
		// The following rule is used by search().
		// Please remove those attributes that should not be searched.
		array('id, user_id, code, label, description, topic_id, budget', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'milestones' => array(self::HAS_MANY, 'Milestone', 'project_id'),
			'owner' => array(self::BELONGS_TO, 'User', 'user_id'),
			'topic' => array(self::BELONGS_TO, 'Topic', 'topic_id'),
			'projectDocuments' => array(self::HAS_MANY, 'ProjectDocument', 'project_id'),
			'issues' => array(self::MANY_MANY, 'Issue', 'project_issues(project_id, issue_id)'),
			'projectLogs' => array(self::HAS_MANY, 'ProjectLogs', 'project_id'),
			'projectRelations' => array(self::HAS_MANY, 'ProjectRelation', 'project_id'),
			'projectRelations1' => array(self::HAS_MANY, 'ProjectRelation', 'related_id'),
			'projectUsers' => array(self::HAS_MANY, 'ProjectUser', 'project_id'),
			'users' => array(self::HAS_MANY, 'User', array('user_id'=>'id'),'through' => 'projectUsers'),
			'versions' => array(self::HAS_MANY, 'Version', 'project_id'),
			'events' => array(self::HAS_MANY, 'Event', 'project_id'),
			'children' => array(self::HAS_MANY, 'Project', 'parent_id'),
			'parent' => array(self::BELONGS_TO, 'Project', 'parent_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'user_id' => 'Owner',
			'code' => 'Code',
			'label' => 'Name',
			'description' => 'Description',
			'topic_id' => 'Topic',
			'hours' => 'Hours by day',
			'days' => 'Days by week',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search()
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		$criteria->compare('user_id',$this->user_id);
		$criteria->compare('code',$this->code,true);
		$criteria->compare('label',$this->label,true);
		$criteria->compare('description',$this->description,true);
		$criteria->compare('topic_id',$this->topic_id);
		$criteria->compare('hours',$this->hours);
		$criteria->compare('days',$this->days);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	public function afterFind()
	{
		$this->oldRecord=clone $this;
		return parent::afterFind();
	}
	
	
	public function behaviors()
	{
		return array(
				'ProjectBehavior'=>array(
				'class'=>'application.components.behaviors.ProjectBehavior'
				),
		);
	}

	/**
	 *
	 * Save the current data into the Project Logs object before saving the new data ...
	 */
	protected function beforeSave()
	{
		if(empty($this->budget))
			$this->budget = 0;
		
		$projectLog = new ProjectLogs;
		$projectLog->project_id = $this->id;
		$projectLog->user_id = Yii::app()->user->id;
		$projectLog->owner_id = $this->user_id;
		$projectLog->topic_id = $this->topic_id;
		$projectLog->label = $this->label;
		$projectLog->budget = $this->budget;
		$projectLog->creation_date = date('Y-m-d H:i:s');

		if(!is_null($this->description))
			$projectLog->description = $this->description;

		if(!is_null($this->parent_id))
			$projectLog->parent_id = $this->parent_id;

		$projectLog->save();

		return parent::beforeSave();
	}

	protected function afterSave()
	{
		$member = ProjectUser::model()->findByAttributes(array('project_id'=>$this->id, 'user_id'=>$this->user_id));

		if(is_null($member))
		$member = new ProjectUser;
		elseif($member->role != 'Project Owner'){
			$member->role = 'Project Owner';
			$member->save();
		}

		$this->eventLog();
	}

	protected function eventLog()
	{
		$event = new Event;
		$event->user_id = Yii::app()->user->id;
		$event->project_id = $event->ref_id = $this->id;
		$event->ref_object = 'Project';
		$event->creation_date = date('Y-m-d H:i:s');
			
		if(!is_null($this->oldRecord->id))
		{
			if($this->oldRecord->label != $this->label)
			$changeLog = '<br>Name has been modified from <i>'.$this->oldRecord->label.'</i> to <i>' . $this->label . '</i>';
			if($this->oldRecord->code != $this->code)
			$changeLog .= '<br>Code has been modified from <i>'.$this->oldRecord->code.'</i> to <i>' . $this->code. '</i>';
			if($this->oldRecord->user_id != $this->user_id){
				$changeLog .= '<br>Owner has been modified from <i>'.$this->oldRecord->owner->username.'</i> to <i>' . $this->owner->username. '</i>';
				$event->criticity = $event->CRITICITY_HIGH;
			}
			
			if($this->oldRecord->topic_id != $this->topic_id)
			$changeLog .= '<br>Topic has been modified from <i>'.$this->oldRecord->topic->label.'</i> to <i>' . $this->topic->label. '</i>';
	
			$event->description = '<b>Project has been updated</b>' . $changeLog;
		}
		else {
			$changeLog .= '<br>Owner is <i>'.$this->owner->username.'</i>';
			
			if(!is_null($this->parent_id)){
				$changeLog .= '<br>Project is a subproject of <i>' . $this->parent->label .'</i>';
			}
				
			
			$event->description = '<b>New Project <i>'.$this->label.'</i> has been added</b>' . $changeLog;
		}
		
		$event->save();
	}
}