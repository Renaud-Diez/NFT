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
 * @property integer $milestone_id
 * @property integer $priority
 * @property string $estimated_time
 * @property integer $private
 *
 * The followings are the available model relations:
 * @property Project $project
 * @property User $assignee
 * @property User $user
 * @property IssueStatus $status
 * @property Milestone $milestone
 * @property IssueType $type
 * @property IssueDocument[] $issueDocuments
 * @property IssueLogs[] $issueLogs
 * @property IssueRelation[] $issueRelations
 * @property IssueRelation[] $issueRelations1
 * @property Project[] $projects
 * @property Timetracker[] $timetrackers
 */
class Issue extends CActiveRecord
{
	CONST PRIORITY_LOW = 0;
	CONST PRIORITY_NORMAL = 1;
	CONST PRIORITY_HIGH = 2;
	CONST PRIORITY_URGENT = 3;
	CONST PRIORITY_IMMEDIATE = 4;
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
			array('project_id, user_id, assignee_id, status_id, type_id, milestone_id, priority, private', 'numerical', 'integerOnly'=>true),
			array('label', 'length', 'max'=>150),
			array('estimated_time', 'length', 'max'=>2),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, label, project_id, user_id, assignee_id, status_id, type_id, milestone_id, priority, estimated_time, private', 'safe', 'on'=>'search'),
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
			'project' => array(self::BELONGS_TO, 'Project', 'project_id'),
			'assignee' => array(self::BELONGS_TO, 'User', 'assignee_id'),
			'user' => array(self::BELONGS_TO, 'User', 'user_id'),
			'status' => array(self::BELONGS_TO, 'IssueStatus', 'status_id'),
			'milestone' => array(self::BELONGS_TO, 'Milestone', 'milestone_id'),
			'type' => array(self::BELONGS_TO, 'IssueType', 'type_id'),
			'issueDocuments' => array(self::HAS_MANY, 'IssueDocument', 'issue_id'),
			'issueLogs' => array(self::HAS_MANY, 'IssueLogs', 'issue_id'),
			'issueRelations' => array(self::HAS_MANY, 'IssueRelation', 'issue_id'),
			'issueRelations1' => array(self::HAS_MANY, 'IssueRelation', 'related_id'),
			'projects' => array(self::MANY_MANY, 'Project', 'project_issues(issue_id, project_id)'),
			'timetrackers' => array(self::HAS_MANY, 'Timetracker', 'issue_id'),
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
			'status_id' => 'Status',
			'type_id' => 'Type',
			'milestone_id' => 'Milestone',
			'priority' => 'Priority',
			'estimated_time' => 'Estimated Time',
			'private' => 'Private',
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
		$criteria->compare('label',$this->label,true);
		$criteria->compare('project_id',$this->project_id);
		$criteria->compare('user_id',$this->user_id);
		$criteria->compare('assignee_id',$this->assignee_id);
		$criteria->compare('status_id',$this->status_id);
		$criteria->compare('type_id',$this->type_id);
		$criteria->compare('milestone_id',$this->milestone_id);
		$criteria->compare('priority',$this->priority);
		$criteria->compare('estimated_time',$this->estimated_time,true);
		$criteria->compare('private',$this->private);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
	
	public function getPriorities($value)
	{
		$return = array( 
		       	self::PRIORITY_LOW 		=> 'Low', 
		        self::PRIORITY_NORMAL		=> 'Normal', 
		        self::PRIORITY_HIGH		=> 'High',
		        self::PRIORITY_URGENT		=> 'Urgent',
		        self::PRIORITY_IMMEDIATE	=> 'Immediate',
				);
				
		if(!is_null($value))
			$return = $return[$value];
	    
	    return $return;
	}

	protected function afterSave()
	{
		$model = new ProjectIssues;
		$model->project_id = $this->project_id;
		$model->issue_id = $this->id;
		$model->save();
		
		//$model = new ProjectLogs;
		
	}
}