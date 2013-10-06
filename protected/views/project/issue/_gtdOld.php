<?php
/* @var $this ProjectController */
/* @var $data Issue */

if($GLOBALS['type'] != $data->status->alias){
	if($GLOBALS['divType'] == true)
		echo '</div>';
		
	//echo '<tr><td style="border-top: 1px solid rgb(217, 217, 217);"><h4>' . $data->status->getAlias($data->status->alias) . '</h4></td></tr><tr><td>';
	echo '<div class="postit" style="float: left; width: 250px; padding: 4px; margin-right: 10px; border-radius: 8px; border: 1px solid rgb(217, 217, 217);"><h4>'.$data->status->getAlias($data->status->alias).'</h4><hr style="border-color: rgb(217, 217, 217); border-width: 1px 0px 0px; margin-top: -5px;">';
	
	$GLOBALS['divType'] = true;
	$GLOBALS['type'] = $data->status->alias;
	$GLOBALS['kbGroups'][] = $data->status->id;
}

$GLOBALS['status'] = $data->status->label;
$GLOBALS['type'] = $data->status->alias;
?>

<div class="alert">

	<b><?php echo CHtml::encode($data->getAttributeLabel('id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->id), array('/issue/view', 'id'=>$data->id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('assignee_id')); ?>:</b>
	<?php echo CHtml::encode($data->assignee->username); ?>
	<br />
	
	<?php echo CHtml::encode($data->label); ?>

</div>