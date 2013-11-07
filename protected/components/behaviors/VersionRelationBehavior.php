<?php
class VersionRelationBehavior extends CActiveRecordBehavior
{
	public function getOppositeRelation()
	{
		if($this->owner->relation == 0)
			return 1;
		elseif($this->owner->relation == 1)
			return 0;
	}
	
	public function beforeValidate($event)
	{
		$this->checkRelatedVersion();
		
		return parent::beforeValidate($event);
	}
	
	public function beforeSave($event)
	{
		$model1 = VersionRelation::model()->findByAttributes(array('target_id' => $this->owner->target_id, 'source_id' => $this->owner->source_id));
		if(!empty($model1->id)){
			$model2 = VersionRelation::model()->findByAttributes(array('related_id' => $this->owner->source_id, 'issue_id' => $this->owner->target_id));
			$this->deleteRelation($model1, $model2);
		}
			
		return parent::beforeSave();
	}
	
	public function afterDelete($event)
	{
		parent::afterDelete();
	
		$criteria = new CDbCriteria;
		$criteria->compare('source_id', $this->owner->target_id);
		$criteria->compare('target_id', $this->owner->source_id);
	
		$model=VersionRelation::model()->find($criteria);
	
		if(!is_null($model))
			$model->delete();
	}
	
	
	protected function deleteRelation($model1, $model2)
	{
		$model1->delete();
		$model2->delete();
	}
	
	protected function checkRelatedVersion()
	{
		$model = Version::model()->findByPk($this->owner->target_id);
		
		if(empty($model->id)){
			$this->owner->addError('target_id', 'Related Version/Phase doesn\'t exist!');
		}
		
		$model = VersionRelation::model()->findByAttributes(array('target_id' => $this->owner->target_id, 'source_id' => $this->owner->source_id));
		
		if(!empty($model->id)){
			$this->owner->addError('target_id', 'Relation already exist!');
		}
	}
}