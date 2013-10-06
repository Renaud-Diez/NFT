<?php 
	//$arrEffort = $model->estimatedRemainingEffort();
	$arrEffort = $model->todayCompletion();
	$class = false;
	if($arrEffort->overrun > 0){
		$class = 'alert in alert-block fade alert-error';
		$title = 'Overrun of ' . $arrEffort->overrun . ' hours';
		$message = 'Project completed at <i>' . $arrEffort->completion . '%</i> in <i>' . $arrEffort->spent_time . ' hours</i> instead of the <i>' . $arrEffort->theorical_effort . ' hours</i> originaly scheduled.';
		$message .= '<br />According to current logged effort, the remaining effort for the resting <i>'.(100-$arrEffort->completion).'%</i> should be increased to <i>' . ($arrEffort->estimated_remaining_effort-$arrEffort->spent_time) . ' hours</i>';
		
	}
	
	if($class)
		echo $this->renderPartial('partials/_messageAlert', array('class'=> $class, 'title' => $title, 'message' => $message), true);
?>

<?php 
	$remainingBudget = $model->remainingBudget();
	$class = false;
	if($remainingBudget < 0){
		$class = 'alert in alert-block fade alert-error';
		$title = 'Budget has been entirely consumed!';
		$message = 'Your Project Budget is now of <i>' . $remainingBudget . '</i> hours';
	}
	else{
		$estimatedRemainingBudget = $model->estimatedRemainingBudget();
		if($estimatedRemainingBudget < 0){
			$class = 'alert in alert-block fade alert-error';
			$title = 'Insufficient Budget to end the Project';
			$message = 'According to captured data, your Project Budget will be overrun of <i>' . ($estimatedRemainingBudget*-1) . '</i> hours';
		}
	}
	
	if($class)
		echo $this->renderPartial('partials/_messageAlert', array('class'=> $class, 'title' => $title, 'message' => $message), true);
?>

<?php 
	$arr = $model->remainingEffortVsTime();
	$availableTime = (($arr['availableTime']['d']*8)+$arr['availableTime']['h']);
	$class = false;
	if($arr['remainingTime'] > $availableTime){
		$class = 'alert in alert-block fade alert-error';
		$title = 'Insufficient available time!';
		if($arr['availableTime']['d'] > 0)
			$msg = '<i>' . $arr['availableTime']['d'] . '</i> days and ';
		$message = 'The available time to end the Project is about ' .$msg. ' <i>' . $arr['availableTime']['h'] . '</i> hours';
		$message .= '<br />The estimated remaining effort is evaluated to: <i>' . $arr['remainingTime'] . '</i> hours';
	}
	
	if($class)
		echo $this->renderPartial('partials/_messageAlert', array('class'=> $class, 'title' => $title, 'message' => $message), true);
?>