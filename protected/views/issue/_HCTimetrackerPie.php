<?php

$this->Widget('ext.highcharts.HighchartsWidget', array(
//'scripts' => array('highcharts-more'),   
	'options'=>array(	
      	'title' => array('text' => null),
		'chart' => array('backgroundColor' => '#f5f5f5', 'plotBackgroundColor' => false, 'plotBorderWidth' => false, 'plotShadow' => false),
      	'tooltip' => array('pointFormat' => '<b>{point.percentage:.1f}%  - {point.y} hrs</b>'),
      	'plotOptions' => array('pie' => array('allowPointSelect' => true, 
      											'cursor' => 'pointer',
												'showInLegend' => true,
      											'dataLabels' => array('enabled' => false, 
      																	'color' => '#000000', 
      																	'connectorColor' => '#000000', 
      																	'format' => '<b>{point.name}</b>: {point.percentage:.1f} %'))),
      	'series' => array(array('type' => 'pie', 'name' => 'Activities', 'data' => $data
							))
																					
   )
));

?>