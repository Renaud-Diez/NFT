<?php
/* @var $this ProjectController */
/* @var $data Project */
?>

<div class="view">

	<b><?php echo $model->getRelatedOptions($data->relation);?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->related->label), array('view', 'id'=>$data->related_id)); ?>
	<br />


</div>