<?php
/* @var $this SiteController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Welcom',
);

$project = false;
if($_POST['Project'] && !empty($_POST['Project']['label'])){
	$project = $_POST['Project']['label'];
}
?>

<h1>Welcom <?php if($model->firstname)echo $model->firstname;else echo $model->username;?></h1>
<p><i>You last visit was on  <?php echo Yii::app()->user->lastLogin;?>.</i></p>

<?php 
$dpQuestion = $model->openedQuestion($project);
if($dpQuestion->getItemCount() > 0):?>
<h3>Opened questions waiting for you</h3>
<div style="margin-top: -20px; margin-bottom: 30px;">
<?php 
$openedQuestions = array('id' => 'user-question-grid',
							'ajaxUpdate'=>true,
							'dataProvider' => $dpQuestion,
							'itemView' => 'partials/_question',
							'enableSorting' => true,
							'viewData' => array('model' => $model));

$this->widget('zii.widgets.CListView', $openedQuestions);
?>
</div>
<?php endif;?>

<?php 
unset($dpQuestion);
$dpQuestion = $model->openedQuestion($project, 'posted');
if($dpQuestion->getItemCount() > 0):?>
<h3>Opened questions posted by you</h3>
<div style="margin-top: -20px; margin-bottom: 30px;">
<?php 
$postedQuestions = array('id' => 'user-question-grid',
							'ajaxUpdate'=>true,
							'dataProvider' => $dpQuestion,
							'itemView' => 'partials/_question',
							'enableSorting' => true,
							'viewData' => array('model' => $model));

$this->widget('zii.widgets.CListView', $postedQuestions);
?>
</div>
<?php endif;?>

<?php 
$GLOBALS['project'] = 0;
$dpIssues = $model->assignedIssues(false, $project);
if($dpIssues->getItemCount() > 0):?>
<div style="border-top: 1px dotted grey;">
<h3>Opened issues assigned to you</h3>
<div style="margin-top: -20px; margin-bottom: 30px;">
<?php 
	$assignedIssues = array('id' => 'user-issues-grid',
							'ajaxUpdate'=>true,
							'dataProvider' => $dpIssues,
							'itemView' => 'partials/_issue',
							'enableSorting' => true,
							'viewData' => array('model' => $model));

	$GLOBALS['project'] = 0;
	$this->widget('zii.widgets.CListView', $assignedIssues);
?>
</div></div>
<?php endif;?>



<?php 
	$date = new DateTime;
	$to = $date->format('Y-m-d');
	$date->sub(new DateInterval('P10D'));
	$dataProvider = $model->getActivityDetail($from, $to);
	
if($dataProvider):?>
<div style="border-top: 1px dotted grey;">
<h3>Your last 10 days activities</h3>
<div style="margin-top: -20px;">
<?php 
	$activityDetail = array('id' => 'user-activity-grid',
							'ajaxUpdate'=>true,
							'dataProvider' => $dataProvider,
							'itemView' => '_weekly',
							'enableSorting' => true,
							'viewData' => array('model' => $model));

	$this->widget('zii.widgets.CListView', $activityDetail);
?>
</div></div>
<?php endif;?>