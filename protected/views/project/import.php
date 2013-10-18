<?php
/* @var $this ProjectController */
/* @var $model Project */

$this->breadcrumbs=array(
	'Projects'=>array('index', 'v' => 'highlighted'),
	$model->id,
);


$this->memberMenu=array(
	array('label'=>'Manage Members', 'url'=>array('project/members', 'id'=>$model->id)),
);
?>

<div id="statusMsg" class="flash-success" style="display:none;">

</div>

<h1>Import</h1>


<?php //$this->renderPartial('partials/_titleMenu', array('model'=>$model)); ?>

<div style="border-top: 1px solid lightgrey; padding-top: 5px;">
<b>Activity</b>
<?php 
	$filePath = 'assets/media/test.xlsx';
	$arrSheet = Yii::app()->yexcel->readActiveSheet($filePath);
	
	echo '<table>';
	foreach($arrSheet as $row){
		echo '<tr>';
		foreach($row as $record => $value){
			echo '<td>' . $arrSheet[1][$record] . '::' . $value . '</td>';
		}
		echo '</tr>';
	}
	echo '</table>';
?>
</div>
<?php //$this->renderPartial('_sidebar', array('model'=>$model)); ?>