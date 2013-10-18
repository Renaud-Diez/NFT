<?php
/* @var $this ProjectRoleController */
/* @var $data ProjectRole */
?>

<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->id), array('view', 'id'=>$data->id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('project_id')); ?>:</b>
	<?php echo CHtml::encode($data->project_id); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('role')); ?>:</b>
	<?php echo CHtml::encode($data->role); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('creation_date')); ?>:</b>
	<?php echo CHtml::encode($data->creation_date); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('minimum')); ?>:</b>
	<?php echo CHtml::encode($data->minimum); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('maximum')); ?>:</b>
	<?php echo CHtml::encode($data->maximum); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('entry_date')); ?>:</b>
	<?php echo CHtml::encode($data->entry_date); ?>
	<br />


</div>