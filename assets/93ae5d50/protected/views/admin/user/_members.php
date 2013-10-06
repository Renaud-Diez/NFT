<?php
/* @var $this UserController */
/* @var $data User */
?>

<div class="view">
	<b><?php echo CHtml::encode($data->getAttributeLabel('username')); ?>:</b>
	<?php echo CHtml::encode($data->username, array('view', 'id'=>$data->id)); ?>
	<br />
	
	<b><?php echo CHtml::encode($data->getAttributeLabel('email')); ?>:</b>
	<?php echo CHtml::encode($data->email); ?> <br />
	
	<b>Role:</b>
	<?php echo CHtml::encode($data->projectUsers[0]->role); ?> 

	<?php
	/*echo CHtml::ajaxLink(
		  "Unregister member",
	Yii::app()->createUrl( 'project/ajaxupdate' ),
	array( // ajaxOptions
		    'type' => 'POST',
		    'beforeSend' => "function( request )
		                     {
		                       // Set up any pre-sending stuff like initializing progress indicators
		                       if(confirm('Are You Sure ...')) return true; return false;
		                     }",
		    'success' => "function( data )
		                  {
		                    // handle return data
		                    reloadGrid( data );
		                  }",
		    'data' => array( 'act' => 'doDelete', 'id' => 1, 'user_id' => $data->id )
	),
	array( //htmlOptions
		    'href' => Yii::app()->createUrl( 'project/ajaxupdate' ),
		    'class' => $class
	)
	);*/

	if($model->checkAccess('Project.Setmembers'))
	{
		echo '<br />';
		echo CHtml::link(
			  "Unregister member",
		array( 'project/ajaxupdate', 'act' => 'doDelete', 'id' => $model->id, 'user_id' => $data->id ),
		array( //htmlOptions
			    'class' => 'lnkButton')
		);
	}
	?>
<br />

</div>
