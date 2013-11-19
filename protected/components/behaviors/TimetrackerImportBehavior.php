<?php
class TimetrackerImportBehavior extends CBehavior
{
	protected $row = false;
	
	public function importWorklog($filePath = false)
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
				
			return $records . ' Worklog succesfully imported!';
		}
		
		return 'No file provided!';
	}
	
	
	protected function mapImportedAttributes($attribute, $value = false)
	{
		if (in_array ( $attribute, array (
				'updateauthor',
				'username'
		) ))
			return $this->mapUser($value);
		elseif (in_array ( $attribute, array (
				'created',
				'creation_date'
		) ))
			$this->mapDate($value);
		elseif (in_array ( $attribute, array (
				'worklogbody',
				'comment',
				'description',
				'desc'
		) ))
			$this->row['comment'] = $value;
		elseif (in_array ( $attribute, array (
				'code',
				'pkey'
		) ))
			this->mapIssue($value);
		elseif (in_array ( $attribute, array (
				'updated',
				'update'
		) ))
			$this->mapUpdate($value);
		elseif (in_array ( $attribute, array (
					'timeworked',
					'timespent',
					'worked',
					'time_spent',
					'time'
			) ))
			$this->row['time_spent'] = $value;
		else
			return false;
	}
	
	protected function importRow($row)
	{
		$criteria=new CDbCriteria;
		$criteria->compare('username', $row['username']);
		$criteria->compare('issue_id', $row['issue_id']);
		$criteria->compare('log_date', $row['log_date']);

		$model = Timetracker::model()->find($criteria);
		
		if(is_null($model)){
			$model = new Timetracker;
			$model->billable = 0;
			
			if(is_null($row['activity_id']))
				$model->activity_id = $this->mapActivity();
			
			$model->attributes = $row;
			$model->save();
		}

		return $model;
	}
	
	protected function mapActivity($value = null)
	{
		if(is_null($value))
			$value = 'Undefined';
		
		$criteria=new CDbCriteria;
		$criteria->compare('label', $value);
			
		$model = TimeActivity::model()->find($criteria);
		
		if(is_null($model)){
			$model = new Activity;
			$model->label = $value;
		}
		
		return $model->id;
	}
	
	protected function mapUser($value)
	{
		$criteria=new CDbCriteria;
		$criteria->compare('username', $value);
		
		$model = Issue::model()->find($criteria);
		
		if(!is_null($model))
			$this->row['user_id']  = $model->id;
	}
	
	protected function mapIssue($value)
	{
		$criteria=new CDbCriteria;
		$criteria->compare('code', $value);
	
		$model = Issue::model()->find($criteria);
	
		if(!is_null($model))
			$this->row['issue_id']  = $model->id;
	}
	
	protected function mapDate($value)
	{
		$date = DateTime::createFromFormat('d/m/Y H:i:s', $value);
		
		$this->row['creation_date'] = $date->format('Y-m-d H:i:s');
	}
	
	protected function mapUpdate($value)
	{
		$date = new DateTime::createFromFormat('d/m/Y H:i:s', $value);
		
		$this->row['log_date'] = $date->format('Y-m-d H:i:s');
	}
	
	
}