<?php /* @var $this Controller */ ?>
<?php $this->beginContent('//layouts/main');?>

<div class="span-5">
	
	<?php if(strtolower($this->action->id) == 'gtd'):?>
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
</div>
<div class="span-19">
	<div id="content">
		<?php echo $content; ?>
	</div><!-- content -->
</div>

<?php $this->endContent(); ?>