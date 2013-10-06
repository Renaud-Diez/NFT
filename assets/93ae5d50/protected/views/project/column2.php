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
			'title'=>'Versions & Milestones',
		));
		$this->widget('zii.widgets.CMenu', array(
			'items'=>$this->versionMenu,
			'htmlOptions'=>array('class'=>'operations'),
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