<?php
/* @var $this UserController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Users',
);
?>

<h1>Users' Activities</h1>
	
	TODO: <del>Yesterday activities (+total workload)</del> - This week activities (+total workload) - Current Priorities (TBD)
	<br />
	Activities pie diagram + Data list
	Project activities pue + Data list
	<br />
	GTD Issue List

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
