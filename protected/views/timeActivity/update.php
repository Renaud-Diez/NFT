<?php
/* @var $this TimeActivityController */
/* @var $model TimeActivity */

$this->breadcrumbs=array(
	'Time Activities'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List TimeActivity', 'url'=>array('index')),
	array('label'=>'Create TimeActivity', 'url'=>array('create')),
	array('label'=>'View TimeActivity', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage TimeActivity', 'url'=>array('admin')),
);
?>

<h1>Update TimeActivity <?php echo $model->id; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>