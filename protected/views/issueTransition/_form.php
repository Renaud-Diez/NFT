<?php
/* @var $this IssueTransitionController */
/* @var $model IssueTransition */
/* @var $form CActiveForm */
?>

<div class="form">
<?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm', array(
	'id'=>'issue-transition-form',
	'enableAjaxValidation'=>true,
	//'enableClientValidation'=>true,
	'clientOptions'=>array(
		'validateOnSubmit'=>false,
		'validateOnChange'=>false,
	),
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>
	
	<div class="">
		<?php echo $form->textFieldRow($model,'label',array('style'=>'width: 350px;','maxlength'=>150)); ?>
	</div>

	<div class="row">
			<?php echo $form->dropDownListRow($model,'action', $model->getApplicationOptions(), array('prompt'=>'Select an Action'));?>
	</div>

	<div class="row">
				<?php 	//$model->getProjects();
						//Project::model()->findAll($criteria);
				
						/*$projects = CHtml::listData(Project::model()->findAll($criteria),
		                    											'id', 
		                    											'label');*/
						echo $form->dropDownListRow(
		                    $model,
		                    'project_id', 
		                    $model->getProjects(),
		                    array(	'prompt'=>'Select a Project',
		                    		'onChange'=>"js:;$('#IssueTransition_milestone_id').html(\"<option value=''>Select a Milestone</option>\");",
		    						'ajax' => array(
		    						'type'=>'POST', 
		    						'url'=>CController::createUrl('loadversions'),
		    						'update'=>'#IssueTransition_version_id', 
		  							'data'=>array('project_id'=>'js:this.value'),
									)
								)
		  					);
				?>
	</div>

	<div class="row">
				<?php 	/*$versions = CHtml::listData(Version::model()->findAll(array('order'=>'due_date ASC', 
		                    												'condition'=>'project_id=:project_id', 
		                    												'params'=>array('project_id'=>$model->project_id))),
		                    											'id', 
		                    											'label');*/
						echo $form->dropDownListRow(
		                    $model,
		                    'version_id', 
		                    array(),
		                    array(	'prompt'=>'Select a Version',
		    						'ajax' => array(
		    						'type'=>'POST', 
		    						'url'=>CController::createUrl('loadmilestones'),
		    						'update'=>'#IssueTransition_milestone_id', 
		  							'data'=>array('version_id'=>'js:this.value'),
									)
								)
		  					);
				?>
	</div>

	<div class="row">
			<?php echo $form->dropDownListRow($model,'milestone_id', array(), array('prompt'=>'Select a Milestone'));?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'comment'); ?>
		<?php echo $form->textArea($model,'comment',array('style'=>'width: 350px;', 'cols'=>50)); ?>
		<?php echo $form->error($model,'comment'); ?>
	</div>
	
		<div class="row buttons">
		<?php $title = 'Create a new Milestone';
		if(isset($model->id))
			$title = 'Update Milestone';
		 $this->widget('bootstrap.widgets.TbButton', array(
						    'buttonType'=>'submit',
							'label'=> $model->isNewRecord ? 'Create Transition' : 'Save Transition',
						    'type'=>'primary', // null, 'primary', 'info', 'success', 'warning', 'danger' or 'inverse'
						    'size'=>'large', // null, 'large', 'small' or 'mini'
							'block'=>true,
				)); 
		?>
	</div>

<?php $this->endWidget(); ?>

<script type="text/javascript">
$(document).ready(function(){
  $.ajax({
    type: 'POST',
    data: {version_id: $('#IssueTransition_version_id').val() <?php if(!empty($model->milestone_id)){echo ', milestone_id:'.$model->milestone_id;}?>},
    url: '<?php echo CController::createUrl('loadmilestones') ?>',
    success: function(data){
                $('#IssueTransition_milestone_id').html(data)
            }
  });
  $.ajax({
	    type: 'POST',
	    data: {project_id: $('#IssueTransition_project_id').val() <?php if(!empty($model->version_id)){echo ', version_id:'.$model->version_id;}?>},
	    url: '<?php echo CController::createUrl('loadversions') ?>',
	    success: function(data){
	                $('#IssueTransition_version_id').html(data)
	            }
	  })
})
</script>

</div><!-- form -->