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

<h1>Getting Things Done</h1>

<?php $this->renderPartial('partials/_titleMenu', array('model'=>$model)); ?>

<?php 
$this->renderPartial('issue/_gtdContainer', array('issues'=>$todoIssues, 'model' => $model, 'class' => 'alert', 'group' => 'todo'));
unset($GLOBALS['project']);
$this->renderPartial('issue/_gtdContainer', array('issues'=>$openIssues, 'model' => $model, 'class' => 'alert', 'group' => 'open'));
unset($GLOBALS['project']);
$this->renderPartial('issue/_gtdContainer', array('issues'=>$doneIssues, 'model' => $model, 'class' => 'alert  in alert-block fade alert-success', 'group' => 'done'));
?>


<script type="text/javascript">
function equalHeight(group) {
	var tallest = 0;
	
	group.each(function() {
		var thisHeight = $(this).height();
		if(thisHeight > tallest){
			tallest = thisHeight;
		}
	});
	group.height(tallest);
}

equalHeight($(".postit"));
</script>
<div class="clear"></div>
<?php $this->renderPartial('_sidebar', array('model'=>$model)); ?>
