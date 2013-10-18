<?php
/* @var $this ProjectController */
/* @var $model Project */

$this->breadcrumbs=array(
	'Project'=>array('index'),
	$model->label=>array('view','id'=>$model->id),
	'Statistics',
);
?>
<div>
	<h1><?php echo $model->label;?> <small>#<?php echo $model->code; ?></small></h1>
	
	<?php $this->renderPartial('partials/_titleMenu', array('model'=>$model)); ?>
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
