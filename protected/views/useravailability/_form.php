<?php
/* @var $this UserAvailabilityController */
/* @var $model UserAvailability */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'user-availability-form',
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'user_id'); ?>
		<?php echo $form->textField($model,'user_id'); ?>
		<?php echo $form->error($model,'user_id'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'hoursbyday'); ?>
		<?php echo $form->textField($model,'hoursbyday',array('size'=>2,'maxlength'=>2)); ?>
		<?php echo $form->error($model,'hoursbyday'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'daysbyweek'); ?>
		<?php echo $form->textField($model,'daysbyweek',array('size'=>2,'maxlength'=>2)); ?>
		<?php echo $form->error($model,'daysbyweek'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->