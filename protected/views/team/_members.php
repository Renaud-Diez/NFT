<?php
/* @var $this UserController */
/* @var $data User */
?>

<div class="view">
	<div>
	<b><?php echo CHtml::encode($data->getAttributeLabel('username')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->username), array('/user/view', 'id'=>$data->id)); ?>
	<br />
	
	<b><?php echo CHtml::encode($data->getAttributeLabel('email')); ?>:</b>
	<?php echo CHtml::encode($data->email); ?>
	
		<div class="text-right" style="margin-top:-40px; margin-bottom:20px;">
			<a href="<?php echo CController::createUrl('/user/view', array('id'=>$data->id))?>">
				<i class="icon-eye-open"></i>
			</a>
				<?php //if($model->checkAccess('Project.Setmembers')){
	
						echo CHtml::ajaxLink(
						  '<i class="icon-trash"></i>',
						  Yii::app()->createUrl( 'team/setMembership', array('id' => $model->id) ),
						  array( // ajaxOptions
						    'type' => 'POST',
						    'success' => "function( data )
						                  {
						                    reloadList( data, 'members-grid' ); return false;
						                  }",
						    'data' => array( 'act' => 'unregister', 'user_id' => $data->id)
						  ),
						  array( //htmlOptions
						    'href' => Yii::app()->createUrl( 'team/setMembership', array('id' => $model->id)  ),
						    'class' => 'lnkButton'
						  )
						 );
				//}?>
		</div>
	</div>

</div>

<script type="text/javascript">
function reloadList(data, grid)
{
	$.fn.yiiListView.update(grid);
	$('#statusMsg').html(data).fadeIn().animate({opacity: 1.0}, 15000).fadeOut("slow");
}
</script>
