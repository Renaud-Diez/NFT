<?php
/* @var $this ProjectController */
/* @var $data Project */
?>

<div class="view">

	<big><?php echo CHtml::link(CHtml::encode($data->label), array('view', 'id'=>$data->id)); ?></big>
	
	<?php 
		$projectUser = $data->getVisibility();
		if(($_GET['v'] != 'highlighted' && $projectUser->visibility == 0) || $_GET['v'] == 'highlighted'):?>
	<div class="text-right" style="margin-top:-20px;padding-bottom: 10px;">
			<i class="icon-star"></i>
			<a href="<?php 
							$visibility=1;
							if($_GET['v'] == 'highlighted'){
								$visibility=0;
							}
							echo CController::createUrl('index', array('highlight' => $data->id, 'visibility' => $visibility, 'v'=>'highlighted'))
							?>">
				<?php if($_GET['v'] == 'highlighted'){echo 'Unhighlight';}else{echo 'Highlight';}?>
			</a>
	</div>
	<?php endif;?>
	<br />
	
	<?php echo CHtml::encode($data->topic->label); ?> project <small>owned by <?php echo CHtml::encode($data->owner->username); ?></small>
	<br /><br />
	
	<i><small>Progress:</small></i>
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
		$arrOptions['collapsible'] = true;
		$arrOptions['heightStyle'] = 'content';
		$arrOptions['animated'] = 'bounceslide';
		

		$dataProvider = $data->getEvents(3);
		$dpInfo = $data->getIssues(false, 'info');
		$treedata = $data->getSubprojects($data->id);
		
		$arr = $dpInfo->getData();
		if(is_array($arr) && count($arr) > 0){
			$arrPanels['Info & Questions'] = $this->renderPartial('partials/_info', array('model'=> $data, 'dataProvider' => $dpInfo), true);
			$active = true;
			$accordion = true;
		}
		
		$arr = $dataProvider->getData();
		if(is_array($arr) && count($arr) > 0){
			$arrPanels['Events & Alerts'] = $this->renderPartial('_alerts', array('model'=> $data, 'dataProvider' => $dataProvider), true);
			$active = true;
			$accordion = true;
		}

		if($data->description != ''){
			$arrPanels['Description'] = $this->renderPartial('_description', array('description'=> $data->description), true);
			$accordion = true;
		}
		
		if($treedata && count($treedata) > 0){
			$arrPanels['Subprojects'] = $this->renderPartial('_subprojects', array('data'=> $data, 'treedata' => $treedata), true);
			$accordion = true;
		}
			
		if(!$active)
			$arrOptions['active'] = false;
			
		if($accordion){
			$this->widget('zii.widgets.jui.CJuiAccordion', array(
		    'panels'=>$arrPanels,
		    // additional javascript options for the accordion plugin
		    'options'=>$arrOptions
		    ));
		}
?>

</div>