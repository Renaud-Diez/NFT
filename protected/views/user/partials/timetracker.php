<div style="padding: 10px; margin-top:-30px;">
<?php 
	if($dataProvider)
	{
		$this->widget('zii.widgets.CListView', array(
		'dataProvider'=>$dataProvider,
		'itemView'=>'_weekly',));
	}
	
 ?>
</div>