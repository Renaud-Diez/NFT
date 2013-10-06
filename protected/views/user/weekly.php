<?php
/* @var $this UserController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Users',
);
?>

<h1>Weekly Report</h1>

<div class="row-fluid">
		<div class="span6">
		<?php
			$arrdata = $model->getActivities();
			if($arrdata)
				$this->renderPartial('_HCactivities', array('data'=>$arrdata));
		?>
		</div>
		
		<div class="span6">
		<?php 
			$dataActivities = $model->getActivityDetail();

			if($dataActivities)
			{	
				$this->widget('bootstrap.widgets.TbExtendedGridView', array(
				'id' => 'issue-grid',
			    'dataProvider' => $dataActivities,
			    'type' => 'striped bordered condensed',
			    'summaryText' => false,
				'columns' => array(
								'time_spent',
								'activity',
								'issue',
								'project',
							)
				));
			}
		?>
		</div>
</div>

<?php 
		$this->widget('zii.widgets.jui.CJuiAccordion', array(
	    'panels'=>array(
		//'Milestones'=>$this->renderPartial('_milestones',array('data' => $data),true),
		'Issues Logs'=>$this->renderPartial('partials/issueLogs',array('model' => $model),true),
	    'Time Logs'=>$this->renderPartial('partials/timetracker',array('model' => $model, 'dataProvider' => $dataProvider),true),
	    ),
	    // additional javascript options for the accordion plugin
	    'options'=>array(
	    //'active'=>false,
		'collapsible'=>true,
	    'heightStyle'=>'content',
	    'animated'=>'bounceslide',
	    )
	    ));
?>

<?php 
		/*$this->widget('zii.widgets.jui.CJuiAccordion', array(
	    'panels'=>array(
		//'Milestones'=>$this->renderPartial('_milestones',array('data' => $data),true),
	    'Issues Logs'=>$this->renderPartial('_issues',array('model' => $model),true),
	    ),
	    // additional javascript options for the accordion plugin
	    'options'=>array(
	    'active'=>false,
		'collapsible'=>true,
	    'heightStyle'=>'content',
	    'animated'=>'bounceslide',
	    )
	    ));*/
?>
