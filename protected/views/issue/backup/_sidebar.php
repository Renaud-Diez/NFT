<?php
$this->beginWidget('zii.widgets.jui.CJuiDialog', 
					array( // the dialog
			    			'id'=>'dialogTimetracker',
			    			'options'=>array(
			        		'title'=>'Log Time',
			       			'autoOpen'=>false,
			        		'modal'=>true,
			        		'width'=>'420',
                    		'height'=>'auto',
							'minHeight'=>'350',
							//'close' => 'js:function(){location.reload();}',
			    		),
			));
			
?>
<div class="timetrackerForm"></div>
<?php $this->endWidget();?>


<?php
$this->beginWidget('zii.widgets.jui.CJuiDialog', 
					array( // the dialog
			    			'id'=>'dialogModal',
			    			'options'=>array(
			        		'title'=>'Issue Transition',
			       			'autoOpen'=>false,
			        		'modal'=>true,
			        		'width'=>'390',
                    		'height'=>'auto',
							'minHeight'=>'600',
							//'close' => 'js:function(){location.reload();}',
			    		),
			));
			
?>
<div class="modalForm"></div>
<?php $this->endWidget();?>


<?php
$this->beginWidget('zii.widgets.jui.CJuiDialog', 
					array( // the dialog
			    			'id'=>'dialogRelated',
			    			'options'=>array(
			        		'title'=>'New Related Issue',
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


<script type="text/javascript">

function relatedJS()
{
	<?php echo CHtml::ajax(array(
            'url'=>array('issue/related/'.$model->id),
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

function logTime()
{
	<?php echo CHtml::ajax(array(
            'url'=>array('/timetracker/create/issue/'.$model->id),
            'data'=> "js:$(this).serialize()",
            'type'=>'post',
            'dataType'=>'json',
            'success'=>"function(data)
            {
                if (data.status == 'failure')
                {
                    $('#dialogTimetracker div.timetrackerForm').html(data.div);
                    $('#dialogTimetracker div.timetrackerForm form').submit(logTime);
                }
                else
                {
                    $('#dialogTimetracker div.timetrackerForm').html(data.div);
                    setTimeout(\"$('#dialogTimetracker').dialog('close') \",3000);
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

function reloadInfo(data)
{
	//$('#button-Imonit').css("display", "none");
	var labelOn = 'I\'m on It!';
	var labelOff = 'I\'m not on it anymore!';
	var label = labelOn;
	var addClass = 'btn-success';
	var removeClass = 'btn-danger';
	alert($('#button-Imonit').text());
	if($('#button-Imonit').text() == labelOn){
		label = labelOff;
		addClass = 'btn-danger';
		removeClass = 'btn-success';
	}
	$('#button-Imonit').html(label);
	$('#button-Imonit').switchClass(removeClass, addClass);
	$('#statusMsg').html(data).fadeIn().animate({opacity: 1.0}, 15000).fadeOut("slow");
}

function reloadList(data, grid)
{
	$.fn.yiiListView.update(grid);
	$('#statusMsg').html(data).fadeIn().animate({opacity: 1.0}, 15000).fadeOut("slow");
}
</script>
