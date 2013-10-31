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

$label = 'Assigned to User';
if($this->user->id == Yii::app()->user->id)
	$label = 'Assigned to me';

$arrMenu[] = array(	'buttonType' => 'button',
								'type' => $type,
								'label' => $label,
								'toggle' => true,
								'htmlOptions' => array(
										'class' => $class,
										'onClick' => 'document.location.href = "' . $url . '" + "/me/" + '.$value.';'),
						);

$url = $this->createUrl('user/' .$this->action->id . '/id/' . $this->user->id);
$toggle = false;
$value = 'true';
$class = 'btn';
$type = 'standard';
if(Yii::app()->session['criticalIssues'] == true){
	$toggle = true;
	$value = 'false';
	$class .= ' active';
	$type = 'primary';
}
$arrMenu[] = array(	'buttonType' => 'button',
		'type' => $type,
		'label' => 'Critical',
		'toggle' => $toggle,
		'htmlOptions' => array(
				'class' => $class,
				'onClick' => 'document.location.href = "' . $url . '" + "/criticalIssues/" + '.$value.';'),
);

if($this->action->id != 'gtd'){
	$url = $this->createUrl('user/' .$this->action->id . '/id/' . $this->user->id);
	$toggle = false;
	$value = 'true';
	$class = 'btn';
	$type = 'standard';
	if(Yii::app()->session['openIssues'] == true){
		$toggle = true;
		$value = 'false';
		$class .= ' active';
		$type = 'primary';
	}
	$arrMenu[] = array(	'buttonType' => 'button',
			'type' => $type,
			'label' => 'Open',
			'toggle' => $toggle,
			'htmlOptions' => array(
					'class' => $class,
					'onClick' => 'document.location.href = "' . $url . '" + "/openIssues/" + '.$value.';'),
	);
	
	$arrMenu[] = array('label' => 'GTD', 'url' => CController::createUrl('gtd', array('id'=>$this->user->id)), 'icon' => 'icon-tasks');
}
	

if($this->action->id != 'view')
	$arrMenu[] = array('label' => 'Issues', 'url' => CController::createUrl('view', array('id'=>$this->user->id)), 'icon' => 'icon-tasks');

$this->widget(
		'bootstrap.widgets.TbButtonGroup',
		array(
				//'type' => 'info',
				'buttons' => $arrMenu,
		)
);

$this->widget(
		'bootstrap.widgets.TbButtonGroup',
		array(
				//'type' => 'primary',
				'buttons' => array(
						array(	'label' => 'Statistics',
								'items' => array(
										array('label' => 'Issues', 'url' => array('view', 'id' => $model->id)),
										'---',
										array('label' => 'Weekly Report', 'url' => array('weeklyReport', 'id' => $model->id)),
										array('label' => 'Workload', 'url' => array('workload', 'id' => $model->id)),
								)
						),
				),
		)
);
?>
</div>