<div style="margin-top:-15px; margin-left:10px; margin-right:10px;">
<?php
/**
 * Configurable by user
 * Due dates changes
 * Issue type Incidents + identified types by user
 * Milestones due date warning: eg: 5 days before the end of the milestone / requested effort greater than allocated HR & available time => CRON + manual force update ...
 * Owner changes
 * Special trigger set by the user
 */

	$arrEvent = array('id' => 'related-grid',
							'ajaxUpdate'=>true,
							'dataProvider' => $dataProvider,
							'itemView' => '/event/_view',
							'enableSorting' => true,
							'viewData' => array('model' => $model));
	$this->widget('zii.widgets.CListView', $arrEvent);
?>
</div>