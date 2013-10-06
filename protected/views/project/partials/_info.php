<div style="margin-top:-15px; margin-left:10px; margin-right:10px;">
<?php

	$arrEvent = array('id' => 'related-grid',
							'ajaxUpdate'=>true,
							'dataProvider' => $dataProvider,
							'itemView' => 'issue/_question',
							'enableSorting' => true,
							'viewData' => array('model' => $model));
	$this->widget('zii.widgets.CListView', $arrEvent);
?>
</div>