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
	//$.fn.yiiListView.update('user-grid');
	$('#statusMsg').html(data).fadeIn().animate({opacity: 1.0}, 15000).fadeOut("slow");
}
</script>


<div class="span-5 last">
	<div id="hidden" style="display:none;">
	<?php 
		$this->widget('zii.widgets.CMenu', array(
			'items'=>$this->hiddenMenu,
			'htmlOptions'=>array('class'=>'operations'),
		));
	?>
	</div>
	<div id="sidebar">
	<?php
		$this->beginWidget('zii.widgets.CPortlet', array(
			'title'=>'Operations',
		));
		$this->widget('zii.widgets.CMenu', array(
			'items'=>$this->menu,
			'htmlOptions'=>array('class'=>'operations'),
		));
		$this->endWidget();
	?>
	</div><!-- sidebar -->
	<div id="sidebar">
	<?php
		$this->beginWidget('zii.widgets.CPortlet', array(
			'title'=>'Issues',
		));
		$this->widget('zii.widgets.CMenu', array(
			'items'=>$this->issueMenu,
			'htmlOptions'=>array('class'=>'operations'),
		));
		$this->endWidget();
	?>
	</div><!-- sidebar -->
	<div id="sidebar">
	<?php
		$this->beginWidget('zii.widgets.CPortlet', array(
			'title'=>'Verions & Milestones',
		));
		$this->widget('zii.widgets.CMenu', array(
			'items'=>$this->versionMenu,
			'htmlOptions'=>array('class'=>'operations'),
		));
		$this->endWidget();
	?>
	</div><!-- sidebar -->
	<div id="sidebar">
		<?php
			$this->beginWidget('zii.widgets.CPortlet', array(
				'title'=>'Members',
			));
			/*$this->widget('zii.widgets.CMenu', array(
				'items'=>$this->memberMenu,
				'htmlOptions'=>array('class'=>'operations'),
			));*/
			
			if(!empty($this->arrMembers))
			{
				$this->widget('zii.widgets.CListView', $this->arrMembers);
			
				/*echo CHtml::ajaxLink('Add Members',
	        						$this->createUrl('project/setmembers/id/'.$model->id),
	        						array('success'=>'function(r){$("#juiDialog").html(r).dialog("open"); return false;}'),
	        						array('id'=>'showJuiDialog') // not very useful, but hey...
								);*/
				echo CHtml::link('Add members', "",  // the link for open the dialog
	    							array(
	        								'style'=>'cursor: pointer; text-decoration: underline;',
	        								'onclick'=>"{;addMembers();$('#juiDialog').dialog('open');}"
	    							)
	    						);
			}
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
		<div class="divForForm"></div>
		 
		<?php $this->endWidget();?>
		
		<script type="text/javascript">
		// here is the magic
		function addMembers()
		{
		    
			<?php echo CHtml::ajax(array(
		            'url'=>array('project/members/id/1'),
		            'data'=> "js:$(this).serialize()",
		            'type'=>'post',
		            'dataType'=>'json',
					'success'=>"function(data)
					            {
					                if (data.status == 'failure')
					                {
					                    $('#juiDialog div.divForForm').html(data.div);
					                          // Here is the trick: on submit-> once again this function!
					                    $('#juiDialog div.divForForm form').submit(addMembers);
					                }
					                else
					                {
					                    $('#juiDialog div.divForForm').html(data.div);
					                    setTimeout(\"$('#juiDialog').dialog('close') \",3000);
					                    //$.fn.yiiGridView.update('non-user-grid');
					                }
					 
					            } "
		            ))
		     ?>;
		    return false; 
		 
		}
		 
		</script>
	</div><!-- sidebar -->
</div>
<div class="span-19">
	<div id="content">
		<div id="statusMsg" class="flash-success" style="display:none;"></div>
		
		<?php echo $content; ?>
	</div><!-- content -->
</div>


<?php $this->endContent(); ?>