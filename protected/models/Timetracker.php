<?php

/**
 * This is the model class for table "timetracker".
 *
 * The followings are the available columns in table 'timetracker':
 * @property integer $id
 * @property integer $user_id
 * @property integer $issue_id
 * @property string $time_spent
 * @property integer $billable
 * @property string $comment
 * @property integer $activity_id
 *
 * The followings are the available model relations:
 * @property TimeActivity $activity
 * @property Issue $issue
 * @property User $user
 */
class Timetracker extends CActiveRecord
{
	public $remaining_time;
	
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Timetracker the static model class
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
		return 'timetracker';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('user_id, issue_id, time_spent, billable, activity_id', 'required'),
			array('remaining_time', 'validateRemainingTime'),
			array('user_id, issue_id, billable, activity_id', 'numerical', 'integerOnly'=>true),
			array('time_spent', 'length', 'max'=>5),
			array('comment', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, user_id, issue_id, time_spent, billable, comment, activity_id, log_date', 'safe', 'on'=>'search'),
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
			'activity' => array(self::BELONGS_TO, 'TimeActivity', 'activity_id'),
			'issue' => array(self::BELONGS_TO, 'Issue', 'issue_id'),
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
			'issue_id' => 'Issue',
			'time_spent' => 'Time Spent',
			'billable' => 'Billable',
			'comment' => 'Comment',
			'activity_id' => 'Activity',
			'log_date' => 'Work Date',
			'remaining_time' => 'Remaining Time',
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
		$criteria->compare('issue_id',$this->issue_id);
		$criteria->compare('time_spent',$this->time_spent,true);
		$criteria->compare('billable',$this->billable);
		$criteria->compare('comment',$this->comment,true);
		$criteria->compare('activity_id',$this->activity_id);
		$criteria->compare('log_date',$this->log_date);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
	
	public function validateRemainingTime($attribute, $params)
	{
		$model = Issue::model()->findByPk($this->issue_id);
		if($model->assignee_id == Yii::app()->user->id && (!is_numeric($this->$attribute) || $this->$attribute < 0))
			$this->addError($attribute, 'Remaining Time cannot be blank!');
	}

	
	public function afterSave()
	{
		$model = Issue::model()->findByPk($this->issue_id);
		$model->registerParticipant(array($this->user_id));
		
		$spent_time = $model->getLoggedEffort();
		if(!is_numeric($spent_time))
			$spent_time = 0;
		
		if(is_numeric($this->remaining_time) && $this->remaining_time >= 0){
			$completion = $model->completion;
			
			$model->completion = round((1-($this->remaining_time/($spent_time+$this->remaining_time)))*100, 0);
		}
		
		if((is_null($model->estimated_time) || $model->estimated_time == 0) && $model->completion > 0){
			$model->estimated_time = round($spent_time*(100/$model->completion), 1);
		}
		
		$model->save();
	}
}