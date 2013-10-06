<?php
/* @var $this ProjectStatusController */
/* @var $model ProjectStatus */

$this->breadcrumbs=array(
	'Project Statuses'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List ProjectStatus', 'url'=>array('index')),
	array('label'=>'Create ProjectStatus', 'url'=>array('create')),
	array('label'=>'View ProjectStatus', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage ProjectStatus', 'url'=>array('admin')),
);
?>

<h1>Update Project Status <?php echo $model->id; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>