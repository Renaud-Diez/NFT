<?php
/* @var $this UserController */
/* @var $data User */
?>

<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('username')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->username), array('view', 'id'=>$data->id)); ?>
	<br />
	
	<div class="row-fluid">
		<div class="span6">
		<?php
			$arrdata = $data->getActivities($this->search);
			if($arrdata)
				$this->renderPartial('_HCactivities', array('data'=>$arrdata));
		?>
		</div>
		
		<div class="span6">
		<?php 
			$dataProvider = $data->getActivityDetail($this->search);

			if($dataProvider)
			{	
				$this->widget('bootstrap.widgets.TbExtendedGridView', array(
				'id' => 'issue-grid',
			    'dataProvider' => $dataProvider,
			    'type' => 'striped bordered condensed',
			    'summaryText' => false,
				'columns' => array(
								'time_spent',
								'activity',
								'issue',
								'project',
							)
				));
			}
		?>
		</div>
	</div>

</div>