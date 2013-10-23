<?php
/* @var $this TeamController */
/* @var $data Team */
?>

<div class="view">
	<b>Team:</b>
	<?php echo CHtml::link(CHtml::encode($data->label), array('view', 'id'=>$data->id)); ?>
	<br />
</div>