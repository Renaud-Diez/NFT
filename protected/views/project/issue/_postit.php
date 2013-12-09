<?php
/* @var $this ProjectController */
/* @var $data Issue */

//if(empty($model->id) && $GLOBALS['project'] != $data->project_id){
if($GLOBALS['project'] != $data->project_id){
	if(isset($GLOBALS['project']))
		echo '<hr style="border-color: rgb(217, 217, 217); border-width: 1px 0px 0px; margin-top: -5px;">';
	echo '<h4>'.$data->project->label.'</h4>';
}
$GLOBALS['project'] = $data->project_id;

$class = 'alert';
if($data->assignee_id == Yii::app()->user->id)
	$class = 'alert  in alert-block fade alert-info';
elseif($data->status->alias == 3)
	$class = 'alert  in alert-block fade alert-success';
?>

<div class="<?php echo $class?>">

	<b><?php echo CHtml::encode($data->getAttributeLabel('id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->id), array('/issue/view', 'id'=>$data->id)); ?>
	
		<?php if(!empty($data->due_date) && $data->due_date != '0000-00-00 00:00:00'){
			echo '<div class="text-right" style="margin-top:-30px;margin-bottom:-10px;">';
			echo CHtml::encode(Yii::app()->dateFormatter->format('y-MM-d', $data->due_date));
			echo '</div>';
		}
		?>
	
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('assignee_id')); ?>:</b>
	<?php echo CHtml::encode($data->assignee->username); ?>
	<br />
	
	<?php echo CHtml::encode($data->label); ?>

</div>