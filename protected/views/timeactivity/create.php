<?php
/* @var $this TimeActivityController */
/* @var $model TimeActivity */

$this->breadcrumbs=array(
	'Time Activities'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List TimeActivity', 'url'=>array('index')),
	array('label'=>'Manage TimeActivity', 'url'=>array('admin')),
);
?>

<h1>Create TimeActivity</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>