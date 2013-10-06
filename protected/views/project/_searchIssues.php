<?php
/* @var $this IssueController */
/* @var $model Issue */
/* @var $form CActiveForm */
?>

<div class="wide form">

<?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm', array(
	'id' =>'search-form',
	'action'=>Yii::app()->createUrl($this->route, array("id"=>$project->id)),
	'method'=>'get',
));

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
		<?php echo $form->label($model,'user_id'); ?>
		<?php echo $form->textField($model,'user_id'); ?>
	</div>

	<div>
		<?php echo $form->dropDownListRow(
                    $model,
                    'assignee_id', 
                    $model->getAssignableUsers($project->id),
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
		<?php 	$versions = CHtml::listData(Version::model()->findAll(array('order'=>'due_date ASC', 
                    												'condition'=>'project_id=:project_id', 
                    												'params'=>array('project_id'=>$project->id))),
                    											'id', 
                    											'label');
				echo $form->dropDownListRow(
                    $model,
                    'version_id', 
                    $versions,
                    array(	//'empty'=>'Select a Project Version',
    						'prompt'=>'Select a Project Version',
    						'ajax' => array(
    						'type'=>'POST', 
    						'url'=>CController::createUrl('issue/loadmilestones'),
    						'update'=>'#Issue_milestone_id', 
  							'data'=>array('version_id'=>'js:this.value'),
							)
						)
  					, 
					array('empty'=>'Select a Type'));
					 ?>
	</div>

	<div>
		<?php echo $form->dropDownListRow($model,'milestone_id', array(), array('prompt'=>'Select Milestone')); ?>
	</div>

	<div>
		<?php echo $form->dropDownListRow($model,'priority', $model->getPriorities(), array('empty'=>'Select a Priority')); ?>
	</div>

	<div>
		<?php echo $form->textFieldRow($model,'estimated_time',array('style'=>'width: 30px;','maxlength'=>2)); ?> hours
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