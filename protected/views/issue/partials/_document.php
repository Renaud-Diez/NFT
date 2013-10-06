<div class="view">

	<?php 
		$title = strtoupper(substr($data->document->path, -4)) . ' file posted @ ' . Yii::app()->dateFormatter->format('y-MM-d', $data->document->creation_date);
		
		echo CHtml::ajaxLink(	CHtml::encode($data->document->label),
	        					$this->createUrl('Document/file/id/'.$data->document_id),
	        					array('success'=>'function(r){$("#fileDialog").html(r).dialog("open"); return false;}',),
	        					array(	'class' => 'ipopover',
	        							'data-title' => $title,
	        							'data-placement' => 'left',
	        							'data-content' => '<b>Creator: ' . strtoupper($data->document->user->username) . '</b><br /><i>' . $data->document->comment . '</i>',
	        							'data-toggle' => 'popover',
	        							'data-trigger' => 'hover',
	        							)
							);
	?>
	
</div>