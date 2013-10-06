<?php
/* @var $this TimetrackerController */
/* @var $model Timetracker */

$this->breadcrumbs=array(
	'Timetrackers'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List Timetracker', 'url'=>array('index')),
	array('label'=>'Create Timetracker', 'url'=>array('create')),
	array('label'=>'View Timetracker', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage Timetracker', 'url'=>array('admin')),
);
?>

<h1>Update Timetracker <?php echo $model->id; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>