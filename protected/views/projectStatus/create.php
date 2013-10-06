<?php
/* @var $this ProjectStatusController */
/* @var $model ProjectStatus */

$this->breadcrumbs=array(
	'Project Statuses'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List ProjectStatus', 'url'=>array('index')),
	array('label'=>'Manage ProjectStatus', 'url'=>array('admin')),
);
?>

<h1>Create Project Status</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>