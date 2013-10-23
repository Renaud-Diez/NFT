<?php
/* @var $this UserController */
/* @var $model User */
/* @var $form CActiveForm */
?>

<div class="form">

<?php 	$form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
	'id'=>'issue-form',
	'type'=>'vertical',
	));

 if($model->isNewRecord)
 	$model->setScenario('update');

?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>
	
	<div class="row">
		<?php echo $form->textFieldRow($model,'username',array('size'=>45,'maxlength'=>45)); ?>
	</div>
	
	<div class="row">
		<?php echo $form->textFieldRow($model,'email',array('style'=>'width: 400px;','maxlength'=>100)); ?>
	</div>

	
	<div class="row">
		<?php echo $form->textFieldRow($model,'firstname',array('style'=>'width: 300px;','maxlength'=>45)); ?>
	</div>
	
	<div class="row">
		<?php echo $form->textFieldRow($model,'lastname',array('style'=>'width: 300px;','maxlength'=>45)); ?>
	</div>

	<?php if($model->isNewRecord):?>
	<div class="row">
		<?php echo $form->labelEx($model,'password'); ?>
		<?php echo $form->passwordField($model,'password',array('size'=>45,'maxlength'=>45)); ?>
		<?php echo $form->error($model,'password'); ?>
	</div>
	
	<div class="row">
		<?php echo $form->labelEx($model,'password_repeat'); ?>
		<?php echo $form->passwordField($model,'password_repeat',array('size'=>45,'maxlength'=>45)); ?>
		<?php echo $form->error($model,'password_repeat'); ?>
	</div>
	<?php endif;?>
	
	<div class="row">
		<?php echo $form->textFieldRow($model,'hoursbyday',array('style'=>'width: 40px;','maxlength'=>5)); ?>
	</div>
	
	<div class="row">
		<?php echo $form->textFieldRow($model,'daysbyweek',array('style'=>'width: 40px;','maxlength'=>5)); ?>
	</div>
	
	<div class="row buttons">
		<?php $this->widget('bootstrap.widgets.TbButton', array('buttonType'=>'submit', 'type'=>'primary', 'label'=> $model->isNewRecord ? 'Create' : 'Save')); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->