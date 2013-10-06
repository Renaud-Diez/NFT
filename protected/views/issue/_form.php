<?php
/* @var $this IssueController */
/* @var $model Issue */
/* @var $form CActiveForm */
?>

<div class="form">

<?php /*$form=$this->beginWidget('CActiveForm', array(
	'id'=>'issue-form',
	'enableAjaxValidation'=>false,
));*/
	$form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
	'id'=>'issue-form',
	'type'=>'vertical',
	));
?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->textFieldRow($model,'label',array('style'=>'width: 400px;','maxlength'=>150)); ?>
		<?php echo $form->error($model,'label'); ?>
	</div>
	
	<div class="row">
		<?php echo $form->redactorRow($model, 'description', array('class'=>'span4', 'options'=>array('minHeight'=>200))); ?>
		<?php echo $form->error($model,'description'); ?>
	</div>
	
	<div class="row-fluid">
		<div class="span4">
		
			<div class="row">
				<?php echo $form->dropDownListRow(
		                    $model,
		                    'type_id', 
		                    $model->getAvailableType($this->project->topic_id),
							//array('empty'=>'Select a Type')
							array(	'prompt'=>'Select a Type',
		    						'ajax' => array(
		    						'type'=>'POST', 
		    						'url'=>CController::createUrl('loadstatus'),
		    						'update'=>'#Issue_status_id', 
		  							'data'=>array('type_id'=>'js:this.value'),
									)
								)
							);
							 ?>
			</div>
		
			<div class="row">
				<?php echo $form->dropDownListRow($model,'status_id', array(), array('prompt'=>'Select a type first ...'));?>
			</div>
			
			<div class="row">
				<?php echo $form->dropDownListRow($model,'assignee_id', $model->getAssignableUsers(), array('prompt'=>'Select an Assignee')); ?>
				<?php echo $form->error($model,'assignee_id'); ?>
			</div>
			
			<div class="row">
				<?php echo $form->labelEx($model,'due_date'); ?>
				<?php $this->widget('zii.widgets.jui.CJuiDatePicker',array(
			                'model'=>$model,                                // Model object
			                'attribute'=>'due_date', // Attribute name
			                'options'=>array('dateFormat' => 'yy-mm-dd'),                     // jquery plugin options
			                //'htmlOptions'=>array('readonly'=>true) // HTML options
			        ));                            
		        ?>
				<?php echo $form->error($model,'due_date'); ?>
			</div>
		
			
		</div>

		<div class="span4">
			<div class="row">
				<?php 	$versions = CHtml::listData(Version::model()->findAll(array('order'=>'due_date ASC', 
		                    												'condition'=>'project_id=:project_id', 
		                    												'params'=>array('project_id'=>$model->project_id))),
		                    											'id', 
		                    											'label');
						echo $form->dropDownListRow(
		                    $model,
		                    'version_id', 
		                    $versions,
		                    array(	'prompt'=>'Select a Project Version',
		    						'ajax' => array(
		    						'type'=>'POST', 
		    						'url'=>CController::createUrl('loadmilestones'),
		    						'update'=>'#Issue_milestone_id', 
		  							'data'=>array('version_id'=>'js:this.value'),
									)
								)
		  					);
							 ?>
				<?php echo $form->error($model,'version_id'); ?>
			</div>
			
		<div class="row">
			<?php echo $form->dropDownListRow($model,'milestone_id', array(), array('prompt'=>'Select Milestone'));?>
			<?php echo $form->error($model,'milestone_id'); ?>
		</div>
	
		<div class="row">
			<?php echo $form->dropDownListRow($model,'priority', $model->getPriorities()); ?>
			<?php echo $form->error($model,'priority'); ?>
		</div>
		
			<div class="row-fluid">
				<div class="span3">
					<div class="row">
						<?php echo $form->textFieldRow($model,'estimated_time',array('style'=>'width: 30px;','maxlength'=>5)); ?> hours
						<?php echo $form->error($model,'estimated_time'); ?>
					</div>
				</div>
				<div class="span3">
					<div class="row">
						<?php echo $form->textFieldRow($model,'completion',array('style'=>'width: 30px;','maxlength'=>3)); ?> %
						<?php echo $form->error($model,'completion'); ?>
					</div>
				</div>
				<div class="span3">
					<div class="row">
						<?php echo $form->toggleButtonRow($model,'private', array('class'=>'.pull-left','options'=>array('enabledLabel'=>'Yes' , 'disabledLabel'=>'No'))); ?>
						<?php echo $form->error($model,'private'); ?>
					</div>
				</div>
			</div>
		</div>
	
	</div>
	
	<br />

	<div class="row buttons">
		<?php //echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save');
				$this->widget('bootstrap.widgets.TbButton', array('buttonType'=>'submit', 'type'=>'primary', 'label'=>'Submit')); ?>
	</div>

<?php $this->endWidget(); ?>

<script type="text/javascript">
$(document).ready(function(){
  $.ajax({
    type: 'POST',
    data: {version_id: $('#Issue_version_id').val()},
    url: '<?php echo CController::createUrl('loadmilestones') ?>',
    success: function(data){
                $('#Issue_milestone_id').html(data)
            }
  })
})
</script>
</div><!-- form -->