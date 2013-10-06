<?php

/**
 * This is the model class for table "project_status".
 *
 * The followings are the available columns in table 'project_status':
 * @property integer $id
 * @property string $label
 * @property integer $rank
 * @property integer $closed_alias
 *
 * The followings are the available model relations:
 * @property Project[] $projects
 * @property Topic[] $topics
 */
class ProjectStatus extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return ProjectStatus the static model class
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
		return 'project_status';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('label', 'required'),
			array('rank, closed_alias', 'numerical', 'integerOnly'=>true),
			array('label', 'length', 'max'=>255),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, label, rank, closed_alias', 'safe', 'on'=>'search'),
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
			'projects' => array(self::HAS_MANY, 'Project', 'status_id'),
			'topics' => array(self::MANY_MANY, 'Topic', 'project_status_topic(status_id, topic_id)'),
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
			'rank' => 'Rank',
			'closed_alias' => 'Closed Alias',
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
		$criteria->compare('rank',$this->rank);
		$criteria->compare('closed_alias',$this->closed_alias);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
	
	public function behaviors()
	{
		return array(
				'ProjectStatusBehavior'=>array(
				'class'=>'application.components.behaviors.ProjectStatusBehavior'
				),
		);
	}
}