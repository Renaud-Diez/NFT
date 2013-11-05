<?php
/* @var $this VersionController */
/* @var $model Version */

$this->breadcrumbs=array(
	'Versions'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List Version', 'url'=>array('index')),
	array('label'=>'Manage Version', 'url'=>array('admin')),
);
?>



<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>