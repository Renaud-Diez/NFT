<?php
/* @var $this MilestoneController */
/* @var $model Milestone */

$this->breadcrumbs=array(
	'Milestones'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List Milestone', 'url'=>array('index')),
	array('label'=>'Create Milestone', 'url'=>array('create')),
	array('label'=>'View Milestone', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage Milestone', 'url'=>array('admin')),
);
?>

<h1>Update Milestone <?php echo $model->id; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>