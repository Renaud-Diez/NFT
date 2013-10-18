<?php

/**
 * ContactForm class.
 * ContactForm is the data structure for keeping
 * contact form data. It is used by the 'contact' action of 'SiteController'.
 */
class DateRangeForm extends CFormModel
{
	public $name;
	public $from;
	public $to;


	/**
	 * Declares the validation rules.
	 */
	public function rules()
	{
		return array(
			// name, email, subject and body are required
			array('from, to, name', 'safe'),
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
			'from'	=> 'From Date',
			'to'	=> 'To Date',
			'name' 	=> 'Name'
		);
	}
}