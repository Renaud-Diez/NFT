
<?php 
$categories = $hcData['categories'];
$series = $hcData['series'];


$this->Widget('ext.highcharts.HighchartsWidget', array(
	//'scripts' => array('highcharts-more'),   
	'options'=>array(	
      'title' => array('text' => ''),
		'chart' => array(),//'chart' => array('type' => 'column'),
      'xAxis' => array(
      		'title' => array('text' => 'Week nbr.'),
      		'categories' => $categories//array('P1', 'P2', 'P3')
      ),
      'yAxis' => array(
         	'min' => 0,
      		'title' => array('text' => 'Hours'),
      	/*'type'=> 'datetime',
      	'dateTimeLabelFormats'=>array( // don't display the dummy year
                'month'=> '%e %b',
                'year'=> '%y'
            ),*/
      ),
      'tooltip' => array(
      		'headerFormat' => '<span style="font-size:10px">Week {point.key}</span><table>',
      		'pointFormat' => '<tr><td style="color:{series.color};padding:0">{series.name}: </td><td style="padding:0"><b>{point.y:.1f} h</b></td></tr>',
      		'footerFormat' => '</table>',
      		'shared' => true,
      		'useHTML' => true
		),
      'plotOptions' => array('column' => array('pointPadding' => 0.2, 'borderWidth' => 0)),
      'series' => $series/*array(
         array('name' => 'V1', 'data' => array(array(7,10),array(6,9))),
         array('name' => 'V2', 'data' => array(array(0,0),array(5,8))),
         array('name' => 'V3', 'data' => array(array(1,3),array(3,5))),
      )*/
   )
));
?>
