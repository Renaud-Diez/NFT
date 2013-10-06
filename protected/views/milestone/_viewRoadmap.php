<?php
/* @var $this MilestoneController */
/* @var $data Milestone */
?>

<div class="view">

	<div>
		<big><?php echo CHtml::link(CHtml::encode($data->label), array('project/roadmap', 'id'=>$data->project_id, 'milestone'=>$data->id)); ?></big>
		<br />
		<div class="text-right" style="margin-top:-20px;padding-bottom: 10px;">
			<a href="<?php echo CController::createUrl('roadmap', array('id'=>$data->project_id, 'milestone'=>$data->id))?>">
				<i class="icon-eye-open"></i>
				View
			</a>
			-
			<a href="#" onClick="<?php echo ";updateJS('/index.php/milestone/update/".$data->id."', 'Update Milestone');$('#dialogModal').dialog('open'); return false;"?>">
				<i class="icon-pencil"></i>
				Edit
			</a>
		</div>
	</div>
	

	<b><?php echo CHtml::encode($data->getAttributeLabel('due_date')); ?>:</b>
	<?php echo CHtml::encode($data->due_date); ?>
	<br /><br />
	
	<?php 
		$dataCompletion = $data->computeCompletion();
	
		$this->widget('bootstrap.widgets.TbProgress', array(
		'stacked'=>array(
		array('type'=>'success', 'percent'=>$dataCompletion['success'], 'htmlOptions'=>array('title'=>'Closed Issues: '. $dataCompletion['success'] . '%')),
		array('type'=>'warning', 'percent'=>$dataCompletion['warning'], 'htmlOptions'=>array('title'=>'Opened Issues: '. $dataCompletion['warning'] . '% Done')),
		)));
	?>
	
	
	
	<p>
	<i><small><?php echo $dataCompletion['count']?> Issues: <?php echo $dataCompletion['closed']?> closed - <?php echo $dataCompletion['opened']?> opened (<?php echo $dataCompletion['warning']?>% done)</small></i>
	</p>
	
	<?php 
		$this->widget('zii.widgets.jui.CJuiAccordion', array(
	    'panels'=>array(
		//'Milestones'=>$this->renderPartial('_milestones',array('data' => $data),true),
	    'Issues'=>$this->renderPartial('_issues',array('model' => $model, 'gridId' => $data->id, 'type' =>'milestone'),true),
	    ),
	    // additional javascript options for the accordion plugin
	    'options'=>array(
	    'active'=>false,
		'collapsible'=>true,
	    'heightStyle'=>'content',
	    'animated'=>'bounceslide',
	    )
	    ));
    ?>


</div>