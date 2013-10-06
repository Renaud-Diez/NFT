<?php

/**
 * This is the model class for table "milestone".
 *
 * The followings are the available columns in table 'milestone':
 * @property integer $id
 * @property string $label
 * @property integer $project_id
 * @property integer $version_id
 * @property string $creation_date
 * @property string $due_date
 * @property string $status
 *
 * The followings are the available model relations:
 * @property Issue[] $issues
 * @property Project $project
 * @property Version $version
 */
class Milestone extends CActiveRecord
{
	const STATUS_SCHEDULED=0; 
	const STATUS_OPEN=1; 
	const STATUS_CLOSED=2;
	
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Milestone the static model class
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
		return 'milestone';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('label, project_id, version_id, creation_date, due_date', 'required'),
			array('project_id, version_id', 'numerical', 'integerOnly'=>true),
			array('label', 'length', 'max'=>45),
			array('status', 'length', 'max'=>24),
			array('creation_date, start_date, due_date', 'safe'),
			array('label', 'unique', 'criteria'=>array(
            				'condition'=>'`version_id`=:version_id',
            				'params'=>array(':version_id'=>$this->version_id))),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, label, project_id, version_id, creation_date, start_date, due_date, status', 'safe', 'on'=>'search'),
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
			'issues' => array(self::HAS_MANY, 'Issue', 'milestone_id'),
			'project' => array(self::BELONGS_TO, 'Project', 'project_id'),
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
			'label' => 'Label',
			'project_id' => 'Project',
			'version_id' => 'Version',
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
		$criteria->compare('version_id',$this->version_id);
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
				'MilestoneBehavior'=>array(
				'class'=>'application.components.behaviors.MilestoneBehavior'
				),
		);
	}
}