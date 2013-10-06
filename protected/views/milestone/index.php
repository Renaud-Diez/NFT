<?php
/* @var $this MilestoneController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Milestones',
);

$this->menu=array(
	array('label'=>'Create Milestone', 'url'=>array('create')),
	array('label'=>'Manage Milestone', 'url'=>array('admin')),
);
?>

<h1>Milestones</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
