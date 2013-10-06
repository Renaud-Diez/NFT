<?php /* @var $this Controller */ ?>
<?php $this->beginContent('//layouts/modal'); ?>
<?php echo CHtml::ajaxSubmitButton('Filter',array('project/ajaxupdate'), array(),array("style"=>"display:none;")); ?>

	<div>
		<?php echo $content; ?>
	</div><!-- content -->

<?php $this->endContent(); ?>