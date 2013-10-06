<?php
/* @var $this IssueController */
/* @var $model Issue */

$this->breadcrumbs=array(
	'Project'=>array('/project'),
	$model->project->label=>array('/project/view', 'id'=>$model->project_id),
	'Issues'=>array('/project/issues', 'id'=>$model->project_id),
	'Create',
);

$this->menu=array(
	array('label'=>'List Issue', 'url'=>array('index')),
	array('label'=>'Manage Issue', 'url'=>array('admin')),
);
?>

<h1>Create Issue</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model,'relation'=>$relation)); ?>