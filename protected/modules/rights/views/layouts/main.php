<?php 
$this->menu=array(
	array('label'=>Rights::t('core', 'Assignments'),
		'url'=>array('assignment/view')),
	array('label'=>Rights::t('core', 'Permissions'),
			'url'=>array('authItem/permissions')),
	array('label'=>Rights::t('core', 'Roles'),
			'url'=>array('authItem/roles')),
	array('label'=>Rights::t('core', 'Tasks'),
			'url'=>array('authItem/tasks')),
	array('label'=>Rights::t('core', 'Operations'),
			'url'=>array('authItem/operations')),
);
?>


<?php $this->beginContent(Rights::module()->appLayout); ?>

<div class="span-5">
	<div id="sidebar">
	<?php if( $this->id!=='install' ): ?>
	
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

		<?php //$this->renderPartial('/_menu'); ?>

	<?php endif; ?>
	</div>
</div>

<div class="span-19">
<div id="rights" class="container">

	<div id="content">

		<?php $this->renderPartial('/_flash'); ?>

		<?php echo $content; ?>

	</div><!-- content -->

</div>
</div>
<?php $this->endContent(); ?>