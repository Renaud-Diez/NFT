<?php
/* @var $this ProjectController */
/* @var $model Project */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'project-form',
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>
	
	<div class="row">
		<?php echo $form->labelEx($model,'topic_id'); ?>
		<?php //echo $form->textField($model,'topic_id');
				echo $form->dropDownList(
                    $model,
                    'topic_id', 
                    CHtml::listData(Topic::model()->findAll(),
                    'id', 
                    'label'), 
					array('empty'=>'Select Topic')) ?>
		<?php echo $form->error($model,'topic_id'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'code'); ?>
		<?php echo $form->textField($model,'code',array('size'=>45,'maxlength'=>45)); ?>
		<?php echo $form->error($model,'code'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'label'); ?>
		<?php echo $form->textField($model,'label',array('size'=>45,'maxlength'=>45)); ?>
		<?php echo $form->error($model,'label'); ?>
	</div>

	<div class="row" id="editor">
		<?php echo $form->labelEx($model,'description'); ?>
		
		<?php /*$this->widget('ext.editMe.widgets.ExtEditMe', array(
    'name'=>'example',
    'value'=>'',
    'optionName'=>'optionValue',
));*/
/*$this->widget('ext.editMe.widgets.ExtEditMe', array(
    'name'=>'example',
    'value'=>'',
	'toolbar' => array(
							array('PasteText', 'PasteFromWord', '-', 'Undo', 'Redo', '-', 'Bold', 'Italic', 'Underline', 'Strike', 'RemoveFormat','-','TextColor', 'BGColor'),
							array('Format', 'Font', 'FontSize','Image','Link', 'Unlink')
						)
));*/
$this->widget('ext.editMe.widgets.ExtEditMe', array(
    'model'=>$model,
    'attribute'=>'description',
	'toolbar' => array(
							array('PasteText', 'PasteFromWord', '-', 'Undo', 'Redo', '-', 'Bold', 'Italic', 'Underline', 'Strike', 'RemoveFormat','-','TextColor', 'BGColor'),
							array('Format', 'Font', 'FontSize','Image','Link', 'Unlink')
						)
));?>
		
		<?php //echo $form->textArea($model,'description',array('rows'=>6, 'cols'=>50)); ?>
		<?php echo $form->error($model,'description'); ?>
	</div>
	
		
	
	<div class="row">
		<?php echo $form->labelEx($model,'user_id'); ?>
		<?php echo $form->dropDownList($model, 'user_id', $model->getUserOptions());?>
		<?php echo $form->error($model,'user_id'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->