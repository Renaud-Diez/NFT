<?php
/* @var $this ProjectController */
/* @var $data Issue */

if($GLOBALS['project'] != $data->project_id){
	/*if(isset($GLOBALS['project']))
		echo '<hr style="border-color: rgb(217, 217, 217); border-width: 1px 0px 0px; margin-top: -5px;">';*/
	echo '<h4>'.$data->project->label.'</h4>';
}
$GLOBALS['project'] = $data->project_id;

$class = 'alert in alert-block fade alert-info';
if($data->priority >= 2)
	$class = 'alert in alert-block fade alert-danger';
?>

<div class="<?php echo $class?>">

	<b><?php echo CHtml::encode($data->getAttributeLabel('id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->id), array('/issue/view', 'id'=>$data->id)); ?>
	<b>- Posted by </b><?php echo $data->user->uname;?>
	
		<?php if(!empty($data->due_date) && $data->due_date != '0000-00-00 00:00:00'){
			echo '<div class="text-right" style="margin-top:-30px;margin-bottom:-10px;">';
			echo CHtml::encode(Yii::app()->dateFormatter->format('y-MM-d', $data->due_date));
			echo '</div>';
		}
		?>
	
	<br />
	
	<b><?php echo CHtml::encode($data->label); ?></b>
	<br />
	<?php echo $data->description; ?>
	
	<?php foreach($data->issueLogs as $log):?>
			<?php if(!empty($log->comment)):?>
			<div style="border-top: 1px dotted grey; margin-top: 10px;"> 
			<strong><?php echo $log->user->uname . ' @ ' . $log->creation_date;?></strong>
			<?php echo $log->comment;?>
			</div>
			<?php endif;?>
	<?php endforeach;?>

</div>