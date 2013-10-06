<?php
class IssueTransitionBehavior extends CActiveRecordBehavior
{
	CONST APPLICATION_CLONE = 'clone';
	CONST APPLICATION_CREATE = 'create';
	CONST APPLICATION_MOVE = 'move';
	
	public function getApplicationOptions($value = null)
	{
		$return = array( 
	        self::APPLICATION_CLONE => 'clone', 
	        self::APPLICATION_CREATE => 'create', 
	        self::APPLICATION_MOVE => 'move',
	    );
	    
	    if(!is_null($value))
		$return = $return[$value];
		
		return $return;
	}
	
	public function getProjects()
	{
		$issue = Issue::model()->findByPk($this->owner->issue_id);
		
		$union = Yii::app()->db->createCommand()
		->select('pr.related_id')
		->from('project_relation pr')
		->where('pr.project_id = ' . $issue->project_id)
		->text;
		
		
		$criteria=new CDbCriteria;
		$criteria->compare('p.id', $issue->project_id);
		$criteria->addCondition('id IN ('.$union.')', 'OR');
		
		$where=$criteria->condition;
		$params=$criteria->params;
		
		$result = Yii::app()->db->createCommand()
		->select('p.id, p.label')
		->from('project p')
		->where($where, $params)
		->queryAll();
		
		return CHtml::listData($result, 'id', 'label');
	}
}