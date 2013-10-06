<div class="view">
<?php echo '<i class="'.$data->treeIcon($data->topic->label).'" style="margin-right:5px;"></i>' . CHtml::link(CHtml::encode($data->label), array('/project/view', 'id'=>$data->id));?>
</div>