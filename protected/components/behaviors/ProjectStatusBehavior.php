<?php
class ProjectStatusBehavior extends CActiveRecordBehavior
{
	public function saveTopicRelation($arrValues)
	{
			$this->deleteRelations();
			foreach($arrValues as $value){
				$arr = explode(':', $value);
				$statusId = $arr[0];
				$relationId = $arr[1];
				
				if(is_null($this->findRelatedObject($statusId, $relationId))){
					$this->saveRelation($statusId, $relationId);
				}
			}
	}
	
	protected function deleteRelations()
	{
		ProjectStatusTopic::model()->deleteAll();
	}
	
	protected function findRelatedObject($statusId, $relationId)
	{
		return ProjectStatusTopic::model()->findByAttributes(array('status_id' => $statusId, 'topic_id' => $relationId));
	}
	
	protected function saveRelation($statusId, $relationId)
	{
		$model = new ProjectStatusTopic;
		$model->status_id = $statusId;
	    $model->topic_id = $relationId;
		$model->save();
	}
}