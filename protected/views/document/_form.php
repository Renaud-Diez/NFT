<?php
/* @var $this DocumentController */
/* @var $model Document */
/* @var $form TbActiveForm */
?>

<div class="form">

<?php 
	/*$form=$this->beginWidget('CActiveForm', array(
	'id'=>'document-form',
	'enableAjaxValidation'=>false,
	'htmlOptions' => array('enctype' => 'multipart/form-data'),
	));*/

	$form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
	'id'=>'document-form',
	'type'=>'vertical',
	'htmlOptions' => array('enctype' => 'multipart/form-data'),
	'enableAjaxValidation'=>true,
	//'enableClientValidation'=>true,
	'clientOptions'=>array(
		'validateOnSubmit'=>false,
		'validateOnChange'=>true,
		),
	));
 ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'label'); ?>
		<?php echo $form->textField($model,'label',array('size'=>45,'maxlength'=>45)); ?>
		<?php echo $form->error($model,'label'); ?>
	</div>

	<div class="row">
		<?php echo $form->fileFieldRow($model,'file'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'comment'); ?>
		<?php echo $form->textArea($model,'comment',array('rows'=>6, 'cols'=>50)); ?>
		<?php echo $form->error($model,'comment'); ?>
	</div>

	<div class="row buttons">
		<?php 
		$this->widget('bootstrap.widgets.TbButton', array(
						    'buttonType'=>'submit',
							'label'=>$model->isNewRecord ? 'Upload Document' : 'Update Document',
						    'type'=>'primary', // null, 'primary', 'info', 'success', 'warning', 'danger' or 'inverse'
						    'size'=>'large', // null, 'large', 'small' or 'mini'
							'block'=>true,
				));
		
		/*echo CHtml::ajaxSubmitButton(Yii::t('job','Upload Document'),CHtml::normalizeUrl(array('Document/createOld','render'=>false)),array('success'=>'js: function(data) {
                        $("#fileDialog").dialog("close");
                    }'),array('id'=>'closeJobDialog'));*/
		?>
		
		
	</div>
<?php $this->endWidget(); ?>

</div><!-- form -->