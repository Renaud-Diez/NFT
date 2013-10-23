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

 	//$model->setScenario('password');
?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>
	
	<div class="row">
		<?php echo $form->textFieldRow($model,'email',array('style'=>'width: 400px;','maxlength'=>100)); ?>
	</div>

	
	<div class="row">
		<?php echo $form->textFieldRow($model,'firstname',array('style'=>'width: 400px;','maxlength'=>45)); ?>
	</div>
	
	<div class="row">
		<?php echo $form->textFieldRow($model,'lastname',array('style'=>'width: 400px;','maxlength'=>45)); ?>
	</div>
	
	<div class="row buttons">
		<?php $this->widget('bootstrap.widgets.TbButton', array('buttonType'=>'submit', 'block' => true, 'type'=>'primary', 'label'=> $model->isNewRecord ? 'Create' : 'Save')); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->