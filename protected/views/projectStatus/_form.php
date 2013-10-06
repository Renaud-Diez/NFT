<?php
/* @var $this ProjectStatusController */
/* @var $model ProjectStatus */
/* @var $form CActiveForm */
?>

<div class="form">

<?php 
$form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
	'id'=>'projectStatus-form',
	'type'=>'vertical',
	));
?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->textFieldRow($model,'label',array('size'=>60,'maxlength'=>255)); ?>
	</div>

	<div class="row">
		<?php echo $form->textFieldRow($model,'rank',array('style'=>'width: 40px;','maxlength'=>2)); ?>
	</div>

	<div class="row">
		<?php echo $form->toggleButtonRow($model,'closed_alias', array('class'=>'.pull-left','options'=>array('enabledLabel'=>'Yes' , 'disabledLabel'=>'No'))); ?>
		<?php echo $form->error($model,'closed_alias'); ?>
	</div>
	<br />
	<div class="row buttons">
		<?php $this->widget('bootstrap.widgets.TbButton', array('buttonType'=>'submit', 'type'=>'primary', 'label'=>'Submit')); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->