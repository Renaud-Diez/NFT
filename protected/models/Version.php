<?php

/**
 * This is the model class for table "version".
 *
 * The followings are the available columns in table 'version':
 * @property integer $id
 * @property string $label
 * @property integer $project_id
 * @property string $creation_date
 * @property string $due_date
 * @property string $status
 *
 * The followings are the available model relations:
 * @property Milestone[] $milestones
 * @property Project $project
 */
class Version extends CActiveRecord
{
	const STATUS_SCHEDULED=0; 
	const STATUS_OPEN=1; 
	const STATUS_ACCEPTANCE=2;
	const STATUS_TORELEASE=3;
	const STATUS_RELEASED=4;
	const STATUS_CLOSED=5;
	
	CONST RELATION_SOURCE = 0;
	CONST RELATION_TARGET = 1;
	
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Version the static model class
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
		return 'version';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('project_id, label, due_date', 'required'),
			array('project_id', 'numerical', 'integerOnly'=>true),
			array('label', 'length', 'max'=>45),
			array('status', 'length', 'max'=>24),
			array('creation_date, start_date, due_date', 'safe'),
			array('label', 'unique', 'criteria'=>array(
            'condition'=>'`project_id`=:project_id',
            'params'=>array(
                ':project_id'=>$this->project_id
            	),
            )),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, label, project_id, creation_date, start_date, due_date, status', 'safe', 'on'=>'search'),
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
			'milestones' => array(self::HAS_MANY, 'Milestone', 'version_id'),
			'project' => array(self::BELONGS_TO, 'Project', 'project_id'),
			'source' => array(self::HAS_MANY, 'VersionRelation', 'source_id'),
			'target' => array(self::HAS_MANY, 'VersionRelation', 'target_id'),
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
			'creation_date' => 'Creation Date',
			'start_date' => 'Start Date',
			'due_date' => 'Due Date',
			'status' => 'Status',
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
		$criteria->compare('creation_date',$this->creation_date,true);
		$criteria->compare('start_date',$this->start_date,true);
		$criteria->compare('due_date',$this->due_date,true);
		$criteria->compare('status',$this->status,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
	
	public function behaviors()
	{
		return array(
				'VersionBehavior'=>array(
				'class'=>'application.components.behaviors.VersionBehavior'
				),
		);
	}
}