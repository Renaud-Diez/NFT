<div style="padding: 10px;">
<?php 
	if($dataProvider)
	{
		$this->widget('zii.widgets.CListView', array(
		'dataProvider'=>$dataProvider,
		'itemView'=>'_weekly',));
	}
	
 ?>
</div>