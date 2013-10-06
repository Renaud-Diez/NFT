<?php
/* @var $this ProjectController */
/* @var $model Issue */

$this->breadcrumbs=array(
	'Project'=>array('index'),
	$model->label=>array('view','id'=>$model->id),
	'Issues',
);

$this->issueMenu=array(
	array('label'=>'Create Issue', 'url'=>array('issue/create', 'pid'=>$model->id)),
	array('label'=>'View Issues', 'url'=>array('issues', 'id'=>$model->id)),
);


Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$('#issue-grid').yiiGridView('update', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<h1>Kanban</h1>

<?php $this->renderPartial('partials/_titleMenu', array('model'=>$model)); ?>

<?php
echo '<table widht="100%">';
foreach($types as $type => $status){
	$groups[] = $type;
	
	echo '<tr><td style="'.$tyle.'"><h4>' . strtoupper($type) . '</h4></td></tr><tr><td>';
	$tyle = 'border-top: 1px solid rgb(217, 217, 217);';

		$div = 93;
		if(count($status) > 1)
			$div = round((90/count($status)));
		foreach($status as $record => $issues){
			echo '<div class="'.$type.'" style="float: left; width:'.$div.'%; padding: 4px; margin-bottom:5px; margin-right: 10px; border-radius: 8px; border: 1px solid rgb(217, 217, 217);"><h4>'.$record.'</h4><hr style="border-color: rgb(217, 217, 217); border-width: 1px 0px 0px; margin-top: -5px; margin-bottom:-5px;">';
			
			$arr = array('id' => $type.'-'.$record.'-grid',
							'ajaxUpdate'=>true,
							'dataProvider' => $issues,
							'itemView' => 'issue/_postit',
							'enableSorting' => true,
							'viewData' => array('model' => $model));

			$this->widget('zii.widgets.CListView', $arr);
			echo '</div>';
		}
		
	echo '</td></tr>';
}
echo '</table>';

/*$types = array('id' => 'related-grid',
							'ajaxUpdate'=>true,
							'dataProvider' => $types,
							'itemView' => 'issue/_type',
							'enableSorting' => true,
							'viewData' => array('model' => $model));

$this->widget('zii.widgets.CListView', $types);*/
?>

<?php 
/*$arrIsue = array('id' => 'related-grid',
							'ajaxUpdate'=>true,
							'dataProvider' => $dataProvider,
							'itemView' => 'issue/_kanban',
							'enableSorting' => true,
							'viewData' => array('model' => $model));

echo '<table widht="100%">';
$this->widget('zii.widgets.CListView', $arrIsue);
echo '</table>';*/
?>


<script type="text/javascript">
function equalHeight(group) {
	var tallest = 0;
	
	group.each(function() {
		var thisHeight = $(this).height();
		if(thisHeight > tallest){
			tallest = thisHeight;
		}
	});
	group.height(tallest);
}

<?php 
//$groups = $GLOBALS['kbGroups'];
foreach($groups as $group)
	echo 'equalHeight($(".'.$group.'"));';
?>
</script>

<?php $this->renderPartial('_sidebar', array('model'=>$model)); ?>
