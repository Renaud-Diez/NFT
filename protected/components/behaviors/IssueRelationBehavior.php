<?php
class IssueRelationBehavior extends CActiveRecordBehavior
{
	public function getOppositeRelation()
	{
		if($this->owner->relation == 0)
			return 0;
		elseif($this->owner->relation == 1)
			return 2;
		elseif($this->owner->relation == 2)
			return 1;
		elseif($this->owner->relation == 3)
			return 4;
		elseif($this->owner->relation == 4)
			return 3;
		elseif($this->owner->relation == 5)
			return 5;
		elseif($this->owner->relation == 6)
			return 5;
	}
	
	public function beforeValidate()
	{
		$this->checkRelatedIssue();
		
		return parent::beforeValidate();
	}
	
	public function beforeSave($event)
	{
		$model1 = IssueRelation::model()->findByAttributes(array('related_id' => $this->owner->related_id, 'issue_id' => $this->owner->issue_id));
		if(!empty($model1->id)){
			$model2 = IssueRelation::model()->findByAttributes(array('related_id' => $this->owner->issue_id, 'issue_id' => $this->owner->related_id));
			$this->deleteRelation($model1, $model2);
		}
			
		return parent::beforeSave();
	}
	
	protected function deleteRelation($model1, $model2)
	{
		$model1->delete();
		$model2->delete();
	}
	
	protected function checkRelatedIssue()
	{
		$issue = Issue::model()->findByPk($this->owner->related_id);
		
		if(empty($issue->id)){
			$this->owner->addError('related_id', 'Related Issue doesn\'t exist!');
		}
	}
}