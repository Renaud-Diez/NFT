<?php
/* @var $this TimeActivityController */
/* @var $model TimeActivity */

$this->breadcrumbs=array(
	'Time Activities'=>array('index'),
	$model->id,
);

$this->menu=array(
	array('label'=>'List TimeActivity', 'url'=>array('index')),
	array('label'=>'Create TimeActivity', 'url'=>array('create')),
	array('label'=>'Update TimeActivity', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete TimeActivity', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage TimeActivity', 'url'=>array('admin')),
);
?>

<h1>View TimeActivity #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'label',
	),
)); ?>
