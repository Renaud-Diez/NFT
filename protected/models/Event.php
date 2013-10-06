<?php

/**
 * This is the model class for table "event".
 *
 * The followings are the available columns in table 'event':
 * @property integer $id
 * @property integer $user_id
 * @property integer $project_id
 * @property string $creation_date
 * @property string $ref_object
 * @property integer $ref_id
 * @property string $description
 *
 * The followings are the available model relations:
 * @property Project $project
 * @property User $user
 */
class Event extends CActiveRecord
{
	CONST CRITICITY_LOW = 0;
	CONST CRITICITY_NORMAL = 1;
	CONST CRITICITY_MEDIUM = 2;
	CONST CRITICITY_HIGH = 3;
	CONST CRITICITY_VERYHIGH = 4;
	CONST CRITICITY_CRITICAL = 5;
	
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Event the static model class
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
		return 'event';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('user_id, creation_date, ref_object, ref_id', 'required'),
			array('user_id, project_id, ref_id, criticity', 'numerical', 'integerOnly'=>true),
			array('ref_object', 'length', 'max'=>45),
			array('description', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, user_id, project_id, creation_date, ref_object, ref_id, description, criticity', 'safe', 'on'=>'search'),
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
			'user' => array(self::BELONGS_TO, 'User', 'user_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'user_id' => 'User',
			'project_id' => 'Project',
			'creation_date' => 'Creation Date',
			'ref_object' => 'Ref Object',
			'ref_id' => 'Ref',
			'description' => 'Description',
			'criticity' => 'Criticity',
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
		$criteria->compare('project_id',$this->project_id);
		$criteria->compare('creation_date',$this->creation_date,true);
		$criteria->compare('ref_object',$this->ref_object,true);
		$criteria->compare('ref_id',$this->ref_id);
		$criteria->compare('description',$this->description,true);
		$criteria->compare('criticity',$this->criticity,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}