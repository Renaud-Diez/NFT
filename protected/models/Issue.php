<?php

/**
 * This is the model class for table "issue".
 *
 * The followings are the available columns in table 'issue':
 * @property integer $id
 * @property string $label
 * @property integer $project_id
 * @property integer $user_id
 * @property integer $assignee_id
 * @property integer $status_id
 * @property integer $type_id
 * @property integer $version_id
 * @property integer $milestone_id
 * @property integer $priority
 * @property string $estimated_time
 * @property integer $private
 *
 * The followings are the available model relations:
 * @property IssueStatus $status
 * @property Project $project
 * @property Version $version
 * @property User $assignee
 * @property User $user
 * @property Milestone $milestone
 * @property IssueType $type
 * @property IssueDocument[] $issueDocuments
 * @property IssueLogs[] $issueLogs
 * @property IssueRelation[] $issueRelations
 * @property IssueRelation[] $issueRelations1
 * @property ProjectIssues[] $projectIssues
 * @property Timetracker[] $timetrackers
 */
class Issue extends CActiveRecord
{
	CONST PRIORITY_LOW 			= 0;
	CONST PRIORITY_NORMAL 		= 1;
	CONST PRIORITY_HIGH 		= 2;
	CONST PRIORITY_URGENT 		= 3;
	CONST PRIORITY_IMMEDIATE 	= 4;
	
	CONST RELATED_TO			= 0;
	CONST RELATED_DUPLICATES	= 1;
	CONST RELATED_DUPLICATEBY	= 2;
	CONST RELATED_BLOCKS		= 3;
	CONST RELATED_BLOCKEDBY		= 4;
	CONST RELATED_PRECEDES		= 5;
	CONST RELATED_FOLLOWS		= 6;
	const RELATED_PARENT		= 7;
	const RELATED_CHILD			= 8;
	
	public $overdue;
	
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Issue the static model class
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
		return 'issue';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('project_id, user_id, status_id, type_id', 'required'),
			array('project_id, user_id, assignee_id, owner_id, status_id, type_id, version_id, milestone_id, private, completion, parent_id', 'numerical', 'integerOnly'=>true),
			array('label', 'length', 'max'=>150),
			array('estimated_time', 'length', 'max'=>6),
			array('completion', 'length', 'max'=>4),
			array('overrun, logged_effort, theorical_remaining_effort, pessimistic_remaining_effort, optimistic_remaining_effort', 'numerical'),
			array('description, comment, due_date, priority, overdue, code', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, label, project_id, user_id, assignee_id, status_id, type_id, version_id, milestone_id, priority, estimated_time, private, completion, priority, due_date, code', 'safe', 'on'=>'search'),
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
			'status' => array(self::BELONGS_TO, 'IssueStatus', 'status_id'),
			'project' => array(self::BELONGS_TO, 'Project', 'project_id'),
			'version' => array(self::BELONGS_TO, 'Version', 'version_id'),
			'assignee' => array(self::BELONGS_TO, 'User', 'assignee_id'),
			'user' => array(self::BELONGS_TO, 'User', 'user_id'),
			'owner' => array(self::BELONGS_TO, 'User', 'owner_id'),
			'milestone' => array(self::BELONGS_TO, 'Milestone', 'milestone_id'),
			'type' => array(self::BELONGS_TO, 'IssueType', 'type_id'),
			'issueDocuments' => array(self::HAS_MANY, 'IssueDocument', 'issue_id'),
			'issueLogs' => array(self::HAS_MANY, 'IssueLogs', 'issue_id'),
			'issueRelations' => array(self::HAS_MANY, 'IssueRelation', 'issue_id'),
			'issueRelations1' => array(self::HAS_MANY, 'IssueRelation', 'related_id'),
			'issueUsers' => array(self::HAS_MANY, 'IssueUser', 'user_id'),
			'projectIssues' => array(self::HAS_MANY, 'ProjectIssues', 'issue_id'),
			'timetrackers' => array(self::HAS_MANY, 'Timetracker', 'issue_id'),
			'parent' => array(self::BELONGS_TO, 'Issue', 'parent_id'),
			'remainingCompletion' => array(self::HAS_ONE, 'RemainingCompletion', 'id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'label' => 'Label',
			'project_id' => 'Project',
			'user_id' => 'User',
			'assignee_id' => 'Assignee',
			'owner_id' => 'Owner',
			'status_id' => 'Status',
			'type_id' => 'Type',
			'version_id' => 'Version',
			'milestone_id' => 'Milestone',
			'priority' => 'Priority',
			'estimated_time' => 'Effort',
			'due_date' => 'Due Date',
			'code' => 'Code',
			'private' => 'Private',
			'description' => 'Description',
			'completion' => 'Completion',
			'comment' => 'Comment',
			'parent_id' => 'Parent',
			'overrun' => 'Overrun',
			'overdue' => 'Overdue',
			'logged_effort' => 'Logged Effort',
			'theorical_remaining_effort' => 'Theorical Remaining Effort',
			'optimistic_remaining_effort' => 'Estimated Remaining Effort',
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
		
		if(Yii::app()->session['openIssues'] == true){
			$criteria->with['status'] = array('together' => true);
			$criteria->compare('status.closed_alias', 0);
		}
		
		if(Yii::app()->session['criticalIssues'] == true){
			$this->delayedIssues($criteria);
			$this->overrunIssues($criteria);
		}elseif($this->overdue){
			$this->delayedIssues($criteria);
		}

		$criteria->compare('t.id',$this->id);
		$criteria->compare('t.label',$this->label,true);
		$criteria->compare('project_id',$this->project_id);
		$criteria->compare('user_id',$this->user_id);
		$criteria->compare('owner_id',$this->owner_id);
		$criteria->compare('assignee_id',$this->assignee_id);
		$criteria->compare('status_id',$this->status_id);
		$criteria->compare('type_id',$this->type_id);
		$criteria->compare('version_id',$this->version_id);
		$criteria->compare('milestone_id',$this->milestone_id);
		$criteria->compare('priority',$this->priority);
		$criteria->compare('estimated_time',$this->estimated_time,true);
		$criteria->compare('private',$this->private);
		$criteria->compare('description',$this->description, true);
		$criteria->compare('completion',$this->completion);
		$criteria->compare('overrun',$this->overrun);
		$criteria->compare('logged_effort',$this->logged_effort);
		$criteria->compare('theorical_remaining_effort',$this->theorical_remaining_effort);
		$criteria->compare('optimistic_remaining_effort',$this->optimistic_remaining_effort);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
	
	public function behaviors()
	{
		return array(
				'IssueBehavior'=>array(
				'class'=>'application.components.behaviors.IssueBehavior'
				),
		);
	}
	
	public function getComment()
	{
		return $this->comment;
	}
	
	public function setComment($value)
	{
		$this->comment = $comment;
	}


	/*protected function beforeSave()
	{
		$this->user_id = Yii::app()->user->id;
		if(!isset($this->id))
			$this->creation_date = date('Y-m-d H:i:s');
			
		if(is_null($this->completion))
			$this->completion = 0;
		
		return parent::beforeSave();
	}*/
}