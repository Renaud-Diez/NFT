<?php
/* @var $this MilestoneController */
/* @var $data Milestone */
?>

<div>

	<?php //echo CHtml::link(CHtml::encode($data->id), array('view', 'id'=>$data->id)); ?>

	<b><?php echo CHtml::encode($data->label); ?></b>
	<br />
	

	<?php echo DateTimeHelper::timeElapse($data->due_date);?> <small><i>(<?php echo CHtml::encode(substr($data->due_date,0,10)); ?>)</i></small> - <?php echo $data->getStatusOptions($data->status); ?>
	<br />


</div>
<br />