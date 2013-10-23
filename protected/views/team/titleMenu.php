<div class="text-right" style="margin-top:-50px;padding-bottom: 15px;">
<?php 
$this->widget(
		'bootstrap.widgets.TbButtonGroup',
		array(
				//'type' => 'info',
				'buttons' => array(
						array('label' => 'Update', 'url' => array('update', 'id' => $model->id), 'icon' => 'icon-pencil'),
						array('label' => 'Add Members', 'url' => '#', 'htmlOptions'=>array('onclick'=>'js:jQuery.ajax({\'success\':function(r){$("#juiDialog").html(r).dialog("open"); return false;},\'url\':\'/team/membership/id/'.$model->id.'\',\'cache\':false})'), 'icon' => 'icon-user'),
						array('label' => 'Issues', 'url' => array('issues', 'id' => $model->id), 'icon' => 'icon-list'),
						array('label' => 'GTD', 'url' => array('gtd', 'id' => $model->id), 'icon' => 'icon-ok'),
				),
		)
);

$this->widget(
		'bootstrap.widgets.TbButtonGroup',
		array(
				//'type' => 'primary',
				'buttons' => array(
						array(	'label' => 'Statistics',
								'items' => array(
										array('label' => 'Membership', 'url' => array('view', 'id' => $model->id)),
										'---',
										array('label' => 'Weekly Report', 'url' => array('weekly', 'id' => $model->id)),
										array('label' => 'Workload', 'url' => array('workload', 'id' => $model->id)),
								)
						),
				),
		)
);

$this->beginWidget('zii.widgets.jui.CJuiDialog', array( // the dialog
		'id'=>'juiDialog',
		'options'=>array(
				'title'=>'Team Membership',
				'autoOpen'=>false,
				'modal'=>true,
				'width'=>'760',
				'height'=>'auto',
				'close' => 'js:function(){location.reload();}',
		),
));

$this->endWidget();
?>
</div>