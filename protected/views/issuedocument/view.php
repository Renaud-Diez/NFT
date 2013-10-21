<?php
/* @var $this IssueDocumentController */
/* @var $model IssueDocument */

$this->breadcrumbs=array(
	'Issue Documents'=>array('index'),
	$model->id,
);

$this->menu=array(
	array('label'=>'List IssueDocument', 'url'=>array('index')),
	array('label'=>'Create IssueDocument', 'url'=>array('create')),
	array('label'=>'Update IssueDocument', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete IssueDocument', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage IssueDocument', 'url'=>array('admin')),
);
?>

<h1>View IssueDocument #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'issue_id',
		'document_id',
	),
)); ?>
