<div class="form">
<?php $form=$this->beginWidget('CActiveForm', array(
    'enableAjaxValidation'=>true,
)); 

?>


<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'non-member-grid',
	'dataProvider'=>$userModel->getUserNotInTeam($model->id),
	'filter'=>$userModel,
	'columns'=>array(
		array(
            'id'=>'autoId',
            'class'=>'CCheckBoxColumn',
            'selectableRows' => '50',   
        ),
		//'id',
		'username',
		'email',
	),
));?>

<script>
function reloadData(data) { 
	$.fn.yiiGridView.update('non-member-grid');
	$('#statusMsg').html(data).fadeIn().animate({opacity: 1.0}, 15000).fadeOut("slow");
}
</script>

<?php echo CHtml::ajaxSubmitButton('Filter',array('team/setMembership'), array(),array("style"=>"display:none;")); ?>

<?php 
$this->widget('bootstrap.widgets.TbButton',array(
        'buttonType' => 'ajaxButton',
        'id' => 'addToTeam',
        'type' => 'primary',
		'block' => true,
        'label' => 'Add to Team',
        'size' => 'large',
        'url' => $this->createURL('/team/setmembership', array('id'=>$model->id)),
        'disabled' => false,
        'ajaxOptions' => array(
                'type' => 'Post',
                'url' => $this->createURL('/team/setmembership', array('id'=>$model->id)),
                'success'=>"reloadData",
                )
));
?>

<?php $this->endWidget(); ?>
</div>