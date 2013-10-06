<?php
/* @var $this SiteController */
/* @var $model LoginForm */
/* @var $form CActiveForm  */

$this->pageTitle=Yii::app()->name . ' - Login';
$this->breadcrumbs=array(
	'Login',
);
?>

<div style="margin-bottom:20px;">
<h2 style="font-size: 60px;text-align:center;">
Need for Team
<small>a R# Production</small>
</h2>
</div>

<div style=" border-top:1px dotted grey; height:20px;"></div>



<div class="form" style="text-align:center;">
<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'login-form',
	'enableClientValidation'=>true,
	'clientOptions'=>array(
		'validateOnSubmit'=>true,
	),
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<div class="row">
		<?php echo $form->labelEx($model,'username'); ?>
		<?php echo $form->textField($model,'username'); ?>
		<?php echo $form->error($model,'username'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'password'); ?>
		<?php echo $form->passwordField($model,'password'); ?>
		<?php echo $form->error($model,'password'); ?>
	</div>

	<div class="row rememberMe">
		<?php echo $form->checkBox($model,'rememberMe'); ?>
		<?php echo $form->label($model,'rememberMe'); ?>
		<?php echo $form->error($model,'rememberMe'); ?>
	</div>
	<br />
	<div class="row buttons">
		<?php $this->widget('bootstrap.widgets.TbButton', array(
						    'buttonType'=>'submit',
							'label'=>'Login',
						    'type'=>'primary', // null, 'primary', 'info', 'success', 'warning', 'danger' or 'inverse'
						    'size'=>'null', // null, 'large', 'small' or 'mini'
				)); 
		?>
	</div>

<?php $this->endWidget(); ?>
</div><!-- form -->

<div style="text-align:center">
<?php
echo CHtml::image(Yii::app()->baseUrl.'/assets/media/backgroundtablelogin.png', 'Need for Team');
?>
</div>
