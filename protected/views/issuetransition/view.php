<?php
/* @var $this IssueTransitionController */
/* @var $model IssueTransition */

$this->breadcrumbs=array(
	'Issue Transitions'=>array('index'),
	$model->id,
);

$this->menu=array(
	array('label'=>'List IssueTransition', 'url'=>array('index')),
	array('label'=>'Create IssueTransition', 'url'=>array('create')),
	array('label'=>'Update IssueTransition', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete IssueTransition', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage IssueTransition', 'url'=>array('admin')),
);
?>

<h1>View IssueTransition #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'issue_id',
		'action',
		'project_id',
		'version_id',
		'milestone_id',
		'comment',
	),
)); ?>
