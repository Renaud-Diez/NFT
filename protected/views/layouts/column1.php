<?php /* @var $this Controller */ ?>
<?php $this->beginContent('//layouts/main'); ?>

<div class="span-18">
<div id="content">
	<div id="statusMsg" class="flash-success" style="display:none;"></div>
	<?php echo $content; ?>
</div><!-- content -->
</div>
<?php $this->endContent(); ?>