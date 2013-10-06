<?php

/**
 * This is the model class for table "project_completion".
 *
 * The followings are the available columns in table 'project_completion':
 * @property integer $id
 * @property integer $project_id
 * @property string $creation_date
 * @property string $estimated_effort
 * @property string $spent_time
 * @property string $theorical_effort
 * @property string $overrun
 * @property string $theorical_remaining_effort
 * @property string $estimated_remaining_effort
 *
 * The followings are the available model relations:
 * @property Project $project
 */
class ProjectCompletion extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return ProjectCompletion the static model class
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
		return 'project_completion';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('project_id, creation_date, estimated_effort, spent_time, theorical_effort, overrun, theorical_remaining_effort, estimated_remaining_effort', 'required'),
			array('project_id, budget', 'numerical', 'integerOnly'=>true),
			array('estimated_effort, spent_time, theorical_effort, overrun, theorical_remaining_effort, estimated_remaining_effort', 'length', 'max'=>10),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, project_id, creation_date, estimated_effort, spent_time, theorical_effort, overrun, theorical_remaining_effort, estimated_remaining_effort, budget', 'safe', 'on'=>'search'),
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
			'creation_date' => 'Creation Date',
			'estimated_effort' => 'Estimated Effort',
			'spent_time' => 'Spent Time',
			'theorical_effort' => 'Theorical Effort',
			'overrun' => 'Overrun',
			'theorical_remaining_effort' => 'Theorical Remaining Effort',
			'estimated_remaining_effort' => 'Estimated Remaining Effort',
			'budget' => 'Budget',
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
		$criteria->compare('creation_date',$this->creation_date,true);
		$criteria->compare('estimated_effort',$this->estimated_effort,true);
		$criteria->compare('spent_time',$this->spent_time,true);
		$criteria->compare('theorical_effort',$this->theorical_effort,true);
		$criteria->compare('overrun',$this->overrun,true);
		$criteria->compare('theorical_remaining_effort',$this->theorical_remaining_effort,true);
		$criteria->compare('estimated_remaining_effort',$this->estimated_remaining_effort,true);
		$criteria->compare('budget',$this->budget,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}