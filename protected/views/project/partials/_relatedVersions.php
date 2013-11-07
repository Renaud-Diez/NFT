<div style="margin-bottom: 25px;"><h3><?php echo $data->target->project->label . ': ' . substr($data->target->project->topic->steps, 0, -1) . ' ' .$data->target->label;?></h3></div>
<?php
$issues = $data->target->getIssues();
echo $this->renderPartial('_issues',array('dataProvider' => $issues, 'gridId' => $data->target->id),true);
?>