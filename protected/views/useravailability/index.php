<?php
/* @var $this UserAvailabilityController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'User Availabilities',
);

$this->menu=array(
	array('label'=>'Create UserAvailability', 'url'=>array('create')),
	array('label'=>'Manage UserAvailability', 'url'=>array('admin')),
);
?>

<h1>User Availabilities</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
