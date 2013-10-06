<?php
/* @var $this ProjectController */
/* @var $model Project */

$this->breadcrumbs=array(
	'Projects'=>array('index'),
	$model->id,
);

$this->menu=array(
	array('label'=>'List Project', 'url'=>array('index')),
	array('label'=>'Create Project', 'url'=>array('create')),
	array('label'=>'Update Project', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete Project', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage Project', 'url'=>array('admin')),
);

$this->issueMenu=array(
	array('label'=>'Create Issue', 'url'=>array('issue/create', 'pid'=>$model->id)),
	array('label'=>'View Issues', 'url'=>array('issue/view', 'pid'=>$model->id)),
);

$this->versionMenu=array(
	array('label'=>'Create Version', 'url'=>'#', 'linkOptions'=>array('onclick'=>';versionJS();$("#dialogVersion").dialog("open"); return false;')),
	array('label'=>'Manage Versions', 'url'=>array('project/versions', 'id'=>$model->id)),
	array('label'=>'Create Milestone', 'url'=>'#', 'linkOptions'=>array('onclick'=>';milestoneJS();$("#dialogMilestone").dialog("open"); return false;')),
	array('label'=>'Manage Milestones', 'url'=>array('milestone/create', 'pid'=>$model->id)),
);

$this->memberMenu=array(
	array('label'=>'Manage Members', 'url'=>array('project/members', 'id'=>$model->id)),
);
?>

<div id="statusMsg" class="flash-success" style="display:none;">

</div>

<?php $this->renderPartial('_overview', array('model'=>$model)); ?>

<?php 
	if(!empty($arrLogs))
	{
			$this->widget('zii.widgets.CListView', $arrLogs);
	}
?>