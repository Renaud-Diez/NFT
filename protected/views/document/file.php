<?php
/* @var $this DocumentController */
/* @var $data Document */
?>
<?php 
$path = $model->path;
if(in_array(substr($path, -4), array('.jpg', '.png', '.gif')))
	echo CHtml::image($model->path, $model->comment, array('style' => 'width:400; height:400'));
?>
