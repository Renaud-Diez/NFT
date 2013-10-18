<?php
/* @var $this IssueController */
/* @var $model Issue */
/* @var $form CActiveForm */
?>

<div class="wide form">

<?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm', array(
	'id' =>'search-form',
	'action'=>Yii::app()->createUrl($this->route),
	'method'=>'get',
));
$users = CHtml::listData(User::model()->findAll(array('order'=>'id')),'id','username');
?>

	<div>
		<?php echo $form->label($model,'id'); ?>
		<?php echo $form->textField($model,'id'); ?>
	</div>

	<div>
		<?php echo $form->label($model,'label'); ?>
		<?php echo $form->textField($model,'label',array('size'=>60,'maxlength'=>150)); ?>
	</div>

	<div>
		<?php echo $form->dropDownListRow(
                    $model,
                    'user_id', 
                    $users,
					 array('prompt'=>'Select a User'));
		?>
	</div>

	<div>
		<?php echo $form->dropDownListRow(
                    $model,
                    'assignee_id', 
                    $users,
					 array('prompt'=>'Select an Assignee'));
		?>
	</div>

	<div>
		<?php echo $form->dropDownListRow(
                    $model,
                    'status_id', 
                    CHtml::listData(IssueStatus::model()->findAll(array('order'=>'id')),
                    'id', 
                    'label'), 
					array('empty'=>'Select a Status')); 
		?>
	</div>

	<div>
		<?php echo $form->dropDownListRow(
                    $model,
                    'type_id', 
                    CHtml::listData(IssueType::model()->findAll(array('order'=>'id')),
                    'id', 
                    'label'), 
					array('empty'=>'Select a Type'));
					 ?>
	</div>

	<div>
		<?php echo $form->dropDownListRow($model,'priority', $model->getPriorities(), array('empty'=>'Select a Priority')); ?>
	</div>

	<div>
		<?php echo $form->toggleButtonRow($model,'private', array('options'=>array('enabledLabel'=>'Yes' , 'disabledLabel'=>'No'))); ?>
	</div>
	
	<br />

	<div class="buttons">
		<?php //echo CHtml::submitButton('Search'); ?>
		<?php //echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save');
				$this->widget('bootstrap.widgets.TbButton', array('buttonType'=>'submit', 'type'=>'primary', 'label'=>'Search')); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- search-form -->