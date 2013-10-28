<?php
/* @var $this TimetrackerController */
/* @var $model Timetracker */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm', array(
	'id'=>'timetracker-form',
	'enableAjaxValidation'=>true,
	//'enableClientValidation'=>true,
	'clientOptions'=>array(
		'validateOnSubmit'=>false,
		'validateOnChange'=>false,
	),
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<p class="note">Time spent & Remaining time are expressed in hours</p>
	<div class="row-fluid">
		<div class="span4">
			<div class="row">
				<?php echo $form->textFieldRow($model,'time_spent',array('style'=>'width: 40px;','maxlength'=>6)); ?>
			</div>
		</div>
		<div class="span4">
			<div class="row">
				<?php echo $form->textFieldRow($model,'remaining_time',array('style'=>'width: 40px;','maxlength'=>6)); ?>
			</div>
		</div>
	</div>

	<div class="row">
		<?php echo $form->dropDownListRow($model,'activity_id', CHtml::listData(TimeActivity::model()->findAll(array('order' => 'label ASC')), 'id', 'label'), array('prompt'=>'Select an Activity')); ?>
	</div>

	<div class="row">
		<?php echo $form->textAreaRow(
			$model,
			'comment',
			array('class' => 'span5', 'rows' => 3)
			); ?>
	</div>
	
	<div class="row">
		<?php echo $form->toggleButtonRow($model,'billable', array('class'=>'.pull-left','options'=>array('enabledLabel'=>'Yes' , 'disabledLabel'=>'No'))); ?>
	</div>
	<br />

	<div class="row buttons">
		<?php //echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
		<?php 
		$this->widget('bootstrap.widgets.TbButton', array(
						    'buttonType'=>'submit',
							'label'=>'Log Time',
						    'type'=>'primary', // null, 'primary', 'info', 'success', 'warning', 'danger' or 'inverse'
						    'size'=>'large', // null, 'large', 'small' or 'mini'
							'block'=>true,
				)); 
		?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->