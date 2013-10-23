<?php

/**
 * ContactForm class.
 * ContactForm is the data structure for keeping
 * contact form data. It is used by the 'contact' action of 'SiteController'.
 */
class PasswordForm extends CFormModel
{
	public $password;
	public $repeat_password;
	public $old_password;


	/**
	 * Declares the validation rules.
	 */
	public function rules()
	{
		return array(
			// name, email, subject and body are required
			array('password, password_repeat, old_password', 'required'),
			array('password', 'compare', 'on' => 'create, recover'),
			array('password_repeat', 'safe'),
		);
	}

	/**
	 * Declares customized attribute labels.
	 * If not declared here, an attribute would have a label that is
	 * the same as its name with the first letter in upper case.
	 */
	public function attributeLabels()
	{
		return array(
			'password'	=> 'New Password',
			'old_password'	=> 'Old Password',
			'repeat_password' 	=> 'Repeat Password'
		);
	}
	
	public function afterValidate()
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