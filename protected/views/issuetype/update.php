<?php
/* @var $this IssueTypeController */
/* @var $model IssueType */

$this->breadcrumbs=array(
	'Issue Types'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List IssueType', 'url'=>array('index')),
	array('label'=>'Create IssueType', 'url'=>array('create')),
	array('label'=>'View IssueType', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage IssueType', 'url'=>array('admin')),
);
?>

<h1>Update IssueType <?php echo $model->id; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>