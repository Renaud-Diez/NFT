<?php
$this->breadcrumbs=array(
	'Project'=>array('index'),
	$model->label=>array('view','id'=>$model->id),
	'Issues',
);

$this->issueMenu=array(
	array('label'=>'Create Issue', 'url'=>array('issue/create', 'pid'=>$model->id)),
	array('label'=>'View Issues', 'url'=>array('issues', 'id'=>$model->id)),
);

?>

<h1>Roadmap</h1>


<?php $this->renderPartial('partials/_titleMenu', array('model'=>$model)); ?>


<?php 

		$this->widget('zii.widgets.jui.CJuiAccordion', array(
	    'panels'=>array(
		'Gantt'=>$this->renderPartial('_highchartProject', array('dataProvider'=> $versions, 'type' => 'versions'), true),
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

<br />

	<i><small>Overall progress:</small></i>
	<?php 
		//print_r($dataCompletion);
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
	    'Issues not linked to a version'=>$this->renderPartial('_issues',array('model' => $model, 'gridId' => $data->id, 'type' =>'project'),true),
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

<h3>Versions</h3>

<div style="margin-top: -20px;">
<?php 
$this->widget('zii.widgets.CListView', array('id' => 'user-grid','ajaxUpdate'=>true,'dataProvider' => $versions, 'itemView' => '/version/_view', 'enableSorting' => true, 'viewData'=> array('model'=>$model)));
?>
</div>

<?php $this->renderPartial('_sidebar', array('model'=>$model)); ?>