<?php
/* @var $this SiteController */

$this->breadcrumbs=array(
	'Welcom Page',
);

$this->pageTitle=Yii::app()->name;
?>



<?php if(!Yii::app()->user->isGuest): ?>
<h1>Welcome to <i><?php echo CHtml::encode(Yii::app()->name); ?></i></h1>
<p>You last logged in on  <?php echo Yii::app()->user->lastLogin;?>.</p>
<?php endif;?>

<?php if(Yii::app()->user->isGuest): ?>
<h2 style="font-size: 60px;text-align:center">
Need for Team
<small>a R# Production</small>
</h2>
<?php $this->renderPartial('picture'); ?>
<?php endif;?>



