<?php
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
	'id'=>'issue-form',
	'type'=>'vertical',
	)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>
	
	<div class="row">
				<?php 
				$criteria = new CDbCriteria();
				$criteria->condition = "id !=:id";
				$criteria->params = array(':id' => $version->project_id);
				$criteria->order = 'label ASC';
				echo $form->dropDownListRow(
		                    Project::model(),
		                    'id', 
		                    CHtml::listData(Project::model()->findAll($criteria),
							'id',
							'label'),
							//array('empty'=>'Select a Type')
							array(	'prompt'=>'Select a Project',
		    						'ajax' => array(
		    						'type'=>'POST', 
		    						'url'=>CController::createUrl('loadversions'),
		    						'update'=>'#VersionRelation_target_id', 
		  							'data'=>array('project_id'=>'js:this.value'),
									)
								)
							);
							 ?>
	</div>
	
	<div class="row">
				<?php echo $form->dropDownListRow($model,'target_id', array(), array('prompt'=>'Select a project first ...'));?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'relation'); ?>
		<?php echo $form->dropDownList($model,'relation', $version->getRelatedOptions()); ?>
		<?php echo $form->error($model,'relation'); ?>
	</div>
	
	<br>

	<div class="row buttons">
		<?php $this->widget('bootstrap.widgets.TbButton', array(
						    'buttonType'=>'submit',
							'label'=>'Link',
						    'type'=>'primary', // null, 'primary', 'info', 'success', 'warning', 'danger' or 'inverse'
						    'size'=>'large', // null, 'large', 'small' or 'mini'
							'block'=>true,
				)); 
		?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->