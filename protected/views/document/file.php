<?php
/* @var $this DocumentController */
/* @var $data Document */
?>
<?php 
$path = $model->path;
if(in_array(substr($path, -4), array('.jpg', '.png', '.gif')))
	echo CHtml::image($model->path, $model->comment, array('style' => 'width:400; height:400'));
else{
	echo 'Click hereafter to download the document:<br />' . CHtml::link($model->label, $model->path);
}
?>
