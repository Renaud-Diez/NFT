<?php
/* @var $this UserController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Users',
);

$data = false;
?>

<h1>Weekly Report</h1>

<div class="row-fluid">
		<?php 
			$arrdata = $model->getActivities($this->search);
		if($arrdata):?>
		<div class="span6">
		<?php $data = true; $this->renderPartial('_HCactivities', array('data'=>$arrdata)); ?>
		</div>
		<?php endif;?>
		
		<?php $dataActivities = $model->getActivityDetail($this->search);

			if($dataActivities):?>
		<div class="span6">
		<?php 	$data = true;
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
		?>
		</div>
		<?php endif;?>
		
		<?php if(!$data){echo 'No data available for the period.';}?>
</div>

<?php 
		$issueLogs = $model->getUserWeeklyIssues($this->search);
		
		if(count($issueLogs->getData()) > 0)
			$arrPanels['Issues Logs'] = $this->renderPartial('partials/issueLogs',array('model' => $model, 'issueLogs' => $issueLogs),true);
		
		if($dataProvider)
			$arrPanels['Time Logs'] = $this->renderPartial('partials/timetracker',array('model' => $model, 'dataProvider' => $dataProvider),true);
		
		if(count($arrPanels) > 0){
			$this->widget('zii.widgets.jui.CJuiAccordion', array(
		    'panels'=>$arrPanels,
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
