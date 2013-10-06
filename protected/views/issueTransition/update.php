<?php
/* @var $this IssueTransitionController */
/* @var $model IssueTransition */

$this->breadcrumbs=array(
	'Issue Transitions'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List IssueTransition', 'url'=>array('index')),
	array('label'=>'Create IssueTransition', 'url'=>array('create')),
	array('label'=>'View IssueTransition', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage IssueTransition', 'url'=>array('admin')),
);
?>

<h1>Update IssueTransition <?php echo $model->id; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>