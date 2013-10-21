<?php
/* @var $this ProjectRelationController */
?>

<div class="form">
<?php $form=$this->beginWidget('CActiveForm', array(
    'enableAjaxValidation'=>true,
)); ?>

<p>
	Are you sure you want to unlink this relationship?
	<?php echo $form->hiddenField($model,'id');?>
</p>

<?php 
$this->widget('bootstrap.widgets.TbButton',array(
        'buttonType' => 'ajaxButton',
        'id' => 'yes',
        'type' => 'primary',
		'block' => true,
        'label' => 'Yes!',
        //'size' => 'large',
        'url' => $this->createURL('/ProjectRelation/delete', array('id'=>$model->id)),
        'disabled' => false,
        'ajaxOptions' => array(
                'type' => 'Post',
                'url' => $this->createURL('/ProjectRelation/delete', array('id'=>$model->id)),
                //'data' => Yii::app()->request->csrfTokenName."=".Yii::app()->request->getCsrfToken()."&action=cancel&user=".$model->username,
                //'success'=>'function(){$("#deleteDialog").dialog("close");}',
                'success' => 'js:function(){location.reload();}',
                )
));
?>
<?php $this->endWidget(); ?>
</div>
