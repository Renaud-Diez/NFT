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
		'validateOnSubmit'=>false,
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
		<?php echo $form->labelEx($model,'start_date'); ?>
		<?php $this->widget('zii.widgets.jui.CJuiDatePicker',array(
					'id'=>'startdate',
	                'model'=>$model,                                // Model object
	                'attribute'=>'start_date', 						// Attribute name
	                'options'=>array('dateFormat' => 'yy-mm-dd'),   // jquery plugin options
	                //'htmlOptions'=>array('readonly'=>true) 		// HTML options
	        ));                            
        ?>
		<?php echo $form->error($model,'start_date'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'due_date'); ?>
		<?php $this->widget('zii.widgets.jui.CJuiDatePicker',array(
					'id'=>'duedate',
	                'model'=>$model,                                // Model object
	                'attribute'=>'due_date', 						// Attribute name
	                'options'=>array('dateFormat' => 'yy-mm-dd'),   // jquery plugin options
	                //'htmlOptions'=>array('readonly'=>true) 		// HTML options
	        ));                            
        ?>
		<?php echo $form->error($model,'due_date'); ?>
	</div>
	
	<div class="row">
		<?php echo $form->labelEx($model,'version_id'); ?>
		<?php //echo $form->textField($model,'version_id'); ?>
		<?php echo $form->dropDownList($model,'version_id',$model->getVersionOptions($model->project_id));?>
		<?php echo $form->error($model,'version_id'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'status'); ?>
		<?php echo $form->dropDownList($model,'status', $model->getStatusOptions()); ?>
		<?php echo $form->error($model,'status'); ?>
	</div>
	
	<br>

	<div class="row buttons">
		<?php $title = 'Create a new Milestone';
		if(isset($model->id))
			$title = 'Update Milestone';
		 $this->widget('bootstrap.widgets.TbButton', array(
						    'buttonType'=>'submit',
							'label'=> $title,
						    'type'=>'primary', // null, 'primary', 'info', 'success', 'warning', 'danger' or 'inverse'
						    'size'=>'large', // null, 'large', 'small' or 'mini'
							'block'=>true,
				)); 
		?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->