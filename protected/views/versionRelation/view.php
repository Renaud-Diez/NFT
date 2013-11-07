<?php
/* @var $this VersionRelationController */
/* @var $model VersionRelation */

$this->breadcrumbs=array(
	'Version Relations'=>array('index'),
	$model->id,
);

$this->menu=array(
	array('label'=>'List VersionRelation', 'url'=>array('index')),
	array('label'=>'Create VersionRelation', 'url'=>array('create')),
	array('label'=>'Update VersionRelation', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete VersionRelation', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage VersionRelation', 'url'=>array('admin')),
);
?>

<h1>View VersionRelation #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'source_id',
		'target_id',
		'relation',
	),
)); ?>
