<?php
/* @var $this VersionController */
/* @var $model Version */

$this->breadcrumbs=array(
	'Versions'=>array('index'),
	$model->id,
);

$this->menu=array(
	array('label'=>'List Version', 'url'=>array('index')),
	array('label'=>'Create Version', 'url'=>array('create')),
	array('label'=>'Update Version', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete Version', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage Version', 'url'=>array('admin')),
);
?>

<h1>View Version #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'label',
		array('label' => $model->getAttributeLabel('project_id'),'type' => 'raw','value' => CHtml::link($model->project->label, array('project/view', 'id' => $model->project_id))),
		'creation_date',
		'due_date',
	),
)); ?>
