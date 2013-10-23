<?php
$units = 'hrs';
if($type == 'issue-type')
	$units = 'records';
$this->Widget('ext.highcharts.HighchartsWidget', array(
//'scripts' => array('highcharts-more'),   
	'options'=>array(	
      	'title' => array('text' => null),
		'chart' => array('plotBackgroundColor' => false, 'plotBorderWidth' => false, 'plotShadow' => false),
      	'tooltip' => array('pointFormat' => '<b>{point.percentage:.1f}%  - {point.y} hrs</b>'),
      	'plotOptions' => array('pie' => array('allowPointSelect' => true, 
      											'cursor' => 'pointer',
												'showInLegend' => true,
      											'dataLabels' => array('enabled' => true, 
      																	'color' => '#000000', 
      																	'connectorColor' => '#000000', 
      																	'format' => '<b>{point.name}</b>: {point.percentage:.1f} % - {point.y} '.$units))),
      	'series' => array(array('type' => 'pie', 'name' => 'Activities', 'data' => $data
							))
																					
   )
));

?>