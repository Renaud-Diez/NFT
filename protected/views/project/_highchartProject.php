
<?php 
if($type == 'versions')
$hcData = Project::model()->getScheduledVersions($dataProvider);
elseif($type == 'milestones')
$hcData = Version::model()->getScheduledMilestones($dataProvider);
else
$hcData = Project::model()->getScheduledProjects($dataProvider);

$categories = $hcData['categories'];
$series = $hcData['series'];

$this->Widget('ext.highcharts.HighchartsWidget', array(
'scripts' => array('highcharts-more'),   
	'options'=>array(	
      'title' => array('text' => 'Project Plan'),
		'chart' => array('type' => 'columnrange', 'inverted' => true),
      'xAxis' => array(
         'categories' => $categories//array('P1', 'P2', 'P3')
      ),
      'yAxis' => array(
         'title' => array('text' => 'Period'),
      	'type'=> 'datetime',
      	'dateTimeLabelFormats'=>array( // don't display the dummy year
                'month'=> '%e %b',
                'year'=> '%y'
            ),
      ),
      'tooltip' => array('formatter' => "js:function(){return '<b>' + this.x + '</b>' + ': ' + Highcharts.dateFormat('%e %b %y',this.point.low) + ' - ' + Highcharts.dateFormat('%e %b %y',this.point.high);}"),
      'plotOptions' => array('columnrange' => array('grouping' => false)),
      'series' => $series/*array(
         array('name' => 'V1', 'data' => array(array(7,10),array(6,9))),
         array('name' => 'V2', 'data' => array(array(0,0),array(5,8))),
         array('name' => 'V3', 'data' => array(array(1,3),array(3,5))),
      )*/
   )
));
?>
