<?php

/**
 * This is the model class for table "project".
 *
 * The followings are the available columns in table 'project':
 * @property integer $id
 * @property integer $owner_id
 * @property string $code
 * @property string $label
 * @property string $description
 * @property integer $topic_id
 *
 * The followings are the available model relations:
 * @property Milestone[] $milestones
 * @property User $owner
 * @property Topic $topic
 * @property ProjectDocument[] $projectDocuments
 * @property Issue[] $issues
 * @property ProjectLogs[] $projectLogs
 * @property ProjectRelation[] $projectRelations
 * @property ProjectRelation[] $projectRelations1
 * @property ProjectUser[] $projectUsers
 * @property Version[] $versions
 */
class Project extends CActiveRecord
{
	public $oldRecord;
	
	const RELATED_TO			=0; 
	const RELATED_DUPLICATES	=1; 
	const RELATED_DUPLICATEBY	=2;
	const RELATED_BLOCKS		=3; 
	const RELATED_BLOCKEDBY		=4; 
	const RELATED_PRECEDES		=5;
	const RELATED_FOLLOWS		=6;
	
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Project the static model class
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
		return 'project';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('code, topic_id', 'required'),
			array('user_id, topic_id', 'numerical', 'integerOnly'=>true),
			array('code, label', 'length', 'max'=>45),
			array('description', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, user_id, code, label, description, topic_id', 'safe', 'on'=>'search'),
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
			'milestones' => array(self::HAS_MANY, 'Milestone', 'project_id'),
			'owner' => array(self::BELONGS_TO, 'User', 'user_id'),
			'topic' => array(self::BELONGS_TO, 'Topic', 'topic_id'),
			'projectDocuments' => array(self::HAS_MANY, 'ProjectDocument', 'project_id'),
			'issues' => array(self::MANY_MANY, 'Issue', 'project_issues(project_id, issue_id)'),
			'projectLogs' => array(self::HAS_MANY, 'ProjectLogs', 'project_id'),
			'projectRelations' => array(self::HAS_MANY, 'ProjectRelation', 'project_id'),
			'projectRelations1' => array(self::HAS_MANY, 'ProjectRelation', 'related_id'),
			'projectUsers' => array(self::HAS_MANY, 'ProjectUser', 'project_id'),
			'users' => array(self::HAS_MANY, 'User', array('user_id'=>'id'),'through' => 'projectUsers'),
			'versions' => array(self::HAS_MANY, 'Version', 'project_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'user_id' => 'Owner',
			'code' => 'Code',
			'label' => 'Name',
			'description' => 'Description',
			'topic_id' => 'Topic',
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
		$criteria->compare('code',$this->code,true);
		$criteria->compare('label',$this->label,true);
		$criteria->compare('description',$this->description,true);
		$criteria->compare('topic_id',$this->topic_id);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
	
	public function afterFind()
    {
                $this->oldRecord=clone $this;
                return parent::afterFind();     
    }
	
	
	public function getUserOptions()
	{
		$usersArray = Chtml::listData($this->users, 'id', 'username');
		return $usersArray;
	}
	
	public function getMembers()
	{
		return new CActiveDataProvider('User', array(
																'criteria' => array(
																	'with' => array(
																		'projectUsers' => array('condition' => 'project_id=:projectId', 'params' => array(':projectId' => $this->id), 'together' => true)),
																),
																'pagination' => array(
																	'pageSize' => 10,
																),
													));
	}
	
	public function getVersions($status = null)
	{
		/*$versionsDataProvider = new CActiveDataProvider('Version', array(
            						'data'=>$this->versions,
									'pagination' => array('pageSize' => 1)
   							 ));*/
		
		$criteria=new CDbCriteria;

		$criteria->compare('project_id',$this->id);
		
		if(!is_null($status))
			$criteria->compare('relation',$status);

		$versionsDataProvider = new CActiveDataProvider(Version, array(
			'criteria'=>$criteria,
			'pagination' => array('pageSize' => 1)
		));
		
   		$versionsDataProvider->sort->defaultOrder='due_date ASC';
   		

   		return array('id' => 'versions-grid',
							'ajaxUpdate'=>true,
							'dataProvider' => $versionsDataProvider,
							'itemView' => '/version/_viewInProject',
							'enableSorting' => true,
							'viewData' => array('model' => $model));
	}

	
	public function getMembership($userId = null)
	{
		if(is_null($userId))
			$userId = Yii::app()->user->id;
			
		Yii::trace('Check membership for user ID ' . $userId,'models.project');
			
		$oProjectUser = ProjectUser::model()->findByAttributes(array('project_id' => $this->id, 'user_id' => $userId));
		return $oProjectUser;
	}
	
	
	public function checkAccess($itemName = null, $userId = null)
	{
		if(Yii::app()->user->id == $this->user_id)
			return true;
		
		if(is_null($itemName))
			$itemName = 'Project.'. ucfirst(Yii::app()->controller->action->id);
			
		Yii::trace('Check access to ' . $itemName,'models.project');
		
		$oProjectUser = $this->getMembership($userId);
		if(!is_null($oProjectUser))
		{
			$role = $oProjectUser->role;
			
			$authorizer = Rights::getAuthorizer();
			$permissions = $authorizer->getPermissions($role);
			
			if($authorizer->hasPermission($itemName, $parentName=null, $permissions))
			{
				Yii::trace('Grant access to ' . $itemName . ' for ' . $role,'models.project');
				return true;
			}
			
		}
		
		Yii::trace('Deny access to ' . $itemName . ' for ' . $role,'models.project');
		return false;
	}
	
	public function isUserInProject()
	{
		$model = ProjectUser::model()->findByAttributes(array('project_id' => $this->id, 'user_id' => Yii::app()->user->id));
		
		if(is_null($model))
			return false;
		else
			return true;
	}
	
	public function getRelatedProject($relation = null)
	{
		$criteria=new CDbCriteria;

		$criteria->compare('project_id',$this->id);
		
		if(!is_null($relation))
			$criteria->compare('relation',$relation);

		return new CActiveDataProvider(ProjectRelation, array(
			'criteria'=>$criteria,
			'pagination' => array('pageSize' => 10)
		));
	}
	
	public function getRelatedOptions($value = null)
	{
	    $return = array( 
		       	self::RELATED_TO 			=> 'Related', 
		        self::RELATED_DUPLICATES	=> 'Duplicates', 
		        self::RELATED_DUPLICATEBY	=> 'Duplicate by',
		        self::RELATED_BLOCKS		=> 'Blocks', 
		        self::RELATED_BLOCKEDBY		=> 'Blocked by', 
		        self::RELATED_PRECEDES		=> 'Precedes',
		        self::RELATED_FOLLOWS		=> 'Follows',
				);
				
		if(!is_null($value))
			$return = $return[$value];
	    
	    return $return;
	}
	
	/**
	 * 
	 * Save the current data into the Project Logs object before saving the new data ...
	 */
	protected function beforeSave()
	{
		$projectLog = new ProjectLogs;
		$projectLog->project_id = $this->id;
		$projectLog->user_id = Yii::app()->user->id;
		$projectLog->owner_id = $this->user_id;
		$projectLog->topic_id = $this->topic_id;	
		$projectLog->label = $this->label;
		$projectLog->creation_date = date('Y-m-d H:i:s');
		
		if(!is_null($this->description))
			$projectLog->description = $this->description;
		
		if(!is_null($this->parent_id))
			$projectLog->parent_id = $this->parent_id;
		
		$projectLog->save();
		
		return parent::beforeSave();
	}
	
	protected function afterSave()
	{
		$member = new ProjectUser;
		$member->project_id = $this->id;
		$member->user_id = $this->user_id;
		$member->role = 'Project Owner';
		$member->save();
	}
}