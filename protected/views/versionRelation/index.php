<?php
/* @var $this VersionRelationController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Version Relations',
);

$this->menu=array(
	array('label'=>'Create VersionRelation', 'url'=>array('create')),
	array('label'=>'Manage VersionRelation', 'url'=>array('admin')),
);
?>

<h1>Version Relations</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
