<?php
/* @var $this VersionController */
/* @var $data Version */
?>

<div class="view">
	<div style="font-size: 150%">
	<b><?php echo CHtml::encode($data->label); ?></b>
	<br />
	</div>

	<b>Due date: </b>
	<?php echo CHtml::encode($data->due_date); ?>
	<br /><br />
	
	<b>Milestones:</b>
	<?php $this->widget('zii.widgets.CListView', $data->getMilestones($data->milestones));?>


</div>