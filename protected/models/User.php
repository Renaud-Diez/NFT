<?php

/**
 * This is the model class for table "user".
 *
 * The followings are the available columns in table 'user':
 * @property integer $id
 * @property string $username
 * @property string $password
 * @property string $email
 * @property string $last_login
 *
 * The followings are the available model relations:
 * @property Issue[] $issues
 * @property Issue[] $issues1
 * @property Project[] $projects
 * @property ProjectUser[] $projectUsers
 * @property Timetracker[] $timetrackers
 */
class User extends CActiveRecord
{
	public $password_repeat;
	public $old_password;
	public $oldRecord;
	public $uname;
	public $from;
	public $to;
	
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return User the static model class
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
		return 'user';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.		
		return array(
			array('username, password, password_repeat', 'required', 'on' => 'create'),
			//array('username', 'required', 'on' => 'update'),
			array('password, password_repeat', 'required', 'on' => 'recover'),
			array('password, password_repeat, old_password', 'required', 'on' => 'password'),
			array('password', 'compare', 'on' => 'create, recover, password'),
			array('password_repeat, old_password', 'safe'),
			array('username, password', 'length', 'max'=>45),
			array('email', 'length', 'max'=>100),
			array('username', 'unique'),
			array('old_password', 'validateOldPassword', 'on' => 'password'),
			array('email', 'email'),
			array('last_login, firstname, lastname, homepage, hoursbyday, daysbyweek', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, username, email, firstname, lastname, homepage, hoursbyday, daysbyweek', 'safe', 'on'=>'search'),
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
			'issues' => array(self::HAS_MANY, 'Issue', 'user_id'),
			'issues1' => array(self::HAS_MANY, 'Issue', 'assignee_id'),
			'projects' => array(self::HAS_MANY, 'Project', 'user_id'),
			'projectUsers' => array(self::HAS_MANY, 'ProjectUser', 'user_id'),
			'issueUsers' => array(self::HAS_MANY, 'IssueUser', 'user_id'),
			'teamUsers' => array(self::HAS_MANY, 'UserTeam', 'user_id'),
			'timetrackers' => array(self::HAS_MANY, 'Timetracker', 'user_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'username' => 'Username',
			'password' => 'Password',
			'old_password' => 'Old Password',
			'password_repeat' => 'repeat Password',
			'email' => 'Email',
			'last_login' => 'Last Login',
			'firstname' => 'Firstname',
			'lastname' => 'Lastname',
			'homepage' => 'Homepage',
			'hoursbyday' => 'Hours by day',
			'daysbyweek' => 'Days by week',
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
		$criteria->compare('username',$this->username,true);
		//$criteria->compare('password',$this->password,true);
		$criteria->compare('email',$this->email,true);
		$criteria->compare('last_login',$this->last_login,true);
		$criteria->compare('email',$this->email,true);
		$criteria->compare('firstname',$this->firstname,true);
		$criteria->compare('lastname',$this->lastname,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
	
/**
	 * 
	 * Enter description here ...
	 * @param $projectId
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function getUserNotInProject($projectId)
	{
		$dataProvider = new CActiveDataProvider('User', array(
																'criteria' => array(
																	'with' => array(
																		'projectUsers' => array('condition' => 'project_id='.$projectId, 'together' => true)),
																),
													));
		
		$dataArray = $dataProvider->getData();
		foreach ($dataArray as $data)
			$usersArray[] = $data->id;

		
		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		$criteria->compare('username',$this->username,true);
		$criteria->compare('email',$this->email,true);
		
		$criteria->addNotInCondition('id', $usersArray);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
	
	
	public function getUserNotInIssue($issueId)
	{
		$dataProvider = new CActiveDataProvider('User', array(
																'criteria' => array(
																	'with' => array(
																		'issueUsers' => array('condition' => 'issue_id='.$issueId, 'together' => true)),
																),
													));
		
		$dataArray = $dataProvider->getData();
		foreach ($dataArray as $data)
			$usersArray[] = $data->id;

		
		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		$criteria->compare('username',$this->username,true);
		$criteria->compare('email',$this->email,true);
		
		$criteria->addNotInCondition('id', $usersArray);
		//$criteria->addInCondition('id', $projectUsers);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	public function validateOldPassword($attribute, $params)
	{
		if($this->hashPassword($this->$attribute) != $this->oldRecord->password)
			$this->addError($attribute, 'Current password is not the one provided!');
	}
	
	public function afterFind()
	{
		$this->oldRecord = clone $this;
		return parent::afterFind();
	}
	
	public function beforeSave()
	{
		if(!$this->hasErrors())
		{
			$this->password = $this->hashPassword($this->password);
		}
		
		return parent::beforeSave();
	}
	
	public function hashPassword($password)
	{
		return md5($password);
	}
	
	public function validatePassword($password)
	{
		return $this->hashPassword($password) === $this->password;
	}
	
	public function behaviors()
	{
		return array(
				'UserBehavior'=>array(
				'class'=>'application.components.behaviors.UserBehavior'
				),
		);
	}
}