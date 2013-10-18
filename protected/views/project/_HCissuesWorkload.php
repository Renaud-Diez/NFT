<div class="row-fluid">
<div class="span6">
<?php
	$data = $model->getWorkload('activity', $this->search);
	$this->renderPartial('_HCactivities', array('data'=>$data));
?>
</div>

<div class="span6">
<?php 
	$data = $model->getWorkload('users', $this->search);
	$this->renderPartial('_HCactivities', array('data'=>$data));
?>
</div>
</div>