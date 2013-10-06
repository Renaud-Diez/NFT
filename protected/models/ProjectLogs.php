<?php

/**
 * This is the model class for table "project_logs".
 *
 * The followings are the available columns in table 'project_logs':
 * @property integer $id
 * @property integer $project_id
 * @property integer $user_id
 * @property string $creation_date
 * @property string $description
 * @property integer $topic_id
 * @property integer $parent_id
 * @property string $label
 * @property integer $owner_id
 *
 * The followings are the available model relations:
 * @property Topic $topic
 * @property User $owner
 * @property Project $project
 * @property User $user
 */
class ProjectLogs extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return ProjectLogs the static model class
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
		return 'project_logs';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('project_id, user_id, label, owner_id', 'required'),
			array('project_id, user_id, topic_id, parent_id, owner_id, budget', 'numerical', 'integerOnly'=>true),
			array('label', 'length', 'max'=>45),
			array('creation_date, description', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, project_id, user_id, creation_date, description, topic_id, parent_id, label, owner_id', 'safe', 'on'=>'search'),
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
			'topic' => array(self::BELONGS_TO, 'Topic', 'topic_id'),
			'owner' => array(self::BELONGS_TO, 'User', 'owner_id'),
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
			'project_id' => 'Project',
			'user_id' => 'User',
			'creation_date' => 'Creation Date',
			'description' => 'Description',
			'topic_id' => 'Topic',
			'parent_id' => 'Parent',
			'label' => 'Label',
			'owner_id' => 'Owner',
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
		$criteria->compare('user_id',$this->user_id);
		$criteria->compare('creation_date',$this->creation_date,true);
		$criteria->compare('description',$this->description,true);
		$criteria->compare('topic_id',$this->topic_id);
		$criteria->compare('parent_id',$this->parent_id);
		$criteria->compare('label',$this->label,true);
		$criteria->compare('owner_id',$this->owner_id);
		$criteria->compare('budget',$this->budget);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}