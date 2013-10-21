<?php
/* @var $this ProjectStatusController */
/* @var $model ProjectStatus */

$this->breadcrumbs=array(
	'Project Statuses'=>array('index'),
	$model->id,
);

$this->menu=array(
	array('label'=>'List ProjectStatus', 'url'=>array('index')),
	array('label'=>'Create ProjectStatus', 'url'=>array('create')),
	array('label'=>'Update ProjectStatus', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete ProjectStatus', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage ProjectStatus', 'url'=>array('admin')),
);
?>

<h1>View ProjectStatus #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'label',
		'rank',
		'closed_alias',
	),
)); ?>
