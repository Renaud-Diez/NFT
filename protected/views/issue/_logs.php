<?php
/* @var $this IssueController */
/* @var $data IssueLogs */

if($GLOBALS['logDate'] != Yii::app()->dateFormatter->format('y-MM-d', $data->creation_date)){
	echo '<h4>'.Yii::app()->dateFormatter->formatDateTime($data->creation_date, 'medium', null).'</h4>';
}
$GLOBALS['logDate'] = Yii::app()->dateFormatter->format('y-MM-d', $data->creation_date);

$arrAttributes = array('type_id','status_id','due_date','assignee_id','priority','version_id','milestone_id','completion');
foreach($arrAttributes as $attribute){
	$value = $model->getRelatedValue($attribute, $data);
		
	if($GLOBALS[$attribute] != $value){
			$changes .= '<br />'. $data->getAttributeLabel($attribute) . ': ' . $GLOBALS[$attribute] . ' > ' . $value;
	}
		
	$GLOBALS[$attribute] = $value;
}

//view alert-error
?>

<div class="view">
	<small>
	<i>@ <?php echo Yii::app()->dateFormatter->format('H:mm:ss', $data->creation_date);?> by <?php echo $data->user->username;?></i>
	<?php echo $changes;?>
	<?php if($data->comment != '')echo '<br />' . $data->comment . '<br />'; ?>
	</small>
</div>