<?php
/* @var $this IssueController */
/* @var $model Issue */

$this->breadcrumbs=array(
	'Issues'=>array('index'),
	$model->id,
);

$this->breadcrumbs=array(
	'Project'=>array('/project'),
	$model->project->label=>array('/project/view', 'id'=>$model->project_id),
	'Issues'=>array('/project/issues', 'id'=>$model->project_id),
	$model->type->label.' #'.$model->id,
);


$this->menuRelations=array(
	array('label'=>'List Issue', 'url'=>array('index')),
);

$this->menuUsers=array(
	array('label'=>'List Issue', 'url'=>array('index')),
);

$this->menuWorkload=array(
	array('label'=>'List Issue', 'url'=>array('index')),
);

$this->menuDocuments=array(
	array('label'=>'List Issue', 'url'=>array('index')),
);
?>

<div>
	<h1><?php echo $model->type->label;?> #<?php echo $model->id; ?></h1>
	
	<div class="text-right" style="margin-top:-40px;padding-bottom: 10px;">
		<a href="<?php echo CController::createUrl('update', array('id'=>$model->id))?>">
			<i class="icon-pencil"></i>
			Update
		</a>
		-
		<a href="#" onclick=';logTime();$("#dialogTimetracker").dialog("open");return false;'>
			<i class="icon-time"></i>
			Log Time
		</a>
		-
		
		<a href="#" onclick=';updateJS("/issueTransition/create/issue/<?php echo $model->id?>", "Issue Transition");$("#dialogModal").dialog("open");return false;'>
			<i class="icon-tasks"></i>
			Transition
		</a>
		-
		
		<a id="yt0" href="#">
			<i class="icon-trash"></i>
			Delete
		</a>
	</div>
</div>

<h3><?php echo $model->label;?> <small>Created by <?php echo $model->user->username;?></small></h3>

<?php 
	$arrEffort = $model->estimatedRemainingEffort();
	
	if($arrEffort['estimatedRemainingEffort'] > $arrEffort['theoricalEfforForCompletion']){
		$class = 'alert in alert-block fade alert-error';
		$title = 'Alert! Overrun of ' . ($arrEffort['loggedEffort']-$arrEffort['theoricalEfforForCompletion']) . ' hours';
		$message .= $model->type->label . ' completed at <i>' . $model->completion . '%</i> in <i>' . $arrEffort['loggedEffort'] . ' hours</i> instead of the <i>' . $arrEffort['theoricalEfforForCompletion'] . ' hours</i> originaly scheduled.';
		$message .= '<br />According to current logged effort, the remaining effort for the resting <i>'.(100-$model->completion).'%</i> has to be increased to <i>' . $arrEffort['estimatedRemainingEffort'] . ' hours</i>';
		
		$this->renderPartial('partials/_message', array('message'=>$message, 'title' => $title, 'class' => $class));
	}
	else{
		$class = 'alert in alert-block fade alert-success';
	}
?>



<?php 
	$arrAttributes[] = array('label'=>'Status', 'value' => $model->status->label);
	$arrAttributes[] = array('label'=>'Priority', 'value' => $model->getPriorities($model->priority));
	$arrAttributes[] = array('label'=>'Assignee', 'value' => $model->assignee->username);
	
	if(!empty($model->version_id)){
		if(!empty($model->milestone_id))
			$arrAttributes[] = array('label'=>'Version & Milestone', 'value' => $model->version->label . ': ' . $model->milestone->label);
		else
			$arrAttributes[] = array('label'=>'Version', 'value' => $model->version->label);
	}
		
	$arrAttributes[] = array('label'=>'Due Date', 'value' => $model->due_date);
	
	if($model->estimated_time > 0)
		$arrAttributes[] = array('label'=>'Estimated effort', 'value' => $model->estimated_time . ' hrs');
	else
		$arrAttributes[] = array('label'=>'Estimated effort', 'value' => null);
	
	$this->widget('zii.widgets.CDetailView', array(
	'id' => 'issue-detail',
	'data'=>$model,
	'attributes'=>$arrAttributes,
)); ?>

<div style="border-top: 1px solid lightgrey; padding-top: 5px; margin-top: 20px;">
<h4>Description</h4>
<?php echo $model->description;?>
</div>

<div style="border-top: 1px solid lightgrey; padding-top: 5px; margin-top:20px;">
<h4>Activity</h4>
<?php 
	$arrLogs = array('id' => 'issuelogs-grid',
							'ajaxUpdate'=>true,
							'dataProvider' => $model->getLogs(),
							'itemView' => '/issue/_logs',
							'enableSorting' => true,
							'viewData' => array('model' => $model));

	$this->widget('zii.widgets.CListView', $arrLogs);
?>
</div>

<?php $this->renderPartial('_sidebar', array('model'=>$model)); ?>
