<div class="row-fluid">
<div class="span6">
<?php 
	$data = $model->getWorkload('issue-type');
	$this->renderPartial('_HCactivities', array('data'=>$data, 'type' => 'issue-type'));
?>
</div>

<div class="span6">
<?php 
	$data = $model->getWorkload('issue-workload');
	$this->renderPartial('_HCactivities', array('data'=>$data));
?>
</div>
</div>