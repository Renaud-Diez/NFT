<?php
/* @var $this IssueController */
/* @var $model Issue */

$this->breadcrumbs=array(
	'User'=>array('index'),
	'view',
	$model->uname,
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$('#issue-grid').yiiGridView('update', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<h1>Issues Overview for <?php echo $model->uname;?></h1>
<?php $this->renderPartial('partials/titleMenu', array('model'=>$model)); ?>
<p>
You may optionally enter a comparison operator (<b>&lt;</b>, <b>&lt;=</b>, <b>&gt;</b>, <b>&gt;=</b>, <b>&lt;&gt;</b>
or <b>=</b>) at the beginning of each of your search values to specify how the comparison should be done.
</p>
<?php $this->issue=$issue;?>

<?php 
	$this->widget('bootstrap.widgets.TbExtendedGridView', array(
    //'dataProvider' => $issues->search(),
    'id' => 'issue-grid',
    'dataProvider' => $dataProvider,//$dataProvider,
    'filter' => $issue,//$issue,
    'type' => 'striped bordered condensed',
    'summaryText' => false,
    //'cacheTTL' => 10, // cache will be stored 10 seconds (see cacheTTLType)
    //'cacheTTLType' => 's', // type can be of seconds, minutes or hours
    'columns' => array(
    'label',
    //array('name' => 'label', 'value' => $data->label,'header' => 'Label', 'filter' => CHtml::activeTextField($issue, 'label'),
    array('name' => 'project.label', 'value' => $data->project->label,'header' => 'Project', 'filter' => CHtml::activeDropDownList( $issue, 'project_id',
    				CHtml::listData(Project::model()->findAll(array('order'=>'id')),'id', 'label'),
    				array('prompt'=>'Select a Project'))),
    array('name' => 'assignee.username', 'value' => $data->assignee->username,'header' => 'Assignee', 'filter' => CHtml::activeDropDownList( $issue, 'assignee_id', 
                    CHtml::listData(User::model()->findAll(array('order'=>'id')),'id', 'username'),
					array('prompt'=>'Select an Assignee'))),
    array('name' => 'type.label', 'value' => $data->type->label,'header' => 'Type', 'filter' => CHtml::activeDropDownList( $issue, 'type_id', 
                    CHtml::listData(IssueType::model()->findAll(array('order'=>'id')),'id', 'label'), 
					array('empty'=>'Select a Type'))),
	array('name' => 'status.label', 'value' => $data->status->label,'header' => 'Status', 'filter' => CHtml::activeDropDownList( $issue, 'status_id', 
                    CHtml::listData(IssueStatus::model()->findAll(array('order'=>'id')),'id', 'label'), 
					array('empty'=>'Select a Status'))),
	array('name' => 'priority', 'value' => 'Issue::model()->getPriorities($data->priority)', 'filter' => CHtml::activeDropDownList( $issue, 'priority', 
                    $issue->getPriorities(), 
					array('empty'=>'Select a Priority'))),
	'overrun',
	array('name' => 'overdue', 'value' => 'DateTimeHelper::timeElapse($data->due_date)'),
    array(
    'header' => Yii::t('ses', 'Edit'),
    'class' => 'bootstrap.widgets.TbButtonColumn',
    'template' => '{view}',
    'buttons'=>array
    (
      'view' => array
        (
        'url'=>'CController::createUrl("/issue/view", array("id"=>$data->primaryKey))',
        ),
     ),
    
    ),
    ),
    ));



?>
<br />
