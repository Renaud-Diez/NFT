<?php
/* @var $this TopicController */
/* @var $data Topic */
?>

<div>
	<?php
	if($_GET['v'])
		$url = Yii::app()->createUrl('/project/index', array('v' => $_GET['v']));
	else
		$url = Yii::app()->createUrl('/project/index');
		
	$checked = 'checked';
	if($_GET['topic'] != 'ALL' && ($_GET['topic'] == 'NONE' || in_array($data->id, explode(',', $_GET['topic']))))
		$checked = '';
		
	 echo '<input class="topic" type="checkbox" value="'.$data->id.'" name="Topic[checkbox]" onClick="javascript:topicFilter(\''.$url.'\');" '.$checked.'></input>'; ?>
	<?php echo CHtml::encode($data->label); ?>
</div>