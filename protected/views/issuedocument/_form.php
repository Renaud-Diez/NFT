<?php
/* @var $this IssueDocumentController */
/* @var $model IssueDocument */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'issue-document-form',
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'issue_id'); ?>
		<?php echo $form->textField($model,'issue_id'); ?>
		<?php echo $form->error($model,'issue_id'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'document_id'); ?>
		<?php echo $form->textField($model,'document_id'); ?>
		<?php echo $form->error($model,'document_id'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->