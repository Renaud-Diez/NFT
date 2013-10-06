<?php

/**
 * This is the model class for table "issue_transition".
 *
 * The followings are the available columns in table 'issue_transition':
 * @property integer $id
 * @property integer $issue_id
 * @property string $action
 * @property integer $project_id
 * @property integer $version_id
 * @property integer $milestone_id
 * @property string $comment
 *
 * The followings are the available model relations:
 * @property Issue $issue
 */
class IssueTransition extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return IssueTransition the static model class
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
		return 'issue_transition';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('issue_id,label, action', 'required'),
			array('issue_id, project_id, version_id, milestone_id', 'numerical', 'integerOnly'=>true),
			array('action', 'length', 'max'=>45),
			array('comment,label', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, issue_id, action, project_id, version_id, milestone_id, comment', 'safe', 'on'=>'search'),
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
			'issue' => array(self::BELONGS_TO, 'Issue', 'issue_id'),
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
			'label' => 'Label',
			'action' => 'Action',
			'project_id' => 'Project',
			'version_id' => 'Version',
			'milestone_id' => 'Milestone',
			'comment' => 'Comment',
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
		$criteria->compare('label',$this->label,true);
		$criteria->compare('action',$this->action,true);
		$criteria->compare('project_id',$this->project_id);
		$criteria->compare('version_id',$this->version_id);
		$criteria->compare('milestone_id',$this->milestone_id);
		$criteria->compare('comment',$this->comment,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
	
	
	public function behaviors()
	{
		return array(
				'IssueTransitionBehavior'=>array(
				'class'=>'application.components.behaviors.IssueTransitionBehavior'
				),
		);
	}
}