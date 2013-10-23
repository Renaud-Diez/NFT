<?php
/* @var $this TeamController */
/* @var $model Team */

$this->breadcrumbs=array(
	'Teams'=>array('index'),
	$model->label,
);

$this->menu=array(
	array('label'=>'List Team', 'url'=>array('index')),
	array('label'=>'Create Team', 'url'=>array('create')),
);
?>

<div id="statusMsg" class="flash-success" style="display:none;"></div>

<div>
<h1>Team <?php echo $model->label;?></h1>
<?php $this->renderPartial('titleMenu', array('model'=>$model)); ?>
</div>

<h2>Issues</h2>
<?php 
	$dataprovider = $model->getTeamIssues();
	if($dataprovider){
		$this->widget('bootstrap.widgets.TbExtendedGridView', array(
				//'dataProvider' => $issues->search(),
				'id' => 'issue-grid',
				'dataProvider' => $model->getTeamIssues($issue),//$dataProvider,
				'filter' => $issue,//$issue,
				'type' => 'striped bordered condensed',
				'summaryText' => false,
				//'cacheTTL' => 10, // cache will be stored 10 seconds (see cacheTTLType)
				//'cacheTTLType' => 's', // type can be of seconds, minutes or hours
				'columns' => array(
						'id',
						'label',
						//array('name' => 'label', 'value' => $data->label,'header' => 'Label', 'filter' => CHtml::activeTextField($issue, 'label'),
						array('name' => 'project.label', 'value' => $data->project->label,'header' => 'Project', 'filter' => CHtml::activeDropDownList( $issue, 'project_id',
								CHtml::listData(Project::model()->findAll(array('order'=>'id')),'id', 'label'),
								array('prompt'=>'- Project -'))),
						array('name' => 'assignee.username', 'value' => $data->assignee->username,'header' => 'Assignee', 'filter' => CHtml::activeDropDownList( $issue, 'assignee_id',
								CHtml::listData(User::model()->findAll(array('order'=>'id')),'id', 'username'),
								array('prompt'=>'- Assignee -'))),
						array('name' => 'type.label', 'value' => $data->type->label,'header' => 'Type', 'filter' => CHtml::activeDropDownList( $issue, 'type_id',
								CHtml::listData(IssueType::model()->findAll(array('order'=>'id')),'id', 'label'),
								array('empty'=>'- Type -'))),
						array('name' => 'status.label', 'value' => $data->status->label,'header' => 'Status', 'filter' => CHtml::activeDropDownList( $issue, 'status_id',
								CHtml::listData(IssueStatus::model()->findAll(array('order'=>'id')),'id', 'label'),
								array('empty'=>'- Status -'))),
						array('name' => 'priority', 'value' => 'Issue::model()->getPriorities($data->priority)', 'filter' => CHtml::activeDropDownList( $issue, 'priority',
								$issue->getPriorities(),
								array('empty'=>'- Priority -'))),
						array(
								'header' => Yii::t('ses', 'Edit'),
								'class' => 'bootstrap.widgets.TbButtonColumn',
								'template' => '{view} {delete}',
								'buttons'=>array
								(
										'view' => array
										(
												'url'=>'CController::createUrl("/issue/view", array("id"=>$data->primaryKey))',
												//'url'=>'"index.php?r=leads/update&id="',
										),
										'delete' => array
										(
												'url'=>'CController::createUrl("/issue/delete", array("id"=>$data->primaryKey))',
												//'url'=>'"index.php?r=leads/update&id="',
										),
								),
		
						),
				),
		));
	}
	
?>
