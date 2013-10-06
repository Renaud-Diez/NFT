<?php /* @var $this Controller */ ?>
<?php $this->beginContent('//layouts/main'); ?>

<script>
function reloadGrid(data) { 
	$.fn.yiiListView.update('user-grid');
	$('#statusMsg').html(data).fadeIn().animate({opacity: 1.0}, 15000).fadeOut("slow");
}
</script>

<div class="span-5 last">

	<?php 
	$highlightedProject = array('id' => 'owner-project-grid',
							'ajaxUpdate'=>true,
							'dataProvider' => $this->user->registeredInProject(false, true),
							'itemView' => 'partials/_project',
							'enableSorting' => true,
							'viewData' => array('model' => $model));
	
	if(!empty($highlightedProject)): ?>	
		<div id="sidebar">
		<?php
	
				$this->beginWidget('zii.widgets.CPortlet', array(
					'title'=>'Favorite Project(s)',
				));
				
				unset($GLOBALS['project']);
	
				$this->widget('zii.widgets.CListView', $highlightedProject);
	
	
			$this->endWidget();
		?>
		</div><!-- sidebar -->
	<?php endif;?>
	
	<div id="sidebar">
	<?php
			$ownerOfProject = array('id' => 'owner-project-grid',
							'ajaxUpdate'=>true,
							'dataProvider' => $this->user->registeredInProject(true),
							'itemView' => 'partials/_project',
							'enableSorting' => true,
							'viewData' => array('model' => $model));
			
			unset($GLOBALS['project']);
			$this->beginWidget('zii.widgets.CPortlet', array(
				'title'=>'Project(s) where you are registered',
			));
			
			unset($GLOBALS['project']);
			$registeredInProject = array('id' => 'user-project-grid',
							'ajaxUpdate'=>true,
							'dataProvider' => $this->user->registeredInProject(),
							'itemView' => 'partials/_project',
							'enableSorting' => true,
							'viewData' => array('model' => $model));

			if(!empty($registeredInProject))
				$this->widget('zii.widgets.CListView', $registeredInProject);


		$this->endWidget();
	?>
	</div><!-- sidebar -->
	
	<?php if(!empty($ownerOfProject)): ?>	
		<div id="sidebar">
		<?php
	
				$this->beginWidget('zii.widgets.CPortlet', array(
					'title'=>'Project(s) where you are the owner',
				));
				
				unset($GLOBALS['project']);
	
				$this->widget('zii.widgets.CListView', $ownerOfProject);
	
	
			$this->endWidget();
		?>
		</div><!-- sidebar -->
	<?php endif;?>

</div>

<div class="span-19">
	<div id="content">
		<div id="statusMsg" class="flash-success" style="display:none;"></div>
		
		<?php echo $content; ?>
	</div><!-- content -->
</div>


<?php $this->endContent(); ?>
