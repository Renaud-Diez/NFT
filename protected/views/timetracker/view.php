<?php
/* @var $this TimetrackerController */
/* @var $model Timetracker */

$this->breadcrumbs=array(
	'Timetrackers'=>array('index'),
	$model->id,
);

$this->menu=array(
	array('label'=>'List Timetracker', 'url'=>array('index')),
	array('label'=>'Create Timetracker', 'url'=>array('create')),
	array('label'=>'Update Timetracker', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete Timetracker', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage Timetracker', 'url'=>array('admin')),
);
?>

<h1>View Timetracker #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'user_id',
		'issue_id',
		'time_spent',
		'billable',
		'comment',
		'activity_id',
	),
)); ?>
