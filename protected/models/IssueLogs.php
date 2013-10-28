<?php

/**
 * This is the model class for table "issue_logs".
 *
 * The followings are the available columns in table 'issue_logs':
 * @property integer $id
 * @property integer $issue_id
 * @property integer $user_id
 * @property integer $status_id
 * @property string $creation_date
 * @property string $label
 * @property string $comment
 * @property integer $type_id
 * @property integer $version_id
 * @property integer $milestone_id
 * @property integer $assignee_id
 * @property integer $priority
 * @property string $estimated_time
 * @property integer $private
 * @property string $description
 * @property string $due_date
 * @property integer $completion
 *
 * The followings are the available model relations:
 * @property Milestone $milestone
 * @property User $assignee
 * @property User $user
 * @property Issue $issue
 * @property IssueStatus $status
 * @property IssueType $type
 * @property Version $version
 */
class IssueLogs extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return IssueLogs the static model class
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
		return 'issue_logs';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('issue_id, user_id, creation_date', 'required'),
			array('issue_id, user_id, status_id, type_id, version_id, milestone_id, assignee_id, priority, private, completion', 'numerical', 'integerOnly'=>true),
			array('label', 'length', 'max'=>150),
			array('estimated_time', 'length', 'max'=>6),
			array('overrun, logged_effort, theorical_remaining_effort, pessimistic_remaining_effort, optimistic_remaining_effort', 'numerical'),
			array('comment, description, due_date', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, issue_id, user_id, status_id, creation_date, label, comment, type_id, version_id, milestone_id, assignee_id, priority, estimated_time, private, description, due_date, completion', 'safe', 'on'=>'search'),
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
			'milestone' => array(self::BELONGS_TO, 'Milestone', 'milestone_id'),
			'assignee' => array(self::BELONGS_TO, 'User', 'assignee_id'),
			'user' => array(self::BELONGS_TO, 'User', 'user_id'),
			'issue' => array(self::BELONGS_TO, 'Issue', 'issue_id'),
			'status' => array(self::BELONGS_TO, 'IssueStatus', 'status_id'),
			'type' => array(self::BELONGS_TO, 'IssueType', 'type_id'),
			'version' => array(self::BELONGS_TO, 'Version', 'version_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'issue_id' => 'Issue',
			'user_id' => 'User',
			'status_id' => 'Status',
			'creation_date' => 'Creation Date',
			'label' => 'Label',
			'comment' => 'Comment',
			'type_id' => 'Type',
			'version_id' => 'Version',
			'milestone_id' => 'Milestone',
			'assignee_id' => 'Assignee',
			'priority' => 'Priority',
			'estimated_time' => 'Estimated Time',
			'private' => 'Private',
			'description' => 'Description',
			'due_date' => 'Due Date',
			'completion' => 'Completion',
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
		$criteria->compare('issue_id',$this->issue_id);
		$criteria->compare('user_id',$this->user_id);
		$criteria->compare('status_id',$this->status_id);
		$criteria->compare('creation_date',$this->creation_date,true);
		$criteria->compare('label',$this->label,true);
		$criteria->compare('comment',$this->comment,true);
		$criteria->compare('type_id',$this->type_id);
		$criteria->compare('version_id',$this->version_id);
		$criteria->compare('milestone_id',$this->milestone_id);
		$criteria->compare('assignee_id',$this->assignee_id);
		$criteria->compare('priority',$this->priority);
		$criteria->compare('estimated_time',$this->estimated_time,true);
		$criteria->compare('private',$this->private);
		$criteria->compare('description',$this->description,true);
		$criteria->compare('due_date',$this->due_date,true);
		$criteria->compare('completion',$this->completion);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}