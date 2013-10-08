<?php
/* @var $this ProjectController */
/* @var $model Project */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
	'id'=>'issue-form',
	'type'=>'vertical',
	'enableAjaxValidation'=>true,
	));
 ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>
	
	<div class="row">
		<?php echo $form->dropDownListRow(
                    $model,
                    'topic_id', 
                    CHtml::listData(Topic::model()->findAll(),
                    'id', 
                    'label'), 
					array('empty'=>'Select Topic')) ?>
	</div>

	<div class="row">
		<?php echo $form->textFieldRow($model,'code',array('size'=>45,'maxlength'=>45)); ?>
	</div>

	<div class="row">
		<?php echo $form->textFieldRow($model,'label',array('style'=>'width: 350px;','maxlength'=>255)); ?>
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
	
	<div class="row-fluid">
		<div class="span1">
			<div class="row">
				<?php echo $form->textFieldRow($model,'allowed_effort',array('style'=>'width: 60px;','maxlength'=>150)); ?> hours
			</div>
			
			<div class="row">
				<?php echo $form->textFieldRow($model,'allowed_budget',array('style'=>'width: 60px;','maxlength'=>150)); ?> EUR
			</div>
		</div>
			
		<div class="span1">
			<div class="row">
				<?php echo $form->textFieldRow($model,'days',array('style'=>'width: 60px;','maxlength'=>150)); ?> days
			</div>
			
			<div class="row">
				<?php echo $form->textFieldRow($model,'hours',array('style'=>'width: 60px;','maxlength'=>150)); ?> hours
			</div>
		</div>
	</div>
	
	<div class="row">
			<?php echo $form->dropDownListRow($model, 'user_id', $model->getUserOptions());?>
	</div>
	<br />
	
	<div class="row buttons">
		<?php 
			$this->widget('bootstrap.widgets.TbButton', array('buttonType'=>'submit', 'type'=>'primary', 'label'=>$model->isNewRecord ? 'Create' : 'Save'));
		?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->