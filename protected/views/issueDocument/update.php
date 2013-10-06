<?php
/* @var $this IssueDocumentController */
/* @var $model IssueDocument */

$this->breadcrumbs=array(
	'Issue Documents'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List IssueDocument', 'url'=>array('index')),
	array('label'=>'Create IssueDocument', 'url'=>array('create')),
	array('label'=>'View IssueDocument', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage IssueDocument', 'url'=>array('admin')),
);
?>

<h1>Update IssueDocument <?php echo $model->id; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>