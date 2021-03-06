<?php

/**
 * This is the model class for table "project_user".
 *
 * The followings are the available columns in table 'project_user':
 * @property integer $project_id
 * @property integer $user_id
 * @property string $role
 *
 * The followings are the available model relations:
 * @property Project $project
 * @property User $user
 */
class IssueUser extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return ProjectUser the static model class
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
		return 'issue_user';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('issue_id, user_id', 'required'),
			array('issue_id, user_id', 'numerical', 'integerOnly'=>true),
			array('role', 'length', 'max'=>64),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('issue_id, user_id, role', 'safe', 'on'=>'search'),
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
			//'assignement' => array(self::BELONGS_TO, 'AuthItem', 'role'),
			'user' => array(self::BELONGS_TO, 'User', 'user_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'issue_id' => 'Issue',
			'user_id' => 'User',
			'role' => 'Role',
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

		$criteria->compare('issue_id',$this->issue_id);
		$criteria->compare('user_id',$this->user_id);
		$criteria->compare('role',$this->role);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
	
	public function behaviors()
	{
		return array(
				'IssueUserBehavior'=>array(
				'class'=>'application.components.behaviors.IssueUserBehavior'
				),
		);
	}
}