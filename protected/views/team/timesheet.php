<?php
/* @var $this TeamController */
/* @var $model Team */

$this->breadcrumbs=array(
	'Teams'=>array('index'),
	$model->label,
);

?>

<div id="statusMsg" class="flash-success" style="display:none;"></div>

<div>
<h1>Team <?php echo $model->label;?></h1>
<?php $this->renderPartial('titleMenu', array('model'=>$model)); ?>
</div>

<h2>Timesheet</h2>
<?php 
	$Tdata = $model->timesheet($this->search);
	print_r($Tdata);
	/*if($data)
		$this->renderPartial('timesheetGrid', array('data' => $data));*/
?>
