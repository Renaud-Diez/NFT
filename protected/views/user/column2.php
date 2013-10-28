<?php /* @var $this Controller */ 
$projectModel = Project::model();
$project = false;
if($_POST['Project'] && !empty($_POST['Project']['label'])){
	$project = $_POST['Project']['label'];
	$projectModel->label = $project;
}


$uname = $this->user->uname . ' is';
if($this->user->id == Yii::app()->user->id)
	$uname = 'you are';
?>
<?php $this->beginContent('//layouts/main'); ?>

<script>
function reloadGrid(data) { 
	$.fn.yiiListView.update('user-grid');
	$('#statusMsg').html(data).fadeIn().animate({opacity: 1.0}, 15000).fadeOut("slow");
}
</script>
<?php if(in_array(strtolower($this->action->id), array('whosonwhat', 'weeklyreport'))):?>
<div class="span-5 last">
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
				
				if(strtolower($this->action->id) != 'weeklyreport'){
					echo 'User name:';
					echo '<div class="input-prepend"><span class="add-on"><i class="icon-search"></i></span>';
					echo $form->textField($this->search,'name',array('style'=>45,'maxlength'=>45));
					echo '</div>';
					
					//echo '<div class="input-prepend"><span class="add-on"><i class="icon-search"></i></span>';
					echo $form->dropDownListRow($this->search,'team', CHtml::listData(Team::model()->findAll(array('order' => 'label ASC')), 'id', 'label'), array('prompt'=>'- Team -', 'style' => 'width: 248px;'));
					//echo '</div>';
				}
				
				echo '<br /><br />';
				$this->widget(
				'bootstrap.widgets.TbButton',
				array('buttonType' => 'submit', 'label' => 'Filter', 'block' => true)
				);
				 
				$this->endWidget();
				unset($form);
	 		?>
	 </div>
</div>
<?php endif;?>

<?php if($this->user && !in_array(strtolower($this->action->id), array('whosonwhat', 'weeklyreport'))):?>
<div class="span-5 last">

	<?php if(in_array($this->action->id, array('view', 'gtd'))):?>
	<div id="sidebar">
	<?php 
			$this->beginWidget('zii.widgets.CPortlet', array(
				'title'=>'Search',
			));
			
			$this->renderPartial('_searchRight',array(
			'model'=>$this->issue,
			));

			$this->endWidget();
	?>
	</div>
	<?php endif;?>

	<?php //if(!isset($this->project)):?>
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
				$projectModel,
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
	<?php //endif;?>
	
	<?php 
	$highlightedProject = array('id' => 'owner-project-grid',
							'ajaxUpdate'=>true,
							'dataProvider' => $this->user->registeredInProject(false, true, $project),
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
							'dataProvider' => $this->user->registeredInProject(true, false, $project),
							'itemView' => 'partials/_project',
							'enableSorting' => true,
							'viewData' => array('model' => $model));
			
			unset($GLOBALS['project']);
			$this->beginWidget('zii.widgets.CPortlet', array(
				'title'=>'Project(s) where '.$uname.' registered',
			));
			
			unset($GLOBALS['project']);
			$registeredInProject = array('id' => 'user-project-grid',
							'ajaxUpdate'=>true,
							'dataProvider' => $this->user->registeredInProject(false, false, $project),
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
					'title'=>'Project(s) where '.$uname.' the owner',
				));
				
				unset($GLOBALS['project']);
	
				$this->widget('zii.widgets.CListView', $ownerOfProject);
	
	
			$this->endWidget();
		?>
		</div><!-- sidebar -->
	<?php endif;?>

</div>
<?php endif;?>

<div class="span-19">
	<div id="content">
		<div id="statusMsg" class="flash-success" style="display:none;"></div>
		
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
