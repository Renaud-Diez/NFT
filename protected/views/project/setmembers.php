<div class="form">
<?php $form=$this->beginWidget('CActiveForm', array(
    'enableAjaxValidation'=>true,
)); ?>

<div class="row">
	Role: 
	<?php 	$criteria = new CDbCriteria();
			$criteria->compare('name','Project',true);
			$criteria->compare('type','2',true);
			$criteria->order = 'name ASC';
			$list = CHtml::listData(AuthItem::model()->findAll($criteria),'name', 'name');
			echo $form->dropDownList(ProjectUser::model(),'role',$list,array('options' => array('Project Reader' => array('selected'=>true))));
	?>
</div>

<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'non-user-grid',
	'dataProvider'=>$userModel->getUserNotInProject($model->id),
	'filter'=>$userModel,
	'columns'=>array(
		array(
            'id'=>'autoId',
            'class'=>'CCheckBoxColumn',
            'selectableRows' => '50',   
        ),
		'id',
		'username',
		'email',
	),
));?>

<script>
function reloadData(data) { 
	$.fn.yiiGridView.update('non-user-grid');
	$('#statusMsg').html(data).fadeIn().animate({opacity: 1.0}, 15000).fadeOut("slow");
}
</script>

<?php echo CHtml::ajaxSubmitButton('Filter',array('project/ajaxupdate'), array(),array("style"=>"display:none;")); ?>
<?php //echo CHtml::ajaxSubmitButton('Add to Project',array('project/ajaxupdate','act'=>'doActive', 'id' => $model->id), array('success'=>'reloadData')); ?>
<?php 
$this->widget('bootstrap.widgets.TbButton',array(
        'buttonType' => 'ajaxButton',
        'id' => 'addToProject',
        'type' => 'primary',
		'block' => true,
        'label' => 'Add to Project',
        'size' => 'large',
        'url' => $this->createURL('/project/ajaxupdate', array('id'=>$model->id)),
        'disabled' => false,
        'ajaxOptions' => array(
                'type' => 'Post',
                'url' => $this->createURL('/project/ajaxupdate', array('id'=>$model->id)),
                //'data' => Yii::app()->request->csrfTokenName."=".Yii::app()->request->getCsrfToken()."&action=cancel&user=".$model->username,
                'success'=>"reloadData",
                )
));
?>

<?php $this->endWidget(); ?>
</div>