<?php
/* @var $this IssueTransitionController */
/* @var $data IssueTransition */
?>

<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->id), array('view', 'id'=>$data->id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('issue_id')); ?>:</b>
	<?php echo CHtml::encode($data->issue_id); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('action')); ?>:</b>
	<?php echo CHtml::encode($data->action); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('project_id')); ?>:</b>
	<?php echo CHtml::encode($data->project_id); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('version_id')); ?>:</b>
	<?php echo CHtml::encode($data->version_id); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('milestone_id')); ?>:</b>
	<?php echo CHtml::encode($data->milestone_id); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('comment')); ?>:</b>
	<?php echo CHtml::encode($data->comment); ?>
	<br />


</div>