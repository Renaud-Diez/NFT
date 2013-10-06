<?php
/* @var $this ProjectController */
/* @var $model Project */

$this->breadcrumbs=array(
	'Project'=>array('index'),
	$model->label=>array('view','id'=>$model->label),
	'Statistics',
);
?>
<div>
	<h1><?php echo $model->label;?> <small>#<?php echo $model->code; ?></small></h1>
	
	<div class="text-right" style="margin-top:-40px;padding-bottom: 10px;">
		<a href="<?php echo CController::createUrl('update', array('id'=>$model->id))?>">
			<i class="icon-pencil"></i>
			Update
		</a>
		-
		<a id="yt0" href="#">
			<i class="icon-trash"></i>
			Delete
		</a>
	</div>
</div>

<div>
<h3><?php echo $model->topic->label?> project <small>owned by <?php echo $model->owner->username;?></small></h3>
</div>



<?php 
	$this->widget('zii.widgets.jui.CJuiAccordion', array(
	    'panels'=>array(
		'Project Completion Trends'=>$this->renderPartial('_HCissuesType', array('type' => 'issue-type', 'model' => $model), true),
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

<?php 
	$this->widget('zii.widgets.jui.CJuiAccordion', array(
	    'panels'=>array(
		'Project Completion Trends'=>$this->renderPartial('_HCissuesWorkload', array('type' => 'project', 'model' => $model), true),
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

<?php 
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

<?php $this->renderPartial('_sidebar', array('model'=>$model)); ?>
