<?php
/* @var $this UserAvailabilityController */
/* @var $model UserAvailability */

$this->breadcrumbs=array(
	'User Availabilities'=>array('index'),
	$model->id,
);

$this->menu=array(
	array('label'=>'List UserAvailability', 'url'=>array('index')),
	array('label'=>'Create UserAvailability', 'url'=>array('create')),
	array('label'=>'Update UserAvailability', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete UserAvailability', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage UserAvailability', 'url'=>array('admin')),
);
?>

<h1>View UserAvailability #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'user_id',
		'hoursbyday',
		'daysbyweek',
	),
)); ?>
