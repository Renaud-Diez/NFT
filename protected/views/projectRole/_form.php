<?php
/* @var $this ProjectRoleController */
/* @var $model ProjectRole */
/* @var $form CActiveForm */
?>

Indicates the minimum number of profile requested for this project.
<br />

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'project-role-form',
	'enableAjaxValidation'=>true,
	//'enableClientValidation'=>true,
	'clientOptions'=>array(
		'validateOnSubmit'=>false,
		'validateOnChange'=>false,
	),
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<?php 
	$i = 1;
	$records = $model->getRoles();
	foreach($records as $record):?>
	<div class="row">
		<?php echo CHtml::textField('Min['.$i.']',$record['minimum'], array('style' => 'width: 20px;'));?>
		<?php 
			echo CHtml::hiddenField('Id['.$i.']', $record['id']).CHtml::hiddenField('Role['.$i.']', $record['name']);
			echo $record['name']; $i++;?>
	</div>
	<?php endforeach;?>

	<div class="row buttons">
		<?php 
			$this->widget('bootstrap.widgets.TbButton', array('buttonType'=>'submit', 'type'=>'primary', 'block' => true, 'label'=>'Save Project Roles'));
		?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->