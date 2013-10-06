<?php
/* @var $this VersionController */
/* @var $data Version */
?>

<div class="view">
	<div style="font-size: 150%">
	<b><?php echo CHtml::encode($data->label); ?></b>
	<br />
	
		<div class="text-right" style="margin-top:-20px;padding-bottom: 10px;">
			<a href="<?php echo CController::createUrl('roadmap', array('id'=>$data->project_id, 'version'=>$data->id))?>">
			<i class="icon-eye-open"></i>
			</a>
			<a href="#" onClick="<?php echo ";updateJS('/index.php/version/update/".$data->id."', 'Update Version');$('#dialogModal').dialog('open'); return false;"?>">
				<i class="icon-pencil"></i>
			</a>
		</div>
	
	</div>

	<b>Due date: </b>
	<?php echo DateTimeHelper::timeElapse($data->due_date);?>
	<small><i>(<?php echo CHtml::encode(substr($data->due_date,0,10)); ?>)</i></small>
	<br /><br />
	
	<b>Milestones:</b>
	<?php $this->widget('zii.widgets.CListView', $data->getMilestones($model, $data->milestones));?>


</div>