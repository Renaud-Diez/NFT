<?php
/* @var $this MilestoneController */
/* @var $model Milestone */

$this->breadcrumbs=array(
	'Milestones'=>array('index'),
	$model->id,
);

$this->menu=array(
	array('label'=>'List Milestone', 'url'=>array('index')),
	array('label'=>'Create Milestone', 'url'=>array('create')),
	array('label'=>'Update Milestone', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete Milestone', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage Milestone', 'url'=>array('admin')),
);
?>

<h1>View Milestone #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'label',
		'project_id',
		'version_id',
		'creation_date',
		'due_date',
		'status',
	),
)); ?>
