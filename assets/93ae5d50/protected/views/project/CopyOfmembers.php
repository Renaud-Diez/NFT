
<?php
/* @var $this ProjectController */
/* @var $model Project */

$this->breadcrumbs=array(
	'Projects'=>array('index'),
	$model->id,
);

$this->hiddenMenu=array(
	array('label'=>'-', 'url'=>'#', 'linkOptions'=>array('submit'=>array('view','id'=>$model->id))),
);

$this->menu=array(
	array('label'=>'View Project', 'url'=>'#', 'linkOptions'=>array('submit'=>array('view','id'=>$model->id))),
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

<div id="statusMsg" class="flash-success" style="display:none;"></div>

<?php $this->renderPartial('_overview', array('model'=>$model)); ?>

<br><br>
<h2>Project Users</h2>


<?php //$this->widget('zii.widgets.CListView', array('dataProvider' => new CActiveDataProvider('User', array('data'=>$model->users, 'pagination' => array('pageSize' => 2))), 'itemView' => '/admin/user/_view'));
$this->arrMembers = array('id' => 'user-grid','ajaxUpdate'=>true,'dataProvider' => $model->getMembers(), 'itemView' => '/admin/user/_members', 'enableSorting' => true);
//$this->widget('zii.widgets.CListView', array('id' => 'user-grid','ajaxUpdate'=>true,'dataProvider' => $model->getMembers(), 'itemView' => '/admin/user/_members', 'enableSorting' => true));
?>

<?php Yii::app()->clientScript->registerScript('initUnregisterLinks',<<<JS
    $('body').on('click','.lnkButton', function(e) {
   		e.preventDefault();
   		$.post($(this).attr('href'), function(data) {
            reloadGrid(data);
        });
    });
JS
, CClientScript::POS_READY); /*$.post($(this).attr('href'), function(data) {
            $(this).parents('.list-view').html(data);
        });*/?>

<br>


<script>
function reloadGrid(data) { 
	$.fn.yiiListView.update('user-grid');
	$('#statusMsg').html(data).fadeIn().animate({opacity: 1.0}, 15000).fadeOut("slow");
}
</script>


<?php echo CHtml::ajaxLink('Add Members',
        $this->createUrl('project/setmembers/id/'.$model->id),
        array(
            'success'=>'function(r){$("#juiDialog").html(r).dialog("open"); return false;}'
        ),
        array('id'=>'showJuiDialog') // not very useful, but hey...
);?>

<?php 
$this->beginWidget('zii.widgets.jui.CJuiDialog',array(
                'id'=>'juiDialog',
                'options'=>array(
                    'title'=>'Add members to the Project',
                    'autoOpen'=>false,
                    'modal'=>true,
                    'width'=>'760',
                    'height'=>'auto',
					'close' => 'js:function(){location.reload();}'
                ),
                ));
$this->endWidget();
?>

