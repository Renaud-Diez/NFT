<div class="postit" style="float: left; width: 31%; padding: 4px; margin-right: 10px; margin-top: 10px; border-radius: 8px; border: 1px solid rgb(217, 217, 217);">
<h4><?php echo strtoupper($group);?></h4>
<hr style="border-color: rgb(217, 217, 217); border-width: 1px 0px 0px; margin-top: -5px; margin-bottom:-5px;">
<?php
$arrIssues = array('id' => $group.'-grid',
							'ajaxUpdate'=>true,
							'dataProvider' => $issues,
							'itemView' => 'issue/_postit',
							'enableSorting' => true,
							'viewData' => array('model' => $model, 'class' => $class));

$this->widget('zii.widgets.CListView', $arrIssues);
?>
</div>