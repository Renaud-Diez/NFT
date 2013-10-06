<?php
/* @var $this MilestoneController */
/* @var $model Milestone */

$this->breadcrumbs=array(
	'Milestones'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List Milestone', 'url'=>array('index')),
	array('label'=>'Manage Milestone', 'url'=>array('admin')),
);
?>

<h1>Create Milestone</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>