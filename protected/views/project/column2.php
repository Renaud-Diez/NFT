<?php /* @var $this Controller */ ?>
<?php $this->beginContent('//layouts/main'); ?>

<?php Yii::app()->clientScript->registerScript('initUnregisterLinks',<<<JS
    $('body').on('click','.lnkButton', function(e) {
   		e.preventDefault();
   		$.post($(this).attr('href'), function(data) {
            reloadGrid(data);
        });
    });
JS
, CClientScript::POS_READY);
?>

<script>
function reloadGrid(data) { 
	$.fn.yiiListView.update('user-grid');
	$('#statusMsg').html(data).fadeIn().animate({opacity: 1.0}, 15000).fadeOut("slow");
}
</script>

<div class="span-5">
	<div id="hidden" style="display:none;">
	<?php 
		$this->widget('zii.widgets.CMenu', array(
			'items'=>$this->hiddenMenu,
			'htmlOptions'=>array('class'=>'operations'),
		));
	?>
	</div>
	
	<?php if(!isset($this->project)):?>
	 	<div id="sidebar" style="margin-bottom: -20px;">
	 		<?php 
	 			$form = $this->beginWidget(
				'bootstrap.widgets.TbActiveForm',
				array(
				'id' => 'searchForm',
				'type' => 'search',
				'htmlOptions' => array('class' => 'well', 'style' => 'width: 268px;'),
				)
				);
				echo $form->textFieldRow(
				Project::model(),
				'label',
				array(
				'class' => 'input-medium',
				'prepend' => '<i class="icon-search"></i>',
				'style' => 'width: 178px;'
				)
				);
				$this->widget(
				'bootstrap.widgets.TbButton',
				array('buttonType' => 'submit', 'label' => 'Go')
				);
				 
				$this->endWidget();
				unset($form);
	 		?>
	 	</div>
	<?php endif;?>
	
	<?php if($this->search):?>
		<div id="sidebar" style="margin-bottom: -20px;">
	 		<?php 
	 			$form = $this->beginWidget(
				'bootstrap.widgets.TbActiveForm',
				array(
				'id' => 'searchForm',
				'type' => 'form',
				'htmlOptions' => array('class' => 'well', 'style' => 'width: 268px;'),
				)
				);

	 			echo 'From date:';
				echo '<div class="input-prepend"><span class="add-on"><i class="icon-calendar"></i></span>';
				$this->widget('zii.widgets.jui.CJuiDatePicker',array(
						'model'=>$this->search,                                // Model object
						'attribute'=>'from', // Attribute name
						'options'=>array('dateFormat' => 'yy-mm-dd', 'onSelect' => 'js:function(){checkDate();}'),                     // jquery plugin options
				));
				echo '</div>';
				
				echo '<br />To date:';
				echo '<div class="input-prepend"><span class="add-on"><i class="icon-calendar"></i></span>';
				$this->widget('zii.widgets.jui.CJuiDatePicker',array(
						'model'=>$this->search,                                // Model object
						'attribute'=>'to', // Attribute name
						'options'=>array('dateFormat' => 'yy-mm-dd', 'onSelect' => 'js:function(){checkDate();}'),                     // jquery plugin options
						//'htmlOptions'=>array('prepend' => '<i class="icon-calendar"></i>') // HTML options
				));
				echo '</div>';
				
				/*echo 'User name:';
				echo '<div class="input-prepend"><span class="add-on"><i class="icon-search"></i></span>';
				echo $form->textField($this->search,'name',array('style'=>45,'maxlength'=>45));
				echo '</div><br />';*/

				echo '<br />';
				$this->widget(
				'bootstrap.widgets.TbButton',
				array('buttonType' => 'submit', 'label' => 'Filter', 'block' => true)
				);
				 
				$this->endWidget();
				unset($form);
	 		?>
	 </div>
	<?php endif;?>
	
	<?php if(in_array($this->action->id, array('gtd', 'kanban', 'issues'))):?>
		<div id="sidebar" style="margin-bottom: 0px;">
			<?php
			$this->beginWidget('zii.widgets.CPortlet', array(
				'title'=>'Issue Filter',
			));
				
			if(isset($this->project))
				$url = $this->createUrl('project/' .$this->action->id . '/id/' . $this->project->id);
			else
				$url = $this->createUrl('project/' .$this->action->id);
				
			/*$value = false;
			if($_GET['me'] == 'true')
				$value = true;
				$this->widget('bootstrap.widgets.TbToggleButton', 
				array(	'name' => 'scope',
						'enabledLabel' => 'Mine',
						'disabledLabel' => 'All',
						'value' => $value,
						'onChange' => 'js:function($elem, status, e){document.location.href = "' . $url . '" + "/me/" + status;}'
				));*/
				
			$this->endWidget();
			?>
		</div>
	<?php endif;?>
	
	
	<?php if(!isset($this->project))
	{
			echo '<div id="sidebar">';
			$this->beginWidget('zii.widgets.CPortlet', array(
			'title'=>'Views',
		));
		$this->widget('zii.widgets.CMenu', array(
			'items'=>$this->viewMenu,
			'htmlOptions'=>array('class'=>'operations'),
		));
		
		$arrTopic = $this->getTopic();
		if(!empty($arrTopic))
		{
			$this->widget('zii.widgets.CListView', $arrTopic);
			if($_GET['v'])
				$url = Yii::app()->createUrl('/project/index', array('v' => $_GET['v']));
			else
				$url = Yii::app()->createUrl('/project/index');
				
			$value = 'NONE';
			if($_GET['topic'] == $value)
				$value = 'ALL';
			
			echo '<input class="topic" type="checkbox" value="'.$value.'" onClick="javascript:topicFilter(\''.$url.'\');"></input> ' . $value;
		}
		
		$this->endWidget();
		echo '</div><!-- sidebar -->';
		}
	?>
	
	
	<div id="sidebar">
	<?php if(isset($this->project)){
			$this->beginWidget('zii.widgets.CPortlet', array(
				'title'=>'Relationships',
			));
			
			$treedata = $this->project->getSubprojects($this->project->id);
			
			$this->widget('CTreeView',array(
			        'data'=>$treedata,
			        'animated'=>'fast', //quick animation
			        'collapsed'=>false,//remember must giving quote for boolean value in here
			        'htmlOptions'=>array(
			                //'class'=>'treeview-famfamfam',//there are some classes that ready to use
			                'class' => 'filetree',
			        ),
			));
			
			$arrRelated = array('id' => 'related-grid',
							'ajaxUpdate'=>true,
							'dataProvider' => $this->project->getRelatedProject(),
							'itemView' => '_relatedProject',
							'enableSorting' => true,
							'viewData' => array('model' => $this->project));
			if(!empty($arrRelated))
			{
				$this->widget('zii.widgets.CListView', $arrRelated);
			}
	}
	if(isset($this->project)):?>
	
	<div style="text-align: right;margin-top:-17px;";><a href="#" onclick=';relatedJS();$("#dialogRelated").dialog("open"); return false;'>New Relation</a></div>
	
	<?php endif;?>
	
	<?php 
	if(isset($this->project)){$this->endWidget();}
	?>
	</div><!-- sidebar -->
	
	
	<div id="sidebar">
	<?php
		$steps = 'Phases';
		if(!empty($this->project->topic->steps))
			$steps = $this->project->topic->steps;
		$stepsLabel = ucfirst($steps). ' & Milestones';
		$this->beginWidget('zii.widgets.CPortlet', array(
			'title'=>$stepsLabel,
		));
		
		if(!empty($this->arrVersions))
		{
			$this->widget('zii.widgets.CListView', $this->arrVersions);
		}
		
		$this->endWidget();
	?>
	</div><!-- sidebar -->
	<div id="sidebar">
		<?php
			$this->beginWidget('zii.widgets.CPortlet', array(
				'title'=>'Members',
			));
			
			
			if(!empty($this->arrMembers))
			{
				$this->widget('zii.widgets.CListView', $this->arrMembers);
			}
			echo $this->addMemberLink;
			$this->endWidget();
		?>
	
		<?php
			$this->beginWidget('zii.widgets.jui.CJuiDialog', array( // the dialog
			    'id'=>'juiDialog',
			    'options'=>array(
			        'title'=>'Add Members',
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
</div>

<div class="span-19">
	<div id="content">
		<div id="statusMsg" class="flash-success" style="display:none;"></div>
		
		<?php echo $content; ?>
	</div><!-- content -->
</div>

<?php $this->endContent(); ?>