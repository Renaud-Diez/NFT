<?php
/* @var $this ProjectController */
/* @var $model Issue */

$this->breadcrumbs=array(
	'Project'=>array('index'),
	$model->label=>array('view','id'=>$model->id),
	'Issues',
);

$this->issueMenu=array(
	array('label'=>'Create Issue', 'url'=>array('issue/create', 'pid'=>$model->id)),
	array('label'=>'View Issues', 'url'=>array('issues', 'id'=>$model->id)),
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

<h1>Project Issues</h1>

<p>
You may optionally enter a comparison operator (<b>&lt;</b>, <b>&lt;=</b>, <b>&gt;</b>, <b>&gt;=</b>, <b>&lt;&gt;</b>
or <b>=</b>) at the beginning of each of your search values to specify how the comparison should be done.
</p>

<?php echo CHtml::link('Advanced Search','#',array('class'=>'search-button')); ?>
<div class="search-form" style="display:none">
<?php $this->renderPartial('_searchIssues',array(
	'model'=>$issue, 'project' => $model
)); ?>
</div><!-- search-form -->

<?php /*$this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'issue-grid',
	'dataProvider'=>$issues->search(),
	'filter'=>$issues,
	'columns'=>array(
		'id',
		'label',
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
    'dataProvider' => $dataProvider,
    'filter' => $issue,
    'type' => 'striped bordered condensed',
    'summaryText' => false,
    //'cacheTTL' => 10, // cache will be stored 10 seconds (see cacheTTLType)
    //'cacheTTLType' => 's', // type can be of seconds, minutes or hours
    'columns' => array(
    'id',
    'label',
    //array('name' => 'label', 'value' => $data->label,'header' => 'Label', 'filter' => CHtml::activeTextField($issue, 'label'),
    array('name' => 'assignee.username', 'value' => $data->assignee->username,'header' => 'Assignee', 'filter' => CHtml::activeDropDownList( $issue, 'assignee_id', 
                    $issue->getAssignableUsers($model->id),
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

<?php $this->renderPartial('_sidebar', array('model'=>$model)); ?>