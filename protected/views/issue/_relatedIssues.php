<?php
/* @var $this ProjectController */
/* @var $data Project */
if(in_array($data->relation,array(1,2)))
	$class = 'alert in alert-block fade alert-warning';
elseif(in_array($data->relation,array(3,4)))
	$class = 'alert in alert-block fade alert-error';
elseif(in_array($data->relation,array(5,6)))
	$class = 'alert in alert-block fade alert-info';
else
	$class = 'alert in alert-block fade alert-success';
?>

<div class="<?php echo $class;?>">
	<b><?php echo $model->getRelatedOptions($data->relation);?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->related->label), array('view', 'id'=>$data->related_id)); ?>
</div>