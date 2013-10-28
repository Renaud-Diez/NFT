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

$arrMenu[] = array(	'buttonType' => 'button',
								'type' => $type,
								'label' => 'Only assigned issues',
								'toggle' => true,
								'htmlOptions' => array(
										'class' => $class,
										'onClick' => 'document.location.href = "' . $url . '" + "/me/" + '.$value.';'),
						);

if($this->action->id != 'gtd')
	$arrMenu[] = array('label' => 'GTD', 'url' => CController::createUrl('gtd', array('id'=>$this->user->id)), 'icon' => 'icon-tasks');

if($this->action->id != 'view')
	$arrMenu[] = array('label' => 'Issues', 'url' => CController::createUrl('view', array('id'=>$this->user->id)), 'icon' => 'icon-tasks');

$this->widget(
		'bootstrap.widgets.TbButtonGroup',
		array(
				//'type' => 'info',
				'buttons' => $arrMenu,
		)
);
?>
</div>