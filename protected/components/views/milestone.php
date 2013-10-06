<?php
/* @var $this MilestoneController */
/* @var $model Milestone */
/* @var $form CActiveForm */
?>

<div class="form">

<?php /*$form=$this->beginWidget('CActiveForm', array(
	'id'=>'milestone-form',
	'enableAjaxValidation'=>false,
));*/ ?>
<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'milestone-form',
	'enableAjaxValidation'=>true,
	//'enableClientValidation'=>true,
	'clientOptions'=>array(
		'validateOnSubmit'=>true,
		'validateOnChange'=>false,
	),
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'label'); ?>
		<?php echo $form->textField($model,'label',array('size'=>45,'maxlength'=>45)); ?>
		<?php echo $form->error($model,'label'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'version_id'); ?>
		<?php //echo $form->textField($model,'version_id'); ?>
		<?php echo $form->dropDownList($model,'version_id',$model->getVersionOptions());?>
		<?php echo $form->error($model,'version_id'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'due_date'); ?>
		<?php $this->widget('zii.widgets.jui.CJuiDatePicker',array(
					'id'=>'milestonedate',
	                'model'=>$model,                                // Model object
	                'attribute'=>'due_date', 						// Attribute name
	                'options'=>array('dateFormat' => 'yy-mm-dd'),   // jquery plugin options
	                //'htmlOptions'=>array('readonly'=>true) 		// HTML options
	        ));                            
        ?>
		<?php echo $form->error($model,'due_date'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'status'); ?>
		<?php echo $form->dropDownList($model,'status', $model->getStatusOptions()); ?>
		<?php echo $form->error($model,'status'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton('New Milestone');
				//echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save', array('onclick'=> 'js:function{alert(123);'));
				//echo CHtml::ajaxSubmitButton('Create Miletsone',null, array());
		?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->