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

<?php
$this->beginWidget('zii.widgets.jui.CJuiDialog', 
					array( // the dialog
			    			'id'=>'dialogModal',
			    			'options'=>array(
			        		'title'=>'',
			       			'autoOpen'=>false,
			        		'modal'=>true,
			        		'width'=>'420',
                    		'height'=>'auto',
							'minHeight'=>'400',
							'close' => 'js:function(){location.reload();}',
			    		),
			));
			
?>
<div class="modalForm"></div>		 
<?php $this->endWidget();?>

<?php
$this->beginWidget('zii.widgets.jui.CJuiDialog', 
					array( // the dialog
			    			'id'=>'deleteModal',
			    			'options'=>array(
			        		'title'=>'Delete',
			       			'autoOpen'=>false,
			        		'modal'=>true,
			        		'width'=>'320',
                    		'height'=>'auto',
							'minHeight'=>'160',
							'close' => 'js:function(){location.reload();}',
			    		),
			));
			
?>
<div class="deleteForm"></div>		 
<?php $this->endWidget();?>

<script type="text/javascript">
var _dialogurl;
var _dialogtitle;
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
            'url'=>array('/version/create/pid/'.$model->id),
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

function updateJS(url,title)
{
	if (typeof(url)=='string'){
		_dialogurl = url;
		_dialogtitle = title;
	}

	<?php echo CHtml::ajax(array(
            'url' => 'js:_dialogurl',
            'data'=> "js:$(this).serialize()",
            'type'=>'post',
            'dataType'=>'json',
            'success'=>"function(data)
            {
            	$('.ui-dialog-title').html(_dialogtitle);
            	if (data.status == 'failure')
                {
                	$('#dialogModal div.modalForm').html(data.div);
                    $('#dialogModal div.modalForm form').submit(updateJS);
                }
                else
                {
                    $('#dialogModal div.modalForm').html(data.div);
                    setTimeout(\"$('#dialogModal').dialog('close') \",3000);
                }
 
            } ",
            ))
    ?>;
    return false; 
 
}

function checkDate()
{
	$from = $('#DateRangeForm_from').val();
	$to = $('#DateRangeForm_to').val();
	if($from != '' && $to != ''){
		if($from > $to){
			$('#DateRangeForm_to').val($from);
		}
	}
    return false; 
}
</script>