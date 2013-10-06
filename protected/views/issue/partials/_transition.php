<?php
/* @var $this IssueController */
/* @var $data IssueTransition */
?>

<div class="view">

	<?php //echo CHtml::link(CHtml::encode($data->label), array('view', 'id'=>$data->id)); ?>
	<a href="#" onclick=';updateJS("/issueTransition/update/id/<?php echo $data->id;?>/issue/<?php echo $model->id?>", "Issue Transition");$("#dialogModal").dialog("open");return false;'>
	<?php echo CHtml::encode($data->label);?>
	</a>
	<br />
	
</div>