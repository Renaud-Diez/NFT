<div class="text-right" style="margin-top:-50px;padding-bottom: 15px;">
	<?php 
		if(isset($this->project))
			$url = $this->createUrl('project/' .$this->action->id . '/id/' . $this->project->id);
		else
			$url = $this->createUrl('project/' .$this->action->id);
				
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
					array('label' => 'Update', 'url' => CController::createUrl('update', array('id'=>$model->id)), 'icon' => 'icon-pencil'),
					array('label' => 'New Issue', 'url' => CController::createUrl('/issue/create', array('pid'=>$model->id)), 'icon' => 'icon-fire'),
					array(	'buttonType' => 'button',
						'type' => $type,
						'label' => 'My Issues only',
						'toggle' => true,
						'htmlOptions' => array(
							'class' => $class,
							'onClick' => 'document.location.href = "' . $url . '" + "/me/" + '.$value.';'),
					),
					array('label' => 'New sub-project', 'url' => CController::createUrl('create', array('parent_id'=>$model->id)), 'icon' => 'icon-tasks'),
					),
					)
					);
					
		
					
		$this->widget(
				'bootstrap.widgets.TbButtonGroup',
					array( 
					//'type' => 'primary',
					'buttons' => array(
					array(	'label' => 'More',
							'items' => array(
											array('label' => 'VIEWS'),
											array('label' => 'Detail', 'url' => array('view', 'id' => $model->id)),
											array('label' => 'Event History', 'url' => '#'),
											array('label' => 'Time Logs', 'url' => array('timelog', 'id' => $model->id)),
											'---',
											array('label' => 'Delete', 'url' => '#', 'icon' => 'icon-trash'),
										)
							),
					),
				)
			);
			
		
	?>
</div>