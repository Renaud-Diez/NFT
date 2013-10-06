<?php
/* @var $this UserController */
/* @var $data User */

if($GLOBALS['role'] != $data->projectUsers[0]->role)
	echo '<h5>'.$data->projectUsers[0]->role.' :</h5>';

$GLOBALS['role'] = $data->projectUsers[0]->role;
?>

<div class="view">
	<b><?php echo CHtml::encode($data->getAttributeLabel('username')); ?>:</b>
	<?php echo CHtml::encode($data->username, array('view', 'id'=>$data->id)); ?>
	<br />
	
	<b><?php echo CHtml::encode($data->getAttributeLabel('email')); ?>:</b>
	<?php echo CHtml::encode($data->email); ?>

	<?php
	if($model->checkAccess('Project.Setmembers'))
	{
		echo '<br />';
		echo CHtml::link(
			  "Unregister member",
		array( 'project/ajaxupdate', 'act' => 'doDelete', 'id' => $model->id, 'user_id' => $data->id ),
		array( //htmlOptions
			    'class' => 'lnkButton')
		);
	}
	?>
<br />

</div>
