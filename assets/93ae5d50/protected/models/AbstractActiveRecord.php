<?php
class AbstractActiveRecord extends CActiveRecord
{
	protected function beforeSave()
	{
		if(null !== Yii::app()->user)
			$id = Yii::app()->user->id;
			
		if($this->isNewRecord)
			$this->user_id = $id;
		
		return parent::beforeSave();
	}
	
	protected function behaviors()
	{
		return array('CTimestampBehavior' => array('class' => 'zii.behaviors.CTimestampBehavior',
													'creatAttribute' => 'creation_date',
													//'setUpdateonCreate' => true,
													));
	}
}