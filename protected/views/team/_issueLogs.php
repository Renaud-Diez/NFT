<?php
/* @var $this UserController */
/* @var $data User */

if($GLOBALS['project'] != $data->issue->project_id){
	echo '<h4>'.$data->issue->project->label.'</h4>';
}

if($GLOBALS['issue'] != $data->issue_id){
	echo '<h4><small>'.$data->issue->label.'</small></h4>';
}

$date = Yii::app()->dateFormatter->format('y-MM-d', $data->creation_date);
if($GLOBALS['date'] != $date){
	echo '<h4><small>'.$date.'</small></h4>';
}

$GLOBALS['project'] = $data->issue->project_id;
$GLOBALS['issue'] = $data->issue_id;
$GLOBALS['date'] = $date;

if($data->issue->status->alias == 1)
	$class = 'alert  in alert-block fade alert-info';
elseif($data->issue->status->alias == 2)
	$class = 'alert  in alert-block fade alert-warning';
elseif($data->issue->status->alias == 3)
	$class = 'alert  in alert-block fade alert-success';
?>



<div class="<?php echo $class;?>">
	<?php echo $data->comment;?>
</div>