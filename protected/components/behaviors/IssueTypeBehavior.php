<?php
class IssueTypeBehavior extends CActiveRecordBehavior
{
	public function saveTopicRelation($arrValues, $relationName)
	{
			$this->deleteRelations($relationName);
			foreach($arrValues as $value){
				$arr = explode(':', $value);
				$typeId = $arr[0];
				$relationId = $arr[1];
				
				if(is_null($this->findRelatedObject($relationName, $relationId, $typeId))){
					$this->saveRelation($relationName, $typeId, $relationId);
				}
			}
	}
	
	protected function deleteRelations($relationName)
	{
		if($relationName == 'Topic')
			IssueTypeTopic::model()->deleteAll();
		else
			IssueTypeStatus::model()->deleteAll();
	}
	
	protected function findRelatedObject($relationName, $relationId, $typeId)
	{
		if($relationName == 'Topic')
			return IssueTypeTopic::model()->findByAttributes(array('type_id' => $typeId, 'topic_id' => $relationId));
		else
			return IssueTypeStatus::model()->findByAttributes(array('type_id' => $typeId, 'status_id' => $relationId));
	}
	
	protected function saveRelation($relationName, $typeId, $relationId)
	{
		if($relationName == 'Topic'){
			$model = new IssueTypeTopic;
			$model->topic_id = $relationId;
		}
		else{
			$model = new IssueTypeStatus;
			$model->status_id = $relationId;
		}
		
	    $model->type_id = $typeId;
		$model->save();
	}
}