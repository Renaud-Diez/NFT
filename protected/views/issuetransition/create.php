<?php
/* @var $this IssueTransitionController */
/* @var $model IssueTransition */

$this->breadcrumbs=array(
	'Issue Transitions'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List IssueTransition', 'url'=>array('index')),
	array('label'=>'Manage IssueTransition', 'url'=>array('admin')),
);
?>

<h1>Create IssueTransition</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>