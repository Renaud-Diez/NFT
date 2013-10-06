<div>
	<h1><?php echo $model->label;?> <small>#<?php echo $model->code; ?></small></h1>
	
	<?php $this->renderPartial('partials/_titleMenu', array('model'=>$model)); ?>
</div>

<div>
<h3><?php echo $model->topic->label?> project <small>owned by <?php echo $model->owner->username;?></small></h3>
</div>

<?php echo $this->renderPartial('partials/ProjectAlerts', array('model'=> $model), true);?>

<?php 
		/*$issue=new Issue('search');
		$issue->unsetAttributes();
		$issue->attributes=array('version_id'=>$data->id);
		$dataProvider = $model->getDataProviderIssues($issue);*/
		$this->widget('zii.widgets.jui.CJuiAccordion', array(
	    'panels'=>array(
		'Description'=>$this->renderPartial('_description', array('description'=> $model->description), true),
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

<?php 
	$arrCompletion = $model->estimatedRemainingEffort();
	//print_r($arrCompletion);

		$this->widget('zii.widgets.jui.CJuiAccordion', array(
	    'panels'=>array(
		'Project Completion Trends'=>$this->renderPartial('_highchartCompletion', array('type' => 'project', 'model' => $model), true),
	    ),
	    // additional javascript options for the accordion plugin
	    'options'=>array(
	    //'active'=>false,
		'collapsible'=>true,
	    'heightStyle'=>'content',
	    'animated'=>'bounceslide',
	    )
	    ));
?>

<div style="margin-top: 20px;">
<i><small>Progress:</small></i>
<?php 
		$dataCompletion = $model->computeCompletion();
		$this->widget('bootstrap.widgets.TbProgress', array(
		'stacked'=>array(
		array('type'=>'success', 'percent'=>$dataCompletion['success'], 'htmlOptions'=>array('title'=>'Closed Issues: '. $dataCompletion['success'] . '%')),
		array('type'=>'warning', 'percent'=>$dataCompletion['warning'], 'htmlOptions'=>array('title'=>'Opened Issues: '. $dataCompletion['warning'] . '% Done')),
		//array('type'=>'danger', 'percent'=>30),
		)));
?>
</div>

<p>
	<i><small><?php echo $dataCompletion['count']?> Issues: <?php echo $dataCompletion['closed']?> closed - <?php echo $dataCompletion['opened']?> opened (<?php echo $dataCompletion['warning']?>% done)</small></i>
</p>

<br />
