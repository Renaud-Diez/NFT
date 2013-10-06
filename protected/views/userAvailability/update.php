<?php
/* @var $this UserAvailabilityController */
/* @var $model UserAvailability */

$this->breadcrumbs=array(
	'User Availabilities'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List UserAvailability', 'url'=>array('index')),
	array('label'=>'Create UserAvailability', 'url'=>array('create')),
	array('label'=>'View UserAvailability', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage UserAvailability', 'url'=>array('admin')),
);
?>

<h1>Update UserAvailability <?php echo $model->id; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>