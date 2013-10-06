<?php
/* @var $this UserController */
/* @var $data User */

if($GLOBALS['project'] != $data['project']){
	echo '<h4>'.$data['project'].'</h4>';
}

if($GLOBALS['issue'] != $data['issue']){
	echo '<h4><small>'.$data['issue'].'</small></h4>';
}

$date = Yii::app()->dateFormatter->format('y-MM-d', $data['log_date']);
if($GLOBALS['date'] != $date){
	echo '<h4><small>'.$date.'</small></h4>';
}

$GLOBALS['project'] = $data['project'];
$GLOBALS['issue'] = $data['issue'];
$GLOBALS['date'] = $date;
?>



<div class="view">
	<?php echo '<i>'.$data['activity'] . ': ' . $data['time_spent'].'h</i><br />'. $data['comment'];?>
</div>