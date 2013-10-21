<?php
/* @var $this IssueTransitionController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Issue Transitions',
);

$this->menu=array(
	array('label'=>'Create IssueTransition', 'url'=>array('create')),
	array('label'=>'Manage IssueTransition', 'url'=>array('admin')),
);
?>

<h1>Issue Transitions</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
