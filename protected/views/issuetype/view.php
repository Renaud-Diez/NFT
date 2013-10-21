<?php
/* @var $this IssueTypeController */
/* @var $model IssueType */

$this->breadcrumbs=array(
	'Issue Types'=>array('index'),
	$model->id,
);

$this->menu=array(
	array('label'=>'List IssueType', 'url'=>array('index')),
	array('label'=>'Create IssueType', 'url'=>array('create')),
	array('label'=>'Update IssueType', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete IssueType', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage IssueType', 'url'=>array('admin')),
);
?>

<h1>View IssueType #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'label',
	),
)); ?>
