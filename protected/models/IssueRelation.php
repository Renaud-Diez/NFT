<?php

/**
 * This is the model class for table "issue_relation".
 *
 * The followings are the available columns in table 'issue_relation':
 * @property integer $id
 * @property integer $issue_id
 * @property integer $related_id
 * @property string $relation
 *
 * The followings are the available model relations:
 * @property Issue $issue
 * @property Issue $related
 */
class IssueRelation extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return IssueRelation the static model class
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
		return 'issue_relation';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('issue_id, related_id, relation', 'required'),
			array('issue_id, related_id, relation', 'numerical', 'integerOnly'=>true),
			array('relation', 'length', 'max'=>16),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, issue_id, related_id, relation', 'safe', 'on'=>'search'),
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
			'issue' => array(self::BELONGS_TO, 'Issue', 'issue_id'),
			'related' => array(self::BELONGS_TO, 'Issue', 'related_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'issue_id' => 'Issue',
			'related_id' => 'Related',
			'relation' => 'Relation',
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
		$criteria->compare('issue_id',$this->issue_id);
		$criteria->compare('related_id',$this->related_id);
		$criteria->compare('relation',$this->relation);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
	
	public function behaviors()
	{
		return array(
				'IssueRelationBehavior'=>array(
				'class'=>'application.components.behaviors.IssueRelationBehavior'
				),
		);
	}
}