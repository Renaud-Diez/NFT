<?php
/* @var $this IssueController */
/* @var $model Issue */

$this->breadcrumbs=array(
	'Issues'=>array('index'),
	'Manage',
);

$this->menu=array(
	array('label'=>'List Issue', 'url'=>array('index')),
	array('label'=>'Create Issue', 'url'=>array('create')),
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

<h1>Critical Issues</h1>

<p>
You may optionally enter a comparison operator (<b>&lt;</b>, <b>&lt;=</b>, <b>&gt;</b>, <b>&gt;=</b>, <b>&lt;&gt;</b>
or <b>=</b>) at the beginning of each of your search values to specify how the comparison should be done.
</p>
<?php $this->issue=$model;?>

<?php /*$this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'issue-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		'id',
		'label',
		//'project_id',
		'user_id',
		'assignee_id',
		'status_id',
		array(
			'class'=>'CButtonColumn',
		),
	),
));*/

$this->widget('bootstrap.widgets.TbExtendedGridView', array(
    //'dataProvider' => $issues->search(),
    'id' => 'issue-grid',
    'dataProvider' => $model->criticalIssues(),//$dataProvider,
    'filter' => $model,//$issue,
    'type' => 'striped bordered condensed',
    'summaryText' => false,
    //'cacheTTL' => 10, // cache will be stored 10 seconds (see cacheTTLType)
    //'cacheTTLType' => 's', // type can be of seconds, minutes or hours
    'columns' => array(
    'id',
    'label',
    //array('name' => 'label', 'value' => $data->label,'header' => 'Label', 'filter' => CHtml::activeTextField($issue, 'label'),
    array('name' => 'project.label', 'value' => $data->project->label,'header' => 'Project', 'filter' => CHtml::activeDropDownList( $model, 'project_id',
    				CHtml::listData(Project::model()->findAll(array('order'=>'id')),'id', 'label'),
    				array('prompt'=>'- Project -'))),
    array('name' => 'assignee.username', 'value' => $data->assignee->username,'header' => 'Assignee', 'filter' => CHtml::activeDropDownList( $model, 'assignee_id', 
                    CHtml::listData(User::model()->findAll(array('order'=>'id')),'id', 'username'),
					array('prompt'=>'- Assignee -'))),
    array('name' => 'type.label', 'value' => $data->type->label,'header' => 'Type', 'filter' => CHtml::activeDropDownList( $model, 'type_id', 
                    CHtml::listData(IssueType::model()->findAll(array('order'=>'id')),'id', 'label'), 
					array('empty'=>'- Type -'))),
	array('name' => 'status.label', 'value' => $data->status->label,'header' => 'Status', 'filter' => CHtml::activeDropDownList( $model, 'status_id', 
                    CHtml::listData(IssueStatus::model()->findAll(array('order'=>'id')),'id', 'label'), 
					array('empty'=>'- Status -'))),
	array('name' => 'priority', 'value' => 'Issue::model()->getPriorities($data->priority)', 'filter' => CHtml::activeDropDownList( $model, 'priority', 
                    $model->getPriorities(), 
					array('empty'=>'- Priority -'))),
	'overrun',
	array('name' => 'due_date', 'value' => 'DateTimeHelper::timeElapse($data->due_date)'),
    array(
    'header' => Yii::t('ses', 'Edit'),
    'class' => 'bootstrap.widgets.TbButtonColumn',
    'template' => '{view} {delete}',
    'buttons'=>array
    (
      'view' => array
        (
        'url'=>'CController::createUrl("/issue/view", array("id"=>$data->primaryKey))',
        //'url'=>'"index.php?r=leads/update&id="',
        ),
       'delete' => array
        (
        'url'=>'CController::createUrl("/issue/delete", array("id"=>$data->primaryKey))',
        //'url'=>'"index.php?r=leads/update&id="',
        ),
     ),
    
    ),
    ),
    ));



?>
