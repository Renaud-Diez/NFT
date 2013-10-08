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
	$issues = $model->getDataProviderIssues($model->issueFilter($_GET['Issue'], 'project', $data->id));
	
	if(count($issues->getData()) > 0)
	{
		$this->widget('zii.widgets.jui.CJuiAccordion', array(
				'panels'=>array(
						//'Milestones'=>$this->renderPartial('_milestones',array('data' => $data),true),
						'Issues'=>$this->renderPartial('_issues',array('dataProvider' => $issues, 'gridId' => $data->id),true),
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
	
<?php $this->renderPartial('_sidebar', array('model'=>$model)); ?>