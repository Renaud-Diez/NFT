<?php 
$this->breadcrumbs=array(
	'Issue Types',
);

$this->menu=array(
	array('label'=>'List Issue Type', 'url'=>array('index')),
	array('label'=>'Create a new Issue Type', 'url'=>array('create')),
);
?>

<h1><?php echo $this->title;?></h1>

<p>Associate the Issue Types with the corresponding Project Topics hereafter</p>

<div class="form">

<?php
	$form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
	'id'=>'matrix-form',
	'type'=>'vertical',
	));
?>

<?php 

$r = 0;

echo '<table class="items table table-striped table-bordered table-condensed">';

echo '<thead><tr>';
foreach($arrType as $type_id => $type)
{
	if($c==0)
		echo '<td></td>';

	echo '<td>'.$type.'</td>';
	$c++;
}
echo '</tr></thead><tbody>';

foreach($arrRelation as $relation_id => $relation)
{
	$c = 0;
	
	echo '<tr>';
	foreach($arrType as $type_id => $type)
	{
		$checked = '';
		$key = $type_id.':'.$relation_id;
		
		if($c==0){
			echo '<td>' . $relation . '</td>';
		}
		
		if(in_array($key, $arrTypeRelation))
			$checked = 'checked';
			
		echo '<td><input id="Form_checkbox"'.$j.' type="checkbox" value="'.$key.'" name="Matrix['.$j.']" '.$checked.'></input></td>';

		$c++;
	}
	echo '</tr>';

	$r++;
}

echo '</tbody></table>';
?>

<div class="row buttons">
		<?php //echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save');
				$this->widget('bootstrap.widgets.TbButton', array('buttonType'=>'submit', 'type'=>'primary', 'label'=>'Submit')); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->
