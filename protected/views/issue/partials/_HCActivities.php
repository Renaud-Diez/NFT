<div class="row-fluid">
		<div class="span6">
		<?php
			$arrWorkload = $model->getWorkload();
			if($arrWorkload)
				echo $this->renderPartial('partials/_HCTimetrackerPie', array('data' => $arrWorkload), true);
		?>
		</div>
		
		<div class="span6">
		<?php 
			$dataActivities = $model->getActivityDetail();

			if($dataActivities)
			{	
				$this->widget('bootstrap.widgets.TbExtendedGridView', array(
				'id' => 'issue-grid',
			    'dataProvider' => $dataActivities,
			    'type' => 'striped bordered condensed',
			    'summaryText' => false,
				'columns' => array(
								'time_spent',
								'activity',
							)
				));
			}
		?>
		</div>
</div>