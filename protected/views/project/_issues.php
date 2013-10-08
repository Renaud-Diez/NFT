<div style="margin-top:-24px">
<?php 
	/*if(!$dataProvider)
	{
		$issue=new Issue('search');
		$issue->unsetAttributes();
			
		if(isset($_GET['Issue']))
			$issue->attributes=$_GET['Issue'];
			
		if($type == 'version')
			$issue->attributes=array('version_id'=>$gridId);
		elseif($type == 'milestone')
			$issue->attributes=array('milestone_id'=>$gridId);
		elseif($type == 'project')
			$issue->attributes=array('version_id' => array(0=>NULL), 'milestone_id' => NULL);
			
		$dataProvider = $model->getDataProviderIssues($issue);
	}*/

	$name = 'issue-grid'.$gridId;
    $this->widget('bootstrap.widgets.TbExtendedGridView', array(
    //'filter' => $issue,
    'id' => $name,
    'dataProvider' => $dataProvider,
    'type' => 'striped condensed',
    'summaryText' => false,
    //'cacheTTL' => 10, // cache will be stored 10 seconds (see cacheTTLType)
    //'cacheTTLType' => 's', // type can be of seconds, minutes or hours
    'columns' => array(
    'id',
    'label',
    array('name' => 'assignee.username', 'value' => $data->assignee->username,'header' => 'Assignee'),
    array('name' => 'type.label', 'value' => $data->type->label,'header' => 'Type'),
	array('name' => 'status.label', 'value' => $data->status->label,'header' => 'Status'),
	array('name' => 'priority', 'value' => 'Issue::model()->getPriorities($data->priority)'),
    array(
    'header' => Yii::t('ses', 'View'),
    'class' => 'bootstrap.widgets.TbButtonColumn',
    'template' => '{view}',
    'buttons'=>array
    (
      'view' => array
        (
        'url'=>'CController::createUrl("/issue/view", array("id"=>$data->primaryKey))',
        //'url'=>'"index.php?r=leads/update&id="',
        ),
     ),
    
    ),
    ),
    ));

?>
</div>