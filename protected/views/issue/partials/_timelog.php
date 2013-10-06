<?php
/* @var $this IssueController */
/* @var $data Timetracker */


$date = Yii::app()->dateFormatter->format('y-MM-d', $data->log_date);
if($GLOBALS['date'] != $date){
	echo '<h4><small>'.$date.'</small></h4>';
}
$GLOBALS['date'] = $date;

$class = 'view';
if($data->user_id == Yii::app()->user->id)
	$class .= ' alert-info'
?>



<div class="<?php echo $class;?>">
	<?php echo '<b>'.Yii::app()->dateFormatter->format('HH:mm:ss', $data->log_date). ' - ' . strtoupper($data->user->username) . '</b> - <i>' . $data->activity->label . ': ' . $data->time_spent.'h</i>';?>
	<?php if($data->comment != '')echo '<div style="margin-top: 10px;">'. $data->comment . '</div>';?>
</div>