<?php
class UserImportBehavior extends CBehavior
{
	protected $row = false;
	
	public function importUsers($filePath = false)
	{
		$records = 0;
		if($filePath && is_file($filePath)){
			$arrSheet = Yii::app ()->yexcel->readActiveSheet ( $filePath );
			$first = false;
			foreach ( $arrSheet as $rec ) {
				$this->row = false;
				if($first){
					foreach ( $rec as $record => $value ) {
						$this->mapImportedAttributes( strtolower($arrSheet[1][$record]), $value);
					}
					
					if($this->row){
						$model = $this->importRow($this->row);
						if($model->id)
							$records++;
					}
				}
				$first = true;
			}
				
			return $records . ' Users succesfully imported!';
		}
		
		return 'No file provided!';
	}
	
	
	protected function mapImportedAttributes($attribute, $value = false)
	{
		if (in_array ( $attribute, array (
				'user_name',
				'username'
		) ))
			$this->row['username'] = $value;
		elseif (in_array ( $attribute, array (
				'first_name',
				'firstname'
		) ))
			$this->row['firstname'] = ucfirst($value);
		elseif (in_array ( $attribute, array (
				'last_name',
				'lastname'
		) ))
			$this->row['lastname'] = ucfirst($value);
		elseif (in_array ( $attribute, array (
					'email',
					'email_address'
			) ))
			$this->row['email'] = ucfirst($value);
		elseif (in_array ( $attribute, array (
					'display_name',
					'displayname',
					'uname',
					'name',
					'username'
			) ))
			$this->mapUserName($value);
		elseif (in_array ( $attribute, array (
				'credential'
		) ))
			$this->row['password'] = value;
		else
			return false;
	}
	
	protected function importRow($row)
	{
		$criteria=new CDbCriteria;
		$criteria->compare('username', $row['username']);

		$model = User::model()->find($criteria);
		
		if(is_null($model)){
			$model = new User;
			$model->password = $model->password_repeat = 'Sodexo' . date('Y');
		}
		
		$model->attributes = $row;
		$model->save();
		
		return $model;
	}
	
	protected function mapUserName($value)
	{
		$arr = explode(' ', $value, 2);

		if(is_null($this->row['firstname']))
			$this->row['firstname'] = ucwords($arr[0]);
		
		if(is_null($this->row['lastname']))
			$this->row['lastname']  = ucwords($arr[1]);
	}
	
}