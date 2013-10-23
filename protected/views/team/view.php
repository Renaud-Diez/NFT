<?php
/* @var $this TeamController */
/* @var $model Team */

$this->breadcrumbs=array(
	'Teams'=>array('index'),
	$model->label,
);

$this->menu=array(
	array('label'=>'List Team', 'url'=>array('index')),
	array('label'=>'Create Team', 'url'=>array('create')),
);
?>

<div id="statusMsg" class="flash-success" style="display:none;"></div>

<div>
<h1>Team <?php echo $model->label;?></h1>
<?php $this->renderPartial('titleMenu', array('model'=>$model)); ?>
</div>

<?php /*$this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'label',
	),
));*/ ?>

<?php 
$arrMembers = array('id' => 'members-grid',
		'ajaxUpdate'=>true,
		'dataProvider' => $model->getMembers(),
		'itemView' => '_members',
		'enableSorting' => true,
		'viewData' => array('model' => $model));
	
if(!empty($arrMembers))
{
	$this->widget('zii.widgets.CListView', $arrMembers);
}
?>
