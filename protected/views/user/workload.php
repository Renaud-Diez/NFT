<?php
/* @var $this TeamController */
/* @var $model Team */

$this->breadcrumbs=array(
	'Users'=>array('index'),
	$model->uname,
);

?>

<div id="statusMsg" class="flash-success" style="display:none;"></div>

<div>
<h1>Workload <?php if($model->id != Yii::app()->user->id)echo '- ' . $model->uname;?></h1>
<?php $this->renderPartial('partials/titleMenu', array('model'=>$model)); ?>
</div>

<?php 
	$hcData = $model->workload($this->search);
	if($hcData)
		$this->renderPartial('HCworkload', array('hcData' => $hcData));
?>
