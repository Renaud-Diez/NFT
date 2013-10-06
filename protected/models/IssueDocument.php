<?php

/**
 * This is the model class for table "issue_document".
 *
 * The followings are the available columns in table 'issue_document':
 * @property integer $id
 * @property integer $issue_id
 * @property integer $document_id
 *
 * The followings are the available model relations:
 * @property Document $document
 * @property Issue $issue
 */
class IssueDocument extends CActiveRecord
{
	
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return IssueDocument the static model class
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
		return 'issue_document';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('issue_id, document_id', 'required'),
			array('issue_id, document_id', 'numerical', 'integerOnly'=>true),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, issue_id, document_id', 'safe', 'on'=>'search'),
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
			'document' => array(self::BELONGS_TO, 'Document', 'document_id'),
			'issue' => array(self::BELONGS_TO, 'Issue', 'issue_id'),
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
			'document_id' => 'Document',
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
		$criteria->compare('document_id',$this->document_id);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}