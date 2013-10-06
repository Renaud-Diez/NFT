<?php
/* @var $this MilestoneController */
/* @var $data Milestone */
?>

<div>

	<?php //echo CHtml::link(CHtml::encode($data->id), array('view', 'id'=>$data->id)); ?>

	<b><?php echo CHtml::encode($data->label); ?></b>
	<br />
	

	<?php echo CHtml::encode($data->due_date); ?> - <?php echo CHtml::encode($data->status); ?>
	<br />


</div>
<br />