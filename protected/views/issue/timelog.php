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
?>

<div>
	<h1><?php echo $model->type->label;?> #<?php echo $model->id; ?></h1>
	
	<div class="text-right" style="margin-top:-50px;padding-bottom: 10px;">		
		<div style="">
		<?php 
		$this->widget(
					'bootstrap.widgets.TbButtonGroup',
					array(
					//'type' => 'info',
					'buttons' => array(
					array('label' => 'Comment', 'url' => array('comment', 'id' => $model->id), 'icon' => 'icon-pencil'),
					array('label' => 'Log Time', 
							'url' => '#',
							'htmlOptions' => array('onclick'=>';logTime();$("#dialogTimetracker").dialog("open");return false;'),
							'icon' => 'icon-time'),
					array('label' => 'New sub-isue', 'url' => array('create', 'parent_id'=>$model->id), 'icon' => 'icon-tasks'),
					),
					)
					);	
		
		$this->widget(
				'bootstrap.widgets.TbButtonGroup',
					array( 
					//'type' => 'primary',
					'buttons' => array(
					array(	'label' => 'More',
							'items' => array(
											array('label' => 'Update', 'url' => array('update', 'id' => $model->id), 'icon' => 'icon-pencil'),
											'---',
											array('label' => 'VIEWS'),
											array('label' => 'Detail', 'url' => array('view', 'id' => $model->id)),
											array('label' => 'Event History', 'url' => '#'),
											array('label' => 'Time Logs', 'url' => array('timelog', 'id' => $model->id)),
											'---',
											array('label' => 'Delete', 'url' => '#', 'icon' => 'icon-trash'),
										)
							),
					),
				)
			);
		?>
		</div>
		
	</div>
</div>

<h3><?php echo $model->label;?> <small>Created by <?php echo $model->user->username;?></small></h3>

<?php 
	$this->widget('zii.widgets.jui.CJuiAccordion', array(
	    'panels'=>array(
		'Time Log by Activities'=>$this->renderPartial('partials/_HCActivities', array('type' => 'issue-type', 'model' => $model), true),
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
	$arrLog = array('id' => 'related-grid',
							'ajaxUpdate'=>true,
							'dataProvider' => $timeLog,
							'itemView' => 'partials/_timelog',
							'enableSorting' => true,
							'viewData' => array('model' => $model));
	$this->widget('zii.widgets.CListView', $arrLog);
?>

<?php $this->renderPartial('_sidebar', array('model'=>$model)); ?>