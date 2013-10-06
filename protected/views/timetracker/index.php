<?php
/* @var $this TimetrackerController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Timetrackers',
);

$this->menu=array(
	array('label'=>'Create Timetracker', 'url'=>array('create')),
	array('label'=>'Manage Timetracker', 'url'=>array('admin')),
);
?>

<h1>Timetrackers</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
