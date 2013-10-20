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
		elseif($this->owner->relation == 7)
			return 8;
		elseif($this->owner->relation == 8)
			return 7;
	}
	
	public function beforeValidate($event)
	{
		$this->checkRelatedIssue();
		
		return parent::beforeValidate($event);
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
	
	public function afterSave($event)
	{
		if($this->owner->relation == Issue::RELATED_CHILD){
			$this->setParentId($this->owner->related_id, $this->owner->issue_id);
		}
	
		if($this->owner->relation == Issue::RELATED_PARENT){
			$this->setParentId($this->owner->issue_id, $this->owner->related_id);
		}
	}
	
	protected function setParentId($issueId, $parentId)
	{
		$model = Issue::model()->findByPk($issueId);
		$model->parent_id = $parentId;
		$model->save();
	}
	
	public function afterDelete($event)
	{
		parent::afterDelete();
	
		if($this->owner->relation == Issue::RELATED_CHILD){
			$this->resetParentId();
		}
	
		$criteria = new CDbCriteria;
		$criteria->compare('issue_id', $this->owner->related_id);
		$criteria->compare('related_id', $this->owner->issue_id);
	
		$model=IssueRelation::model()->find($criteria);
	
		if(!is_null($model))
			$model->delete();
	}
	
	protected function resetParentId()
	{
		$model = Issue::model()->findByPk($this->owner->related_id);
		$model->parent_id = null;
		$model->save();
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