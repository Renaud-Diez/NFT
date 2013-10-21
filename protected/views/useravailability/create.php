<?php
/* @var $this UserAvailabilityController */
/* @var $model UserAvailability */

$this->breadcrumbs=array(
	'User Availabilities'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List UserAvailability', 'url'=>array('index')),
	array('label'=>'Manage UserAvailability', 'url'=>array('admin')),
);
?>

<h1>Create UserAvailability</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>