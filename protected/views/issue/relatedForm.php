<?php
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'related-form',
	'enableAjaxValidation'=>true,
	//'enableClientValidation'=>true,
	'clientOptions'=>array(
		'validateOnSubmit'=>false,
		'validateOnChange'=>false,
	),
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'related_id'); ?>
		<?php echo $form->textField($model,'related_id',array('style'=>'width: 40px;','maxlength'=>7)); ?>
		<?php echo $form->error($model,'related_id'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'relation'); ?>
		<?php echo $form->dropDownList($model,'relation', $issue->getRelatedOptions()); ?>
		<?php echo $form->error($model,'relation'); ?>
	</div>
	
	<br>

	<div class="row buttons">
		<?php $this->widget('bootstrap.widgets.TbButton', array(
						    'buttonType'=>'submit',
							'label'=>'Create new Relation',
						    'type'=>'primary', // null, 'primary', 'info', 'success', 'warning', 'danger' or 'inverse'
						    'size'=>'large', // null, 'large', 'small' or 'mini'
							'block'=>true,
				)); 
		?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->