<?php
/* @var $this TeamController */
/* @var $model Team */

$this->breadcrumbs=array(
	'Teams'=>array('index'),
	$model->label,
);

$this->menu=array(
	array('label'=>'List Team', 'url'=>array('index')),
	array('label'=>'Create Team', 'url'=>array('create')),
);
?>

<div id="statusMsg" class="flash-success" style="display:none;"></div>

<div>
<h1>Team <?php echo $model->label;?></h1>
<?php $this->renderPartial('titleMenu', array('model'=>$model)); ?>
</div>

<h2>Getting Things Done</h2>

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
