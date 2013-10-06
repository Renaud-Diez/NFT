<?php

/**
 * UserIdentity represents the data needed to identity a user.
 * It contains the authentication method that checks if the provided
 * data can identity the user.
 */
class UserIdentity extends CUserIdentity
{
	public $uname;
	private $_id;
	/**
	 * Authenticates a user.
	 * The example implementation makes sure if the username and password
	 * are both 'demo'.
	 * In practical applications, this should be changed to authenticate
	 * against some persistent user identity storage (e.g. database).
	 * @return boolean whether authentication succeeds.
	 */
	public function authenticate()
	{
		/*$users=array(
			// username => password
			'demo'=>'demo',
			'admin'=>'admin',
		);*/
		$user = User::model()->find('LOWER(username)=?', array(strtolower($this->username)));
		
		if($user === null)
			$this->errorCode=self::ERROR_USERNAME_INVALID;
		elseif(!$user->validatePassword($this->password))
			$this->errorCode=self::ERROR_PASSWORD_INVALID;
		else
		{
			$this->_id = $user->id;
			$this->username = $user->username;
			
			if(!empty($this->firstname))
				$this->uname = $this->firstname;
			elseif(!empty($this->lastname)){
				if(!empty($this->uname))
					$this->uname .= ' ' . $this->lastname;
				else
					$this->uname = $this->lastname;
			}else
				$this->uname = $this->username;
			
			//Set an additional attribute to the User Identity in order to be able to call Yii::app()->user->lastLogin
			$this->setState('lastLogin', date("m/d/y g:i A", strtotime($user->last_login)));
			//Save the login timestamp to user profile in DB
			$user->saveAttributes(array('last_login' => date("Y-m-d H:i:s", time())));
			
			$this->errorCode=self::ERROR_NONE;
		}
			
		return !$this->errorCode;
	}
	
	
	
	public function getId()
	{
		return $this->_id;
	}
}