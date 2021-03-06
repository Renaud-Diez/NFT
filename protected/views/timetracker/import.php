<?php
/* @var $this TimetrackerController */
/* @var $model TimeTracker */

$this->breadcrumbs=array(
	'Timetracker'=>array('index'),
	'Import',
);


?>

<?php
    foreach(Yii::app()->user->getFlashes() as $key => $message) {
        echo '<div class="flash-' . $key . '">' . $message . "</div>\n";
    }
?>

<div id="statusMsg" class="flash-success" style="display:none;">

</div>

<h1>TimeLog Import</h1>

<?php if(is_null($sheet))$this->renderPartial('partials/importForm', array('model'=>$file));?>

<?php if(!is_null($sheet)):?>
<p>Content of your uploaded file:</p>
<div style="border-top: 1px solid lightgrey; padding-top: 5px;">
<?php 
	$i = 0;
	foreach($sheet as $row){
		foreach($row as $record => $value){
			if($i == 0)
				$columns[] = $value;
			$rec[$sheet[1][$record]] = $value;
		}
		if($i > 0)
			$arr[] = $rec;
		$i++;
	}
	
	$dataProvider=new CArrayDataProvider($arr, array(
			'keyField'=>false,
			'pagination'=>array(
					'pageSize'=>$i,
			),
	));
	
	$this->widget('bootstrap.widgets.TbExtendedGridView', array(
			'id'=>'cart-grid',
			'dataProvider'=>$dataProvider,
			'type' => 'striped bordered condensed',
			'columns' => $columns,
	));

	$form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
			'id'=>'import-issues-form',
			'type'=>'vertical',
			'htmlOptions' => array('enctype' => 'multipart/form-data'),
			'enableAjaxValidation'=>true,
			'clientOptions'=>array(
					'validateOnSubmit'=>false,
					'validateOnChange'=>false,
			),
	));
	
	echo $form->hiddenField($file,'path');
	
	$this->widget('bootstrap.widgets.TbButton', array(
			'buttonType'=>'submit',
			'label'=>'Import Data',
			'type'=>'primary',
	));
	echo ' ';
	$this->widget('bootstrap.widgets.TbButton', array(
		'buttonType'=>'submit',
		'label'=>'Cancel',
		'id' => 'cancel',
		'htmlOptions' => array('name' => 'cancel')
		//'type'=>'primary',
));
	
	$this->endWidget();
	
endif;?>
</div>