<?php
/* @var $this ProjectController */
/* @var $model Issue */

$this->breadcrumbs=array(
	'Project'=>array('index'),
	$model->label=>array('view','id'=>$model->id),
	'Issues',
);

$this->issueMenu=array(
	array('label'=>'Create Issue', 'url'=>array('issue/create', 'pid'=>$model->id)),
	array('label'=>'View Issues', 'url'=>array('issues', 'id'=>$model->id)),
);


Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$('#issue-grid').yiiGridView('update', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<h1>Treeview</h1>


<?php 
$this->widget('CTreeView',array(
        'data'=>$treedata,
        'animated'=>'fast', //quick animation
        'collapsed'=>false,//remember must giving quote for boolean value in here
        'htmlOptions'=>array(
                //'class'=>'treeview-famfamfam',//there are some classes that ready to use
                'class' => 'filetree',
        ),
));
?>

<?php $this->renderPartial('_sidebar', array('model'=>$model)); ?>
