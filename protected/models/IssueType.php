<?php

/**
 * This is the model class for table "issue_type".
 *
 * The followings are the available columns in table 'issue_type':
 * @property integer $id
 * @property string $label
 *
 * The followings are the available model relations:
 * @property Issue[] $issues
 * @property Topic[] $topics
 */
class IssueType extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return IssueType the static model class
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
		return 'issue_type';
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
			array('label', 'length', 'max'=>45),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, label', 'safe', 'on'=>'search'),
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
			'issues' => array(self::HAS_MANY, 'Issue', 'type_id'),
			'topics' => array(self::MANY_MANY, 'Topic', 'issue_type_topic(type_id, topic_id)'),
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

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
	
	
	public function behaviors()
	{
		return array(
				'IssueTypeBehavior'=>array(
				'class'=>'application.components.behaviors.IssueTypeBehavior'
				),
		);
	}
	
	public function topicMatrix()
	{
		/*$value = Yii::app()->db->createCommand()
    	->select('avg(completion) as done, count(i.id) as rows')
    	->from('issue i')
    	->join('project_issues pi', 'pi.issue_id = i.id')
    	->where('pi.project_id = :projectId', array(':projectId' => $this->id))
    	->andWhere('i.status_id '.$operator.' :statusId', array(':statusId' => $statusId))
    	->queryRow();*/
		$arrTopics = Yii::app()->db->createCommand()
    	->select('id,label')
    	->from('topic t')
    	->queryAll();
    	
    	$arrTypes = Yii::app()->db->createCommand()
    	->select('id,label')
    	->from('isue_type i')
    	->queryAll();
    	
    	
	}
}