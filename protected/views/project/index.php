<?php
/* @var $this ProjectController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Projects',
);

$this->viewMenu=array(
	array('label'=>'All Projects', 'url'=>array('index')),
	array('label'=>'All Root Projects', 'url'=>array('index', 'v' => 'root')),
	array('label'=>'Highlighted Projects', 'url'=>array('index', 'v' => 'highlighted')),
);
?>

<h1>Projects</h1>

<?php 
	$hcData = Project::model()->getScheduledProjects($dataProvider);
		
	if($hcData){
		$this->widget('zii.widgets.jui.CJuiAccordion', array(
	    'panels'=>array(
		'Gantt'=>$this->renderPartial('_highchartProject', array('hcData' => $hcData), true),
	    ),
	    // additional javascript options for the accordion plugin
	    'options'=>array(
	    //'active'=>false,
		'collapsible'=>true,
	    'heightStyle'=>'content',
	    'animated'=>'bounceslide',
	    )
	    ));
	}
?>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>

<script type="text/javascript">

function topicFilter(url)
{
	var elements = document.getElementsByClassName('topic');
	var params = '';
	var operator = '?';

	for (var i = 0; elements[i]; i++) {
	     if((elements[i].value == 'NONE' || elements[i].value == 'ALL') && elements[i].checked)
	    	 params = elements[i].value + ',';
	     else if((elements[i].value != 'NONE' && elements[i].value != 'ALL') && !elements[i].checked){
				params = params + elements[i].value + ',';
	     }
	}

	if(url.indexOf('?') != -1)
		operator = '&';

	params = operator + 'topic=' + params.substr(0,params.length-1);
	document.location.href = url + params
}
</script>
