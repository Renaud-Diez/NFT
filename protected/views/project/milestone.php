<?php
$this->breadcrumbs=array(
	'Project'=>array('index'),
	$model->label=>array('view','id'=>$model->id),
	'Issues',
);
?>

<h1>Roadmap - Milestone: <?php echo $milestone->label?> <small>version: <?php echo $milestone->version->label?></small></h1>

<div class="text-right" style="margin-top:-40px;padding-bottom: 15px;">
		<a href="#" onClick="<?php echo ";updateJS('/index.php/milestone/update/".$milestone->id."', 'Update Milestone');$('#dialogModal').dialog('open'); return false;"?>">
			<i class="icon-pencil"></i>
			Update
		</a>
	</div>


	<i><small>Progress:</small></i>
	<?php 
		$dataCompletion = $milestone->computeCompletion();
		
		$this->widget('bootstrap.widgets.TbProgress', array(
		'stacked'=>array(
		array('type'=>'success', 'percent'=>$dataCompletion['success'], 'htmlOptions'=>array('title'=>'Closed Issues: '. $dataCompletion['success'] . '%')),
		array('type'=>'warning', 'percent'=>$dataCompletion['warning'], 'htmlOptions'=>array('title'=>'Opened Issues: '. $dataCompletion['warning'] . '% Done')),
		//array('type'=>'danger', 'percent'=>30),
		)));
	?>


	<i><small><?php echo $dataCompletion['count']?> Issues: <?php echo $dataCompletion['closed']?> closed - <?php echo $dataCompletion['opened']?> opened (<?php echo $dataCompletion['warning']?>% done)</small></i>

<?php 
		$this->widget('zii.widgets.jui.CJuiAccordion', array(
	    'panels'=>array(
		//'Milestones'=>$this->renderPartial('_milestones',array('data' => $data),true),
	    'Issues'=>$this->renderPartial('_issues',array('model' => $model, 'gridId' => $milestone->id, 'type' => 'version'),true),
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

<?php $this->renderPartial('_sidebar', array('model'=>$model)); ?>