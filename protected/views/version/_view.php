<?php
/* @var $this VersionController */
/* @var $data Version */
?>

<div class="view">

	<div>
		<big><?php echo CHtml::link(CHtml::encode($data->label), array('project/roadmap', 'id'=>$data->project_id, 'version'=>$data->id)); ?></big>
		<br />
		<div class="text-right" style="margin-top:-20px;padding-bottom: 10px;">
			<a href="<?php echo CController::createUrl('roadmap', array('id'=>$data->project_id, 'version'=>$data->id))?>">
				<i class="icon-eye-open"></i>
				View
			</a>
			-
			<a href="#" onClick="<?php echo ";updateJS('/index.php/version/update/".$data->id."', 'Update Version');$('#dialogModal').dialog('open'); return false;"?>">
				<i class="icon-pencil"></i>
				Edit
			</a>
		</div>
	</div>
	

	<b><?php echo CHtml::encode($data->getAttributeLabel('due_date')); ?>:</b>
	<?php  echo DateTimeHelper::timeElapse($data->due_date);?>
	<small><i>(<?php echo CHtml::encode(substr($data->due_date,0,10)); ?>)</i></small>
	<br /><br />
	
	<small>Progress:</small>
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
		$milestones = $data->getMilestones($model, $data->milestones);
		
		$issues = $model->getDataProviderIssues($model->issueFilter($_GET['Issue'], 'version', $data->id));
		
		if($milestones)
			$arr['Milestones'] = $this->renderPartial('_milestones',array('milestones' => $milestones),true);
		
		//$arr['Issues'] = $this->renderPartial('_issues',array('model' => $model, 'gridId' => $data->id, 'type' =>'version'),true);
		if(count($issues->getData()) > 0)
			$arr['Issues'] = $this->renderPartial('_issues',array('dataProvider' => $issues, 'gridId' => $data->id),true);
		
		if(is_array($arr))
		{
			$this->widget('zii.widgets.jui.CJuiAccordion', array(
					'panels'=> $arr,
					// additional javascript options for the accordion plugin
					'options'=>array(
							//'active'=>false,
							'collapsible'=>true,
							'heightStyle'=>'content',
							'animated'=>'bounceslide',
					)
			));
		}
		
    ?>
	

</div>