<div class="text-right" style="margin-top:-50px;padding-bottom: 15px;">
<?php 
$url = $this->createUrl('user/' .$this->action->id . '/id/' . $this->user->id);
$toggle = false;
$value = 'true';
$class = 'btn';
$type = 'standard';
if(Yii::app()->session['myIssues'] == true){
	$toggle = true;
	$value = 'false';
	$class .= ' active';
	$type = 'primary';
}
$this->widget(
		'bootstrap.widgets.TbButtonGroup',
		array(
				//'type' => 'info',
				'buttons' => array(
						array(	'buttonType' => 'button',
								'type' => $type,
								'label' => 'Only assigned issues',
								'toggle' => true,
								'htmlOptions' => array(
										'class' => $class,
										'onClick' => 'document.location.href = "' . $url . '" + "/me/" + '.$value.';'),
						),
				),
		)
);
?>
</div>