<?php
class IssueStatusBehavior extends CActiveRecordBehavior
{
	CONST BACKLOG = 1;
	CONST OPEN = 2;
	CONST DONE = 3;
	
	public function getAvailableStatus($type_id)
	{
		//$data = IssueStatus::model()->findAll('version_id=:version_id', array(':version_id'=>(int) $_POST['version_id']));
	   	//$data = CHtml::listData($data,'id','label');
	   	
	   	$criteria = new CDbCriteria();
		$criteria->with = array('issueTypes');
		$criteria->together = true;
		$criteria->condition = 'issueTypes.id=:type_id';
		$criteria->params = array(':type_id' => $type_id);
		$criteria->order = 'rank ASC';
		
		return CHtml::listData(IssueStatus::model()->findAll($criteria), 'id', 'label');
	}
	
	public function getAlias($value = null)
	{
		$return = array( 
	        self::BACKLOG => 'TODO', 
	        self::OPEN => 'IN PROGRESS', 
	        self::DONE => 'DONE',
	    );
	    
	    if(!is_null($value))
		$return = $return[$value];
		
		return $return;
	}
}