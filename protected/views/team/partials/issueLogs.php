<div style="padding: 10px; margin-top:-30px;">
<?php 
	$this->widget('zii.widgets.CListView', array(
	'dataProvider' => $issueLogs,
	'itemView'=>'_issueLogs',
)); ?>
</div>