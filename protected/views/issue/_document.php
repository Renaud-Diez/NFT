<?php
/* @var $this DocumentController */
/* @var $model Document */
/* @var $form CActiveForm */
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
		'validateOnChange'=>false,
		),
	));
 ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->textFieldRow($model,'label',array('style'=>'width: 265px;')); ?>
	</div>

	<div class="row">
		<?php echo $form->fileFieldRow($model,'file'); ?>
	</div>
	
	<div class="row" style="margin-top:15px;">
		<?php echo $form->labelEx($model,'comment'); ?>
		<?php echo $form->textArea($model,'comment',array('style'=>'width: 265px;','rows'=>3)); ?>
		<?php echo $form->error($model,'comment'); ?>
	</div>
	
	<div class="row buttons">
		<?php 
		$this->widget('bootstrap.widgets.TbButton', array(
						    'buttonType'=>'submit',
							'label'=>$model->isNewRecord ? 'Upload Document' : 'Update Document',
						    'type'=>'primary', // null, 'primary', 'info', 'success', 'warning', 'danger' or 'inverse'
						    //'size'=>'large', // null, 'large', 'small' or 'mini'
							'block'=>true,
				));
				
				/*$this->widget('bootstrap.widgets.TbButton',array(
        		'buttonType' => 'ajaxButton',
		        'id' => 'addToProject',
		        'type' => 'primary',
				'block' => true,
		        'label' => 'Add Document',
		        //'size' => 'large',
		        'url' => $this->createURL('/Document/createOld', array('issue'=>$model->id)),
		        'disabled' => false,
		        'ajaxOptions' => array(
		                'type' => 'Post',
		                'url' => $this->createURL('/Document/createOld', array('issue'=>$model->id)),
		                //'data' => Yii::app()->request->csrfTokenName."=".Yii::app()->request->getCsrfToken()."&action=cancel&user=".$model->username,
		                'success'=>"alert(123)",
                )
));*/
		?>
		
		
	</div>
<?php $this->endWidget(); ?>

</div><!-- form -->