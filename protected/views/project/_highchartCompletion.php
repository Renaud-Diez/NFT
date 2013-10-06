<?php 
if($type == 'versions')
$series = Project::model()->getScheduledVersions($dataProvider);
elseif($type == 'milestones')
$series = Version::model()->getScheduledMilestones($dataProvider);
else
$series = Project::model()->dataCompletion($model->id);

$this->Widget('ext.highcharts.HighchartsWidget', array(
//'scripts' => array('highcharts-more'),   
	'options'=>array(	
      'title' => array('text' => 'Project Completion Trends'),
		'chart' => array('type' => 'areaspline'),
      'xAxis' => array(
        'title' => array('text' => 'Date'),
      	'type'=> 'datetime',
      	'dateTimeLabelFormats'=>array( // don't display the dummy year
                'month'=> '%e %b',
                'year'=> '%y'
            ),
      ),
      'yAxis' => array(
      	'title' => array('text' => 'Man Days'),
      	//'min' => 0,
      ),
      'tooltip' => array('formatter' => "js:function(){return '<b>'+ this.series.name +'</b><br/>'+
                        Highcharts.dateFormat('%e. %b', this.x) +': '+ this.y +' md';}"),
      'plotOptions' => array('areaspline' => array('fillOpacity' => 0.3, 'dataLabels' => array('enabled' => true), 'enableMouseTracking' => true)),
      'series' => $series
   )
));
?>