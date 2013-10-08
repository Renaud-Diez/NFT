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
		<?php echo $form->redactorRow($model, 'comment', array('class'=>'span4', 'options'=>array('minHeight'=>200))); ?>
		<?php echo $form->error($model, 'comment'); ?>
	</div>
	
	<div class="row-fluid">
		<div class="span4">
		
			<div class="row">
				<?php /*echo $form->dropDownListRow(
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
							);*/
						echo $form->hiddenField($model, 'type_id');
							 ?>
			</div>
		
			<div class="row">
				<?php echo $form->dropDownListRow($model,'status_id', array(), array('prompt'=>'Select a type first ...'));?>
			</div>
			
			<div class="row">
				<?php echo $form->dropDownListRow($model,'assignee_id', $model->getAssignableUsers(), array('prompt'=>'Select an Assignee')); ?>
				<?php echo $form->error($model,'assignee_id'); ?>
			</div>
			
			<div class="row-fluid">
				<div class="span3">
					<div class="row">
						<?php echo $form->textFieldRow($model,'estimated_time',array('style'=>'width: 30px;','maxlength'=>2)); ?> hours
						<?php echo $form->error($model,'estimated_time'); ?>
					</div>
				</div>
				<div class="span3">
					<div class="row">
						<?php echo $form->textFieldRow($model,'completion',array('style'=>'width: 30px;','maxlength'=>3)); ?> %
						<?php echo $form->error($model,'completion'); ?>
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
	    data: {type_id: $('#Issue_type_id').val() <?php if(!empty($model->status_id)){echo ', status_id:'.$model->status_id;}?>},
	    url: '<?php echo CController::createUrl('loadstatus') ?>',
	    success: function(data){
	                $('#Issue_status_id').html(data)
	            }
	  })
})
</script>
</div><!-- form -->