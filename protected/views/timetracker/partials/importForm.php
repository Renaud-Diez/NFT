<?php
/* @var $this TimetrackerController */
/* @var $model FileImport */
/* @var $form CActiveForm */
?>

<div class="form">

<?php 
	$form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
	'id'=>'import-form',
	'type'=>'search',
	'htmlOptions' => array('enctype' => 'multipart/form-data'),
	'enableAjaxValidation'=>true,
	'clientOptions'=>array(
		'validateOnSubmit'=>false,
		'validateOnChange'=>false,
		),
	));
 ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

		<?php echo $form->fileFieldRow($model,'file'); ?>

		<?php 
			$this->widget('bootstrap.widgets.TbButton', array(
						    'buttonType'=>'submit',
							'label'=>'Upload File',
						    'type'=>'primary',
				));
			$this->endWidget();
		?>
</div><!-- form -->