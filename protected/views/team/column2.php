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
	
	<?php if(in_array(strtolower($this->action->id), array('weekly', 'workload'))):?>
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
						//'htmlOptions'=>array('prepend' => '<i class="icon-calendar"></i>') // HTML options
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
				
				/*if(strtolower($this->action->id) != 'weeklyreport'){
					echo 'User name:';
					echo '<div class="input-prepend"><span class="add-on"><i class="icon-search"></i></span>';
					echo $form->textField($this->search,'name',array('style'=>45,'maxlength'=>45));
					echo '</div>';
				}*/
				
				echo '<br /><br />';
				$this->widget(
				'bootstrap.widgets.TbButton',
				array('buttonType' => 'submit', 'label' => 'Filter', 'block' => true)
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

<script type="text/javascript">
function checkDate()
{
	$from = $('#User_from').val();
	$to = $('#User_to').val();
	if($from != '' && $to != ''){
		if($from > $to){
			$('#User_to').val($from);
		}
	}
    return false; 
}
</script>