<?php
class TeamBehavior extends CActiveRecordBehavior
{
	/*
	public function afterFind() {
        parent::afterFind();
        $this->oldfile = $this->file;
    }
 
    public function afterDelete() {
        $this->deleteFile();
        return parent::afterDelete();
    }
	
	public function beforeValidate($event)
	{
		$this->owner->creation_date = date('Y-m-d');
		$this->owner->user_id = Yii::app()->user->id;
		
		return parent::beforeValidate();
	}
 
    public function beforeSave() 
    {
    	if(is_object($this->owner->file)) {
            $this->owner->file->saveAs($uploadPath);

            return parent::beforeSave();
        }
        return false;
    }
	*/
	
	public function getUserNotInProject($teamId)
	{
		$dataProvider = new CActiveDataProvider('User', array(
				'criteria' => array(
						'with' => array(
								'' => array('condition' => 'team_id='.$teamId, 'together' => true)),
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
}