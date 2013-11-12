<?php

/**
 * FileImportForm class.
 * FileImportForm is the data structure for keeping
 * File form data. Used by the 'import' action of 'ProjectController'.
 */
class FileImportForm extends CFormModel
{
	public $file = null;
	public $path = null;
	public $do = null;


	/**
	 * Declares the validation rules.
	 */
	public function rules()
	{
		return array(
			// file is required
			array('file', 'file', 'types'=>'csv, xls, xlsx'),
			array('file, do, path', 'safe'),
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
			'file'	=> 'File',
		);
	}
}