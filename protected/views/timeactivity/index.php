<?php
/* @var $this TimeActivityController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Time Activities',
);

$this->menu=array(
	array('label'=>'Create TimeActivity', 'url'=>array('create')),
	array('label'=>'Manage TimeActivity', 'url'=>array('admin')),
);
?>

<h1>Time Activities</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
