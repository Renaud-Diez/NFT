<?php

/**
 * This is the model class for table "project_role".
 *
 * The followings are the available columns in table 'project_role':
 * @property integer $id
 * @property integer $project_id
 * @property string $role
 * @property string $creation_date
 * @property integer $minimum
 * @property integer $maximum
 * @property string $entry_date
 *
 * The followings are the available model relations:
 * @property Project $project
 */
class ProjectRole extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return ProjectRole the static model class
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
		return 'project_role';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('role, creation_date', 'required'),
			array('project_id, minimum, maximum', 'numerical', 'integerOnly'=>true),
			array('role', 'length', 'max'=>64),
			array('entry_date', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, project_id, role, creation_date, minimum, maximum, entry_date', 'safe', 'on'=>'search'),
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
			'role' => array(self::BELONGS_TO, 'AuthItem', 'role'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'project_id' => 'Project',
			'role' => 'Role',
			'creation_date' => 'Creation Date',
			'minimum' => 'Minimum number of member',
			'entry_date' => 'Entry Date',
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
		$criteria->compare('project_id',$this->project_id);
		$criteria->compare('role',$this->role,true);
		$criteria->compare('creation_date',$this->creation_date,true);
		$criteria->compare('minimum',$this->minimum);
		$criteria->compare('entry_date',$this->entry_date,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
	
	public function behaviors()
	{
		return array(
				'ProjectRoleBehavior'=>array(
						'class'=>'application.components.behaviors.ProjectRoleBehavior'
				),
		);
	}
}