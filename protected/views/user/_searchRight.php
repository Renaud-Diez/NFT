<?php
/* @var $this IssueController */
/* @var $model Issue */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm', array(
	'id' =>'search-form',
	'type'=>'vertical',
	'action'=>Yii::app()->createUrl($this->route . '/id/' . $this->user->id),
	'method'=>'get',
));

$users = CHtml::listData(User::model()->findAll(array('order'=>'username')),'id','username');
?>

	<div>
		<?php echo $form->label($model,'id'); ?>
		<?php echo $form->textField($model,'id',array('style'=>'width: 80px','maxlength'=>5)); ?>
	</div>

	<div>
		<?php echo $form->dropDownListRow(
                    $model,
                    'user_id', 
                    $users,
					 array('prompt'=>'Select a User', 'style' => 'width: 250px;'));
		?>
	</div>

	<div>
		<?php echo $form->dropDownListRow(
                    $model,
                    'status_id', 
                    CHtml::listData(IssueStatus::model()->findAll(array('order'=>'id')),
                    'id', 
                    'label'), 
					array('empty'=>'Select a Status', 'style' => 'width: 250px;')); 
		?>
	</div>

	<div>
		<?php echo $form->dropDownListRow(
                    $model,
                    'type_id', 
                    CHtml::listData(IssueType::model()->findAll(array('order'=>'id')),
                    'id', 
                    'label'), 
					array('empty'=>'Select a Type', 'style' => 'width: 250px;'));
					 ?>
	</div>

	<div>
		<?php echo $form->dropDownListRow($model,'priority', $model->getPriorities(), array('empty'=>'Select a Priority', 'style' => 'width: 250px;')); ?>
	</div>
	
	<br />

	<div class="buttons" style="margin-bottom: -10px;">
		<?php //echo CHtml::submitButton('Search'); ?>
		<?php //echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save');
				$this->widget('bootstrap.widgets.TbButton', array('buttonType'=>'submit', 'type'=>'primary', 'label'=>'Search', 'block' => true)); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- search-form -->