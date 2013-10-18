<?php

/**
 * This is the model class for table "project_relation".
 *
 * The followings are the available columns in table 'project_relation':
 * @property integer $id
 * @property integer $project_id
 * @property integer $related_id
 * @property string $relation
 *
 * The followings are the available model relations:
 * @property Project $project
 * @property Project $related
 */
class ProjectRelation extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return ProjectRelation the static model class
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
		return 'project_relation';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('project_id, related_id, relation', 'required'),
			array('project_id, related_id, relation', 'numerical', 'integerOnly'=>true),
			array('relation', 'length', 'max'=>16),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, project_id, related_id, relation', 'safe', 'on'=>'search'),
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
			'related' => array(self::BELONGS_TO, 'Project', 'related_id'),
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
		$criteria->compare('project_id',$this->project_id);
		$criteria->compare('related_id',$this->related_id);
		$criteria->compare('relation',$this->relation);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
	
	public function getOppositeRelation()
	{
		if($this->relation == 0)
			return 0;
		elseif($this->relation == 1)
			return 2;
		elseif($this->relation == 2)
			return 1;
		elseif($this->relation == 3)
			return 4;
		elseif($this->relation == 4)
			return 3;
		elseif($this->relation == 5)
			return 6;
		elseif($this->relation == 6)
			return 5;
		elseif($this->relation == 7)
			return 8;
		elseif($this->relation == 8)
			return 7;
		else
			return false;
	}
	
	public function afterSave()
	{
		if($this->relation == Project::RELATED_CHILD){
			$this->setParentId($this->related_id, $this->project_id);
		}
		
		if($this->relation == Project::RELATED_PARENT){
			$this->setParentId($this->project_id, $this->related_id);
		}
		
		return parent::afterSave();
	}
	
	protected function setParentId($projectId, $parentId)
	{
		$model = Project::model()->findByPk($projectId);
		$model->parent_id = $parentId;
		$model->save();
	}
	
	protected function resetParentId()
	{
		$model = Project::model()->findByPk($this->related_id);
		$model->parent_id = null;
		$model->save();
	}
	
	public function afterDelete()
	{
		parent::afterDelete();
		
		if($this->relation == Project::RELATED_CHILD){
			$this->resetParentId();
		}
		
		$criteria = new CDbCriteria;
		$criteria->compare('project_id', $this->related_id);
		$criteria->compare('related_id', $this->project_id);
		
		$model=ProjectRelation::model()->find($criteria);
		
		if(!is_null($model))
			$model->delete();
	}
}