<?php
$arrVersions = array('id' => 'relatedVersions-grid',
		'ajaxUpdate'=>true,
		'dataProvider' => $dataProvider,
		'itemView' => 'partials/_relatedVersions',
		'enableSorting' => true,
		'viewData' => array('model' => $version));
	
$this->widget('zii.widgets.CListView', $arrVersions);