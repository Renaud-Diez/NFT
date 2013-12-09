<?php
/* @var $this ProjectController */
/* @var $model Project */

$this->breadcrumbs=array(
	'Projects'=>array('index', 'v' => 'highlighted'),
	$model->label,
);


$this->memberMenu=array(
	array('label'=>'Manage Members', 'url'=>array('project/members', 'id'=>$model->id)),
);
?>

<div id="statusMsg" class="flash-success" style="display:none;">

</div>

<?php $this->renderPartial('_overview', array('model'=>$model)); ?>

<div style="border-top: 1px solid lightgrey; padding-top: 5px;">
<b>Activity</b>
<?php 
	$arrEvent = array('id' => 'related-grid',
							'ajaxUpdate'=>true,
							'dataProvider' => $model->getEvents(),
							'itemView' => '/event/_view',
							'enableSorting' => true,
							'viewData' => array('model' => $model));
	$this->widget('zii.widgets.CListView', $arrEvent);
?>
</div>
<?php $this->renderPartial('_sidebar', array('model'=>$model)); ?>