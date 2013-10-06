<?php
/* @var $this TimetrackerController */
/* @var $model Timetracker */

$this->breadcrumbs=array(
	'Timetrackers'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List Timetracker', 'url'=>array('index')),
	array('label'=>'Manage Timetracker', 'url'=>array('admin')),
);
?>

<h1>Create Timetracker</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>