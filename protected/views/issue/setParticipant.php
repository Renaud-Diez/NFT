<div class="form">
<?php $form=$this->beginWidget('CActiveForm', array(
    'enableAjaxValidation'=>true,
)); ?>

<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'non-user-grid',
	'dataProvider'=>$userModel->getUserNotInIssue($model->id),
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

<?php echo CHtml::ajaxSubmitButton('Filter',array('issue/ajaxSetParticipant'), array(),array("style"=>"display:none;")); ?>
<?php //echo CHtml::ajaxSubmitButton('Add to Project',array('project/ajaxupdate','act'=>'doActive', 'id' => $model->id), array('success'=>'reloadData')); ?>
<?php 
$this->widget('bootstrap.widgets.TbButton',array(
        'buttonType' => 'ajaxButton',
        'id' => 'addToIssue',
        'type' => 'primary',
		'block' => true,
        'label' => 'Add to Issue',
        'size' => 'large',
        'url' => $this->createURL('/issue/ajaxSetParticipant', array('id'=>$model->id)),
        'disabled' => false,
        'ajaxOptions' => array(
                'type' => 'Post',
                'url' => $this->createURL('/issue/ajaxSetParticipant', array('id'=>$model->id)),
                //'data' => Yii::app()->request->csrfTokenName."=".Yii::app()->request->getCsrfToken()."&action=cancel&user=".$model->username,
                'success'=>"reloadData",
                )
));
?>

<?php $this->endWidget(); ?>
</div>