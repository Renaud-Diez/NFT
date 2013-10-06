<?php
/* @var $this UserController */
/* @var $data User */
?>

<div class="view">
	<b><?php echo CHtml::encode($data->getAttributeLabel('username')); ?>:</b>
	<?php echo CHtml::encode($data->username, array('view', 'id'=>$data->id)); ?>
	-
	<?php
	echo CHtml::link(
		  "unregister",
	array( 'project/ajaxupdate', 'act' => 'doDelete', 'id' => 1, 'user_id' => $data->id ),
	array( //htmlOptions
		    'class' => 'lnkButton')
	);
	?>
<br />

</div>
