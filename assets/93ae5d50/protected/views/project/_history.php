<?php
/* @var $this ProjectController */
/* @var $data Project */
?>

<div class="view">

	<b>Updated by: </b>
	<?php echo CHtml::encode($data->user->username);
	 		$d1 = new DateTime($data->creation_date);
			$d2 = new DateTime(date('Y-m-d H:i:s'));
			$diff = $d1->diff($d2);
			
			echo ', ';
			if($diff->m > 0)
				echo $diff->m . ' month ago';
			elseif($diff->days > 0)
				echo $diff->days . ' month ago';
			elseif($diff->h > 0)
				echo $diff->h . ' hours ago';
			elseif($diff->i > 0)
				echo $diff->i . ' minutes ago';
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