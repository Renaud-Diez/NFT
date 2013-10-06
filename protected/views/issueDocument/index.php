<?php
/* @var $this IssueDocumentController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Issue Documents',
);

$this->menu=array(
	array('label'=>'Create IssueDocument', 'url'=>array('create')),
	array('label'=>'Manage IssueDocument', 'url'=>array('admin')),
);
?>

<h1>Issue Documents</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
