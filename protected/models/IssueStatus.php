<?php

/**
 * This is the model class for table "issue_status".
 *
 * The followings are the available columns in table 'issue_status':
 * @property integer $id
 * @property string $label
 * @property integer $rank
 * @property integer $closed_alias
 *
 * The followings are the available model relations:
 * @property Issue[] $issues
 * @property IssueLogs[] $issueLogs
 * @property IssueType[] $issueTypes
 */
class IssueStatus extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return IssueStatus the static model class
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
		return 'issue_status';
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
			array('rank, closed_alias, alias', 'numerical', 'integerOnly'=>true),
			array('label', 'length', 'max'=>45),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, label, rank, closed_alias, alias', 'safe', 'on'=>'search'),
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
			'issues' => array(self::HAS_MANY, 'Issue', 'status_id'),
			'issueLogs' => array(self::HAS_MANY, 'IssueLogs', 'status_id'),
			'issueTypes' => array(self::MANY_MANY, 'IssueType', 'issue_type_status(status_id, type_id)'),
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
			'alias' => 'Alias',
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
		$criteria->compare('alias',$this->alias);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
	
	public function behaviors()
	{
		return array(
				'IssueStatusBehavior'=>array(
				'class'=>'application.components.behaviors.IssueStatusBehavior'
				),
		);
	}
}