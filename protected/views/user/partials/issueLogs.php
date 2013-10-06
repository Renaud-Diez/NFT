<div style="padding: 10px;">
<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$model->getUserWeeklyIssues(),
	'itemView'=>'_issueLogs',
)); ?>
</div>