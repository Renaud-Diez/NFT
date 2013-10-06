<h1>View Project <?php echo $model->label; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		array('label'=>'Owner', 'value' => $model->owner->username),//owner.username',
		'code',
		'label',
		'description:html',
		array('label'=>'Topic', 'value' => $model->topic->label),//'topic.label',
	),
)); ?>

<br />
<div style="border-top: 1px solid lightgrey; padding-top: 5px;">
<b>Related Projects</b>
<div style="text-align: right;margin-top:-17px;";><a href="#" onclick=';relatedJS();$("#dialogRelated").dialog("open"); return false;'>Add</a></div>
<br />
<?php 
$arrRelated = array('id' => 'related-grid',
							'ajaxUpdate'=>true,
							'dataProvider' => $model->getRelatedProject(),
							'itemView' => '_relatedProject',
							'enableSorting' => true,
							'viewData' => array('model' => $model));
if(!empty($arrRelated))
{
	$this->widget('zii.widgets.CListView', $arrRelated);
}
?>
<br />
</div>
<?php ?>

<?php
if($model->checkAccess('Project.Setmembers'))
{
	$this->addMemberLink = CHtml::ajaxLink('Add Members',
	        						$this->createUrl('project/setmembers/id/'.$model->id),
	        						array('success'=>'function(r){$("#juiDialog").html(r).dialog("open"); return false;}')
								);
}
?>

<?php
$this->beginWidget('zii.widgets.jui.CJuiDialog', 
					array( // the dialog
			    			'id'=>'dialogRelated',
			    			'options'=>array(
			        		'title'=>'New Related Project',
			       			'autoOpen'=>false,
			        		'modal'=>true,
			        		'width'=>'420',
                    		'height'=>'auto',
							'minHeight'=>'300',
							'close' => 'js:function(){location.reload();}',
			    		),
			));
			
?>
<div class="relatedForm"></div>
<?php $this->endWidget();?>


<?php
$this->beginWidget('zii.widgets.jui.CJuiDialog', 
					array( // the dialog
			    			'id'=>'dialogVersion',
			    			'options'=>array(
			        		'title'=>'New Version',
			       			'autoOpen'=>false,
			        		'modal'=>true,
			        		'width'=>'420',
                    		'height'=>'auto',
							'minHeight'=>'300',
							'close' => 'js:function(){location.reload();}',
			    		),
			));
			
?>
<div class="versionForm"></div>		 
<?php $this->endWidget();?>

<?php
$this->beginWidget('zii.widgets.jui.CJuiDialog', 
					array( // the dialog
			    			'id'=>'dialogMilestone',
			    			'options'=>array(
			        		'title'=>'New Milestone',
			       			'autoOpen'=>false,
			        		'modal'=>true,
			        		'width'=>'420',
                    		'height'=>'auto',
							'minHeight'=>'400',
							'close' => 'js:function(){location.reload();}',
			    		),
			));
			
?>
<div class="milestoneForm"></div>		 
<?php $this->endWidget();?>


<script type="text/javascript">
function milestoneJS()
{
	<?php echo CHtml::ajax(array(
            'url'=>array('milestone/create/pid/'.$model->id),
            //'url'=>'js:dialogurl',
            'data'=> "js:$(this).serialize()",
            'type'=>'post',
            'dataType'=>'json',
            'success'=>"function(data)
            {
                if (data.status == 'failure')
                {
                    $('#dialogMilestone div.milestoneForm').html(data.div);
                    // Here is the trick: on submit-> once again this function!
                    $('#dialogMilestone div.milestoneForm form').submit(milestoneJS);
                }
                else
                {
                    $('#dialogMilestone div.milestoneForm').html(data.div);
                    setTimeout(\"$('#dialogMilestone').dialog('close') \",3000);
                }
 
            } ",
            ))
    ?>;
    return false; 
 
}

function versionJS()
{
	<?php echo CHtml::ajax(array(
            'url'=>array('version/create/pid/'.$model->id),
            'data'=> "js:$(this).serialize()",
            'type'=>'post',
            'dataType'=>'json',
            'success'=>"function(data)
            {
                if (data.status == 'failure')
                {
                    $('#dialogVersion div.versionForm').html(data.div);
                    $('#dialogVersion div.versionForm form').submit(versionJS);
                }
                else
                {
                    $('#dialogVersion div.versionForm').html(data.div);
                    setTimeout(\"$('#dialogVersion').dialog('close') \",3000);
                }
 
            } ",
            ))
    ?>;
    return false; 
 
}

function relatedJS()
{
	<?php echo CHtml::ajax(array(
            'url'=>array('project/related/'.$model->id),
            'data'=> "js:$(this).serialize()",
            'type'=>'post',
            'dataType'=>'json',
            'success'=>"function(data)
            {
                if (data.status == 'failure')
                {
                    $('#dialogRelated div.relatedForm').html(data.div);
                    $('#dialogRelated div.relatedForm form').submit(relatedJS);
                }
                else
                {
                    $('#dialogRelated div.relatedForm').html(data.div);
                    setTimeout(\"$('#dialogRelated').dialog('close') \",3000);
                }
 
            } ",
            ))
    ?>;
    return false; 
 
}
</script>