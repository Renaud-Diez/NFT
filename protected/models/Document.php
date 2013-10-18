<?php

/**
 * This is the model class for table "document".
 *
 * The followings are the available columns in table 'document':
 * @property integer $id
 * @property integer $user_id
 * @property string $label
 * @property string $path
 * @property string $comment
 * @property string $creation_date
 *
 * The followings are the available model relations:
 * @property User $user
 * @property DocumentFeedback[] $documentFeedbacks
 * @property DocumentLogs[] $documentLogs
 * @property IssueDocument[] $issueDocuments
 * @property ProjectDocument[] $projectDocuments
 */
class Document extends CActiveRecord
{
	public $file = null;
	public $issue_id = null;
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Document the static model class
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
		return 'document';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('file', 'file', 'types'=>'jpg, gif, png, doc, docx, xls, xlsx, pdf, txt'),
			array('user_id', 'required'),
			array('user_id', 'numerical', 'integerOnly'=>true),
			array('label, path', 'length', 'max'=>45),
			array('comment, creation_date', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, user_id, label, path, comment, creation_date', 'safe', 'on'=>'search'),
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
			'user' => array(self::BELONGS_TO, 'User', 'user_id'),
			'documentFeedbacks' => array(self::HAS_MANY, 'DocumentFeedback', 'document_id'),
			'documentLogs' => array(self::HAS_MANY, 'DocumentLogs', 'document_id'),
			'issueDocuments' => array(self::HAS_MANY, 'IssueDocument', 'document_id'),
			'projectDocuments' => array(self::HAS_MANY, 'ProjectDocument', 'document_id'),
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
			'label' => 'Label',
			'path' => 'Path',
			'comment' => 'Comment',
			'creation_date' => 'Creation Date',
			'file' => 'File',
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
		$criteria->compare('label',$this->label,true);
		$criteria->compare('path',$this->path,true);
		$criteria->compare('comment',$this->comment,true);
		$criteria->compare('creation_date',$this->creation_date,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
	
	public function behaviors()
	{
		return array(
				'DocumentBehavior'=>array(
				'class'=>'application.components.behaviors.DocumentBehavior'
				),
		);
	}
}