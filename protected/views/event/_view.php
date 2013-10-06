<?php 
if($GLOBALS['eventDate'] != Yii::app()->dateFormatter->format('y-MM-d', $data->creation_date))
	echo '<h4>'.Yii::app()->dateFormatter->formatDateTime($data->creation_date, 'medium', null).'</h4>';

$GLOBALS['eventDate'] = Yii::app()->dateFormatter->format('y-MM-d', $data->creation_date);

?>
<div class="view">
	<small>
	<i>@ <?php echo Yii::app()->dateFormatter->format('H:mm:ss', $data->creation_date);?> by <?php echo $data->user->username;?></i>
	<br />
		<div class="text-right" style="margin-top:-20px;padding-bottom: 10px;">
			<a href="<?php 
							if($data->ref_object == 'Issue')
								$url = CController::createUrl('/issue/view', array('id'=>$data->ref_id));
							elseif($data->ref_object == 'Version')
								$url = CController::createUrl('/project/roadmap', array('id'=>$data->project_id,'version' => $data->ref_id));
							echo $url;?>">
				<i class="icon-eye-open"></i>
				View
			</a>
		</div>
	<?php echo $data->description; ?>
	<br />
	</small>
</div>