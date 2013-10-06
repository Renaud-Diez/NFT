<div style="margin-left: 8px;">
<?php
//$treedata = $data->getSubprojects($data->id);
											
$this->widget('CTreeView',array(
        'data'=>$treedata,
        'animated'=>'fast', //quick animation
        'collapsed'=>false,//remember must giving quote for boolean value in here
        'htmlOptions'=>array(
                //'class'=>'treeview-famfamfam',//there are some classes that ready to use
                'class' => 'filetree',
        ),
));
?>
</div>