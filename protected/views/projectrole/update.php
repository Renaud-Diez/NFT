<?php
/* @var $this ProjectRoleController */
/* @var $model ProjectRole */

$this->breadcrumbs=array(
	'Project Roles'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List ProjectRole', 'url'=>array('index')),
	array('label'=>'Create ProjectRole', 'url'=>array('create')),
	array('label'=>'View ProjectRole', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage ProjectRole', 'url'=>array('admin')),
);
?>

<h1>Update ProjectRole <?php echo $model->id; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>