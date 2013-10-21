<?php
/* @var $this IssueStatusController */
/* @var $model IssueStatus */
/* @var $form CActiveForm */
?>

<div class="form">

<?php 
	$form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
	'id'=>'issue-status-form',
	'type'=>'vertical',
	));
?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->textFieldRow($model,'label',array('size'=>45,'maxlength'=>45)); ?>
	</div>

	<div class="row">
		<?php echo $form->textFieldRow($model,'rank',array('style'=>'width: 30px;','maxlength'=>2)); ?>
	</div>
	
	<div class="row">
		<?php echo $form->dropDownListRow($model, 'alias', $model->getAlias());?>
	</div>

	<div class="row">
		<?php echo $form->toggleButtonRow($model,'closed_alias', array('class'=>'.pull-left','options'=>array('enabledLabel'=>'Yes' , 'disabledLabel'=>'No'))); ?>
	</div>
	<br />

	<div class="row buttons">
		<?php $this->widget('bootstrap.widgets.TbButton', array('buttonType'=>'submit', 'type'=>'primary', 'label'=>'Submit')); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->