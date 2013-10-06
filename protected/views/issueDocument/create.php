<?php
/* @var $this IssueDocumentController */
/* @var $model IssueDocument */

$this->breadcrumbs=array(
	'Issue Documents'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List IssueDocument', 'url'=>array('index')),
	array('label'=>'Manage IssueDocument', 'url'=>array('admin')),
);
?>

<h1>Create IssueDocument</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>