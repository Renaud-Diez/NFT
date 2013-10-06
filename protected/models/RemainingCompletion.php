<?php

/**
 * This is the model class for table "remaining_completion".
 *
 * The followings are the available columns in table 'remaining_completion':
 * @property integer $id
 * @property integer $project_id
 * @property integer $version_id
 * @property integer $milestone_id
 * @property string $completion
 * @property string $estimated_time
 * @property string $time_spent
 */
class RemainingCompletion extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return RemainingCompletion the static model class
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
		return 'remaining_completion';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('project_id', 'required'),
			array('id, project_id, version_id, milestone_id', 'numerical', 'integerOnly'=>true),
			array('completion', 'length', 'max'=>11),
			array('estimated_time', 'length', 'max'=>2),
			array('time_spent', 'length', 'max'=>24),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, project_id, version_id, milestone_id, completion, estimated_time, time_spent', 'safe', 'on'=>'search'),
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
			'issue' => array(self::BELONGS_TO, 'Issue', 'id'),
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
			'version_id' => 'Version',
			'milestone_id' => 'Milestone',
			'completion' => 'Completion',
			'estimated_time' => 'Estimated Time',
			'time_spent' => 'Time Spent',
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
		$criteria->compare('version_id',$this->version_id);
		$criteria->compare('milestone_id',$this->milestone_id);
		$criteria->compare('completion',$this->completion,true);
		$criteria->compare('estimated_time',$this->estimated_time,true);
		$criteria->compare('time_spent',$this->time_spent,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}