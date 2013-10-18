<div class="text-right" style="margin-top:-50px;padding-bottom: 15px;">
<?php 
$this->widget(
		'bootstrap.widgets.TbButtonGroup',
		array(
				//'type' => 'info',
				'buttons' => array(
						array('label' => 'Add Members', 'url' => '#', 'htmlOptions'=>array('onclick'=>'js:jQuery.ajax({\'success\':function(r){$("#juiDialog").html(r).dialog("open"); return false;},\'url\':\'/team/membership/id/'.$model->id.'\',\'cache\':false})')),
						//
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