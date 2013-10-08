<?php
$this->breadcrumbs=array(
	'Project'=>array('index'),
	$model->label=>array('view','id'=>$model->id),
	'Issues',
);
?>

<h1>Roadmap - Version: <?php echo $version->label?></h1>

<?php 
	$hcData = Version::model()->getScheduledMilestones($version->getAvailableMilestones());
	if($hcData){
		$this->widget('zii.widgets.jui.CJuiAccordion', array(
	    'panels'=>array(
		'Gantt'=>$this->renderPartial('_highchartProject', array('hcData' => $hcData), true),
	    ),
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

<br />

<div class="text-right" style="margin-top:-60px;padding-bottom: 15px;">
		<a href="#" onClick="<?php echo ";updateJS('/index.php/version/update/".$version->id."', 'Update Version');$('#dialogModal').dialog('open'); return false;"?>">
			<i class="icon-pencil"></i>
			Update
		</a>
	</div>


	<i><small>Progress:</small></i>
	<?php 
		$dataCompletion = $version->computeCompletion();
		
		$this->widget('bootstrap.widgets.TbProgress', array(
		'stacked'=>array(
		array('type'=>'success', 'percent'=>$dataCompletion['success'], 'htmlOptions'=>array('title'=>'Closed Issues: '. $dataCompletion['success'] . '%')),
		array('type'=>'warning', 'percent'=>$dataCompletion['warning'], 'htmlOptions'=>array('title'=>'Opened Issues: '. $dataCompletion['warning'] . '% Done')),
		//array('type'=>'danger', 'percent'=>30),
		)));
	?>


	<i><small><?php echo $dataCompletion['count']?> Issues: <?php echo $dataCompletion['closed']?> closed - <?php echo $dataCompletion['opened']?> opened (<?php echo $dataCompletion['warning']?>% done)</small></i>

<?php 
	$issues = $model->getDataProviderIssues($model->issueFilter($_GET['Issue'], 'version', $version->id));

	if(count($issues->getData()) > 0)
	{
		$this->widget('zii.widgets.jui.CJuiAccordion', array(
				'panels'=>array(
						//'Milestones'=>$this->renderPartial('_milestones',array('data' => $data),true),
						'Issues'=>$this->renderPartial('_issues',array('dataProvider' => $issues, 'gridId' => $version->id),true),
				),
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
<?php 
	$milestones = $version->getMilestones($model, $version->milestones, '_viewRoadmap');//$data->getMilestones($model, $data->milestones);
		
	if($milestones):?>
	<h3>Milestones:</h3>
	<div style="margin-top: -20px;">
	<?php $this->widget('zii.widgets.CListView', $milestones);?>
	</div>
	<?php endif;?>
<?php $this->renderPartial('_sidebar', array('model'=>$model)); ?>