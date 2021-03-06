<?php /* @var $this Controller */ ?>
<?php $this->beginContent('//layouts/main'); ?>

<script>
function reloadGrid(data) { 
	$.fn.yiiListView.update('user-grid');
	$('#statusMsg').html(data).fadeIn().animate({opacity: 1.0}, 15000).fadeOut("slow");
}
</script>

<div class="span-5 last">

	<div id="sidebar">
	<?php 

			$this->beginWidget('zii.widgets.CPortlet', array(
				'title'=>'',
			));
			
			$label = 'I\'m on It!';
			$type = 'success';
			if($this->issue->assignee_id == Yii::app()->user->id){
				$label = 'I\'m not on it anymore!';
				$type = 'danger';
			}
				
			
			$this->widget(
				'bootstrap.widgets.TbButton',
				array(
				'id' => 'button-Imonit',
				'label' => $label,
				'type' => $type,
				'buttonType' => 'ajaxLink',
				'block' => true,
				'url' => $this->createUrl('issue/imonit/id/'.$this->issue->id),
				'ajaxOptions' => array('success'=>'function(r){;reloadInfo(r);return false;}'),
				'htmlOptions' => array('name' => 'button-Imonit'),
				)
				);

			$this->endWidget();
	
	?>
	</div>
	
	
	<div id="sidebar">
	<?php
		$this->beginWidget('zii.widgets.CPortlet', array(
			'title'=>'Transitions',
		));
		
		$arrTransitions = array('id' => 'transition-grid',
								'ajaxUpdate'=>true,
								'dataProvider' => $this->issue->getTransitions(),
								'itemView' => 'partials/_transition',
								'enableSorting' => true,
								'viewData' => array('model' => IssueTransition::model()));
		
		
		if(!empty($arrTransitions))
		{
			$this->widget('zii.widgets.CListView', $arrTransitions);
		}
		
		$this->endWidget();
	?>
	</div><!-- sidebar -->
	
	<div id="sidebar">
	<?php

			$this->beginWidget('zii.widgets.CPortlet', array(
				'title'=>'Relationships',
			));
			
			$arrRelated = array('id' => 'related-grid',
								'ajaxUpdate'=>true,
								'dataProvider' => $this->issue->getRelatedIssues(),
								'itemView' => '_relatedIssues',
								'enableSorting' => true,
								'viewData' => array('model' => $this->issue));
			
			if(!empty($arrRelated))
			{
				$this->widget('zii.widgets.CListView', $arrRelated);
			}

	?>
	
	<div style="text-align: right;margin-top:-17px;";><a href="#" onclick=';relatedJS();$("#dialogRelated").dialog("open"); return false;'>New Relation</a></div>
	
	<?php 
	$this->endWidget();
	?>
	</div><!-- sidebar -->
	<div id="sidebar">
		<?php
			$this->beginWidget('zii.widgets.CPortlet', array(
				'title'=>'Participants',
			));
			
			$arrParticipants = array('id' => 'participants-grid',
							'ajaxUpdate'=>true,
							'dataProvider' => $this->issue->getParticipants(),
							'itemView' => '_participants',
							'enableSorting' => true,
							'viewData' => array('model' => $this->issue));
			
			if(!empty($arrParticipants))
			{
				$this->widget('zii.widgets.CListView', $arrParticipants);
			}
			
		?>
		<div style="text-align: right;margin-top:-10x;";>
		<?php
			echo CHtml::ajaxLink('Add Participants',
	        						$this->createUrl('issue/setParticipant/id/'.$this->issue->id),
	        						array('success'=>'function(r){$("#juiDialog").html(r).dialog("open"); return false;}')
								);
		?>
		</div>
		<?php 
			$this->endWidget();
		?>
	
		<?php
			$this->beginWidget('zii.widgets.jui.CJuiDialog', array( // the dialog
			    'id'=>'juiDialog',
			    'options'=>array(
			        'title'=>'Add Participants',
			        'autoOpen'=>false,
			        'modal'=>true,
			        'width'=>'760',
                    'height'=>'auto',
					'close' => 'js:function(){location.reload();}',
			    ),
			));
			
		?>
		 
		<?php $this->endWidget();?>
		

	</div><!-- sidebar -->
	
	<div id="sidebar">
	<?php
		$this->beginWidget('zii.widgets.CPortlet', array(
			'title'=>'Workload',
		));
		
		$arrWorkload = $this->issue->getWorkload();
		
		if($arrWorkload)
		{
			echo $this->renderPartial('_HCTimetrackerPie', array('data' => $arrWorkload), true);
		}
		
		$this->endWidget();
	?>
	</div><!-- sidebar -->
	
	<div id="sidebar">
	<?php
		$this->beginWidget('zii.widgets.CPortlet', array(
			'title'=>'Documents',
		));
		
		if(!empty($this->arrDocuments))
		{
			$this->widget('zii.widgets.CListView', $this->arrDocuments);
		}
		
		echo 'TODO: Add Document';
		
		$this->endWidget();
	?>
	</div><!-- sidebar -->
</div>

<div class="span-19">
	<div id="content">
		<div id="statusMsg" class="flash-success" style="display:none;"></div>
		
		<?php echo $content; ?>
	</div><!-- content -->
</div>


<?php $this->endContent(); ?>
