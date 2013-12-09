<?php
/* @var $this ProjectController */
/* @var $model Project */

$this->breadcrumbs=array(
	'Projects'=>array('index', 'v' => 'highlighted'),
	$model->label,
);


$this->memberMenu=array(
	array('label'=>'Manage Members', 'url'=>array('project/members', 'id'=>$model->id)),
);
?>

<div id="statusMsg" class="flash-success" style="display:none;">

</div>

<?php $this->renderPartial('_overview', array('model'=>$model)); ?>


<h2>Getting Things Done</h2>
<div style="border-top: 1px solid lightgrey; padding-top: 5px;">
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
</div>
<?php $this->renderPartial('_sidebar', array('model'=>$model)); ?>