<?php
/* @var $this ProjectRoleController */
/* @var $model ProjectRole */

$this->breadcrumbs=array(
	'Project Roles'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List ProjectRole', 'url'=>array('index')),
	array('label'=>'Manage ProjectRole', 'url'=>array('admin')),
);
?>

<h1>Create ProjectRole</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>