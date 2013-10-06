<?php
/* @var $this ProjectController */
/* @var $data Project */
?>

<div class="view">

	<b>Updated by: </b>
	<?php 
			echo CHtml::encode($data->user->username);
			echo DateTimeHelper::timeElapse($data->creation_date);
			?>
	<br /><br />
	
	<b><?php echo CHtml::encode($data->getAttributeLabel('owner_id')); ?>:</b>
	<?php echo CHtml::encode($data->owner->username);//echo CHtml::encode($data->user_id); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('label')); ?>:</b>
	<?php echo CHtml::encode($data->label); ?>
	<br />
	
	
	<b><?php echo CHtml::encode($data->getAttributeLabel('topic_id')); ?>:</b>
	<?php echo CHtml::encode($data->topic->label); ?>
	<br /><br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('description')); ?>:</b>
	<br />
	<?php //echo CHtml::encode($data->description);
			echo $data->description ?>
	<br />



</div>