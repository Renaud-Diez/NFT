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
	array('label'=>'Create Version', 'url'=>array('version/create', 'pid'=>$model->id)),
	array('label'=>'Manage Versions', 'url'=>array('project/versions', 'id'=>$model->id)),
	array('label'=>'Create Milestone', 'url'=>array('milestone/create', 'pid'=>$model->id)),
	array('label'=>'Manage Milestones', 'url'=>array('milestone/admin', 'pid'=>$model->id)),
);

$this->memberMenu=array(
	array('label'=>'Manage Members', 'url'=>array('project/members', 'id'=>$model->id)),
);
?>

<div id="statusMsg" class="flash-success" style="display:none;">

</div>

<?php $this->renderPartial('_overview', array('model'=>$model)); ?>

<br><br>
<h2>Project Versions</h2>

<?php //$this->widget('zii.widgets.CListView', array('dataProvider' => new CActiveDataProvider('User', array('data'=>$model->users, 'pagination' => array('pageSize' => 2))), 'itemView' => '/admin/user/_view'));
$this->widget('zii.widgets.CListView', array('id' => 'user-grid','ajaxUpdate'=>true,'dataProvider' => $versions, 'itemView' => '/version/_view', 'enableSorting' => true));
?>

<?php 
/*$this->widget('application.widgets.rgraph.RGraphBar', array(
	'htmlOptions' => array('width' => 500, 'height' => 300),
	'data' => array(1, 3, 5, 7, 2, 4, 6, 10, 8, 9, 12, 11),
    'options' => array(
        'chart' => array(
            'gutter' => array(
                'left' => 20
            ),
            'background' => array(
            	'grid' => array(
            		'autofit' => true
            	)
            ),
            'colors' => array('red'),
            'title' => 'A basic chart',
            'labels' => array(
                'Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'
            ),
        )
    )
));*/
?>