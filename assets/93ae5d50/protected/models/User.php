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
			array('username, password, password_repeat', 'required'),
			array('password_repeat', 'safe'),
			array('username, password', 'length', 'max'=>45),
			array('email', 'length', 'max'=>100),
			array('username', 'unique'),
			array('email', 'email'),
			array('password', 'compare'),
			array('last_login', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, username, email', 'safe', 'on'=>'search'),
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
			'email' => 'Email',
			'last_login' => 'Last Login',
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
	
	protected function afterValidate()
	{
		if(!$this->hasErrors())
		{
			$this->password = $this->hashPassword($this->password);
		}
		
		return parent::afterValidate();
	}
	
	public function hashPassword($password)
	{
		return md5($password);
	}
	
	public function validatePassword($password)
	{
		return $this->hashPassword($password) === $this->password;
	}
}