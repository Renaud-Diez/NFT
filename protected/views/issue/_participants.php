<?php
/* @var $this UserController */
/* @var $data User */
?>

<div class="view">
	<b><?php echo CHtml::encode($data->getAttributeLabel('username')); ?>:</b>
	<?php echo CHtml::encode($data->username, array('view', 'id'=>$data->id)); ?>
	<br />
	
	<b><?php echo CHtml::encode($data->getAttributeLabel('email')); ?>:</b>
	<?php echo CHtml::encode($data->email); ?>

	<?php
	//if($model->checkAccess('Project.Setmembers'))
	//{
		echo '<br />';
		echo CHtml::ajaxLink(
		  'Unregister member',
		  Yii::app()->createUrl( 'issue/ajaxSetParticipant', array('id' => $model->id) ),
		  array( // ajaxOptions
		    'type' => 'POST',
		    'success' => "function( data )
		                  {
		                    reloadList( data, 'participants-grid' ); return false;
		                  }",
		    'data' => array( 'act' => 'unregister', 'user_id' => $data->id)
		  ),
		  array( //htmlOptions
		    'href' => Yii::app()->createUrl( 'issue/ajaxSetParticipant', array('id' => $model->id)  ),
		    'class' => 'lnkButton'
		  )
		 );
	//}
	?>
<br />

</div>
