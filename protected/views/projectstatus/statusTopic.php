<?php 
$this->breadcrumbs=array(
	'Project Status',
);

$this->menu=array(
	array('label'=>'List Project Status', 'url'=>array('index')),
	array('label'=>'Create a new Project Status', 'url'=>array('create')),
);
?>

<h1>Project Status by Topic</h1>

<p>Associate the Project Status with the corresponding Topics hereafter</p>

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
foreach($arrStatus as $status_id => $status)
{
	if($c==0)
		echo '<td></td>';

	echo '<td>'.$status.'</td>';
	$c++;
}
echo '</tr></thead><tbody>';

foreach($arrRelation as $relation_id => $relation)
{
	$c = 0;
	
	echo '<tr>';
	foreach($arrStatus as $status_id => $status)
	{
		$checked = '';
		$key = $status_id.':'.$relation_id;
		
		if($c==0){
			echo '<td>' . $relation . '</td>';
		}
		
		if(in_array($key, $arrTopicRelation))
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
