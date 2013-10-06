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
		<?php //echo $form->textField($model,'version_id'); ?>
		<?php $criteria = new CDbCriteria();
				$criteria->condition = "id !=:id";
				$criteria->params = array(':id' => $project->id);
				echo $form->dropDownList(
                    $model,
                    'related_id', 
                    CHtml::listData(Project::model()->findAll($criteria),
                    'id', 
                    'label'), 
					array('empty'=>'Select a Project')) ?>
		<?php echo $form->error($model,'related_id'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'relation'); ?>
		<?php echo $form->dropDownList($model,'relation', $project->getRelatedOptions()); ?>
		<?php echo $form->error($model,'relation'); ?>
	</div>
	
	<br>

	<div class="row buttons">
		<?php echo CHtml::submitButton('Create new Relation'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->