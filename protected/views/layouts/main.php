<?php /* @var $this Controller */ ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="language" content="en" />

	<!-- blueprint CSS framework -->
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/screen.css" media="screen, projection" />
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/print.css" media="print" />
	<!--[if lt IE 8]>
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/ie.css" media="screen, projection" />
	<![endif]-->

	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/main.css" />
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/form.css" />

	<title><?php echo CHtml::encode($this->pageTitle); ?></title>
</head>

<body>

<div class="container" id="page">

	<div id="header">
		<div>
			<?php Yii::app()->bootstrap->register();
				$activeUser = false;
				$activeProject = false;
				$bodyMargin = 40;
				if(isset($this->project) && !is_null($this->project))
					$activeProject = true;
					
				$this->widget('bootstrap.widgets.TbNavbar', array(
			    'type'=>'inverse', // null or 'inverse'
				'htmlOptions'=>array('style'=>'z-index:100000'),
			    'brand'=>'Need For Team',
			    'brandUrl'=>'#',
			    'collapse'=>true, // requires bootstrap-responsive.css
			    'items'=>array(
			        array(
			            'class'=>'bootstrap.widgets.TbMenu',
			            'items'=>array(
			                array('label'=>'Home', 'url'=>'/user/welcom'),
			                array('label'=>'Issues', 'url'=> array('/issue'), 'visible' => Yii::app()->user->checkAccess('Issue.*')? true: false, 'items'=>array(
			                	array('label'=>'DEFINITION'),
			                	array('label'=>'Issue Type', 'url'=> array('/IssueType')),
			                	array('label'=>'Issue Status', 'url'=> array('/IssueStatus')),
			                	'---',
			                	array('label' => 'VIEW'),
			                	array('label'=>'All Issues', 'url'=> array('/Issue/admin')),
			                	'---',
			                	array('label'=>'CONFIGURATION'),
			                	array('label'=>'Issue Type by Topic', 'url'=> array('/IssueType/typetopic')),
			                	array('label'=>'Issue Type by Role', 'url'=> array('/IssueType/typerole')),
			                	array('label'=>'Issue Status by Type', 'url'=> array('/IssueType/typestatus')),
			                )),
			                array('label'=>'Projects', 'url'=>'#', 'authItemName' => 'Project.View', 'visible' => Yii::app()->user->checkAccess('Project.*')? true: false, 'active'=>$activeProject,  'items'=>array(
			                    array('label'=>'Create a new Project', 'url'=> array('/project/create')),
			                    '---',
			                    array('label'=>'VIEWS'),
			                    array('label'=>'Overview', 'url'=> array('/project/index?v=highlighted')),
			                    array('label'=>'GTD', 'url'=>array('/project/gtd')),
			                    array('label'=>'Treeview', 'url'=>array('/project/treeview')),
			                    '---',
			                    array('label'=>'CONFIGURATION'),
			                    array('label'=>'Project Topics', 'url'=>array('/topic')),
			                    array('label'=>'Project Status', 'url'=>array('/ProjectStatus')),
			                    array('label'=>'Project Status by Topic', 'url'=>array('/ProjectStatus/statustopic')),
			                )),
			                array('label'=>'Users', 'url'=> array('/admin/user'), 'authItemName' => 'User.View', 'visible' => Yii::app()->user->checkAccess('User.view')? true: false, 'active'=>$activeUser,  'items'=>array(
			                	array('label'=>'New User', 'url'=>array('/admin/user/create')),
			                	'---',
			                	array('label'=>'VIEWS'),
			                	array('label'=>'User List', 'url'=>array('/admin/user')),
			                	array('label'=>'User Activities', 'url'=>array('/user/whosOnWhat')),
			                	'---',
			                	array('label'=>'TEAM'),
			                	array('label'=>'Team List', 'url'=>array('/team')),
			                	'---',
			                    array('label'=>'TIME TRACKING'),
			                    array('label' => 'Create a new Activity', 'url' => array('/TimeActivity/create')),
			                    array('label' => 'Time Tracking Activities', 'url' => array('/TimeActivity')),
			                )),
			                array('label'=>'Rights & Permissions', 'url'=>'#', 'authItemName' => 'Project.View', 'visible' => Yii::app()->user->checkAccess('Rights.*')? true: false, 'active'=>$activeRights,  'items'=>array(
			                    array('label'=>'Assignements', 'url'=>array('/rights')),
			                    array('label'=>'Permissions', 'url'=>array('/rights/authItem/permissions')),
			                    array('label'=>'Roles', 'url'=>array('/rights/authItem/roles')),
			                    array('label'=>'Tasks', 'url'=> array('/rights/authItem/tasks')),
			                    array('label'=>'Operations', 'url'=> array('/rights/authItem/operations')),
			                )),
			            ),
			        ),
			        '<form class="navbar-search pull-right" action="" id="IssueForm" name="IssueForm" method="post"><input id="issue" name="issue" type="text" class="search-query span2" placeholder="Search"></form>',
			        array(
			            'class'=>'bootstrap.widgets.TbMenu',
			            'htmlOptions'=>array('class'=>'pull-right'),
			            'items'=>array(
			                array('label'=>'Login', 'url'=>array('/site/login'), 'visible'=> Yii::app()->user->isGuest? true: false),
			                array('label'=>'Logout ('.Yii::app()->user->name.')', 'url'=>array('/site/logout'), 'visible'=>!Yii::app()->user->isGuest),
			                array('label'=>'My Account', 'url'=>'#', 'visible'=> !Yii::app()->user->isGuest? true: false, 'items'=>array(
			                    array('label'=>'Weekly Report', 'url'=>array('/user/weeklyReport')),
			                    array('label'=>'Personal Information', 'url'=>'#', 'linkOptions'=>array('onclick'=>';personalDataJS();$("#dialogPersonalData").dialog("open"); return false;')),
			                    array('label'=>'Change Password', 'url'=>'#', 'linkOptions'=>array('onclick'=>';passwordJS();$("#dialogPassword").dialog("open"); return false;')),
			                )),
			            ),
			        ),
			    ),
			)); ?>
		</div>

		<!-- <div class="subnav navbar-fixed-top"><div id="logo"><?php //echo CHtml::encode(Yii::app()->name); ?></div></div> -->
	</div><!-- header -->

	<div>
	
		<?php 
		if($activeProject === true)
		{
			$steps = 'Phases';
			if(!empty($this->project->topic->steps))
				$steps = ucfirst($this->project->topic->steps);
			
			$bodyMargin = 80;
			//Define Issue menu array
			if($this->uniqueid == 'project' && $this->action->id == 'roadmap' && $_GET['version'])
				$arrMenuIssue[] = array('label'=>'New Issue', 'url'=>array('issue/create', 'pid'=>$this->project->id, 'version' => $_GET['version']));
			elseif($this->uniqueid == 'project' && $this->action->id == 'roadmap' && $_GET['milestone'])
				$arrMenuIssue[] = array('label'=>'New Issue', 'url'=>array('issue/create', 'pid'=>$this->project->id, 'milestone' => $_GET['milestone']));
			else
				$arrMenuIssue[] = array('label'=>'New Issue', 'url'=>array('issue/create', 'pid'=>$this->project->id));
				
			if($this->uniqueid == 'issue' && $this->action->id == 'view')
				$arrMenuIssue[] = array('label'=>'New Sub-issue', 'url'=> array('issue/create/', 'pid'=>$this->project->id, 'parent_id' =>$this->issue->id));
				
			$arrMenuIssue[] = '---';
			$arrMenuIssue[] = array('label'=>'VIEWS');
			$arrMenuIssue[] = array('label'=>'Kanban', 'url'=> array('/project/kanban', 'id' => $this->project->id));
			$arrMenuIssue[] = array('label'=>'GTD', 'url'=> array('/project/gtd', 'id' => $this->project->id));
			$arrMenuIssue[] = '---';
			$arrMenuIssue[] = array('label'=>'LISTS');
			$arrMenuIssue[] = array('label'=>'All Issues', 'url'=> array('/project/issues', 'id' => $this->project->id));
			$arrMenuIssue[] = array('label'=>'My Issues', 'url'=> array('/project/myIssues', 'id' => $this->project->id));
			$arrMenuIssue[] = array('label'=>'Issues by Roles', 'url'=>'/project/issuesByRoles');
			$arrMenuIssue[] = array('label'=>'Issues by Members', 'url'=>'/project/issuesByMembers');
			//end Issue menu array
			
			$this->widget('bootstrap.widgets.TbNavbar', array(
			    'type'=>'null', // null or 'inverse'
				'htmlOptions'=>array('class'=>'subnav navbar-fixed-top'),
			    'brand'=>$this->project->label,
			    'brandUrl'=>'/project/'.$this->project->id,
			    'collapse'=>true, // requires bootstrap-responsive.css
			    'items'=>array(
			        array(
			            'class'=>'bootstrap.widgets.TbMenu',
			            'items'=>array(
			        		array('label'=>'Roadmap', 'url'=>'/project/roadmap/'.$this->project->id),
			        		array('label'=>'Statistics', 'url'=>'/project/statistic/'.$this->project->id),
			                array('label'=>'Project Issues', 'url'=>'/project/issue/'.$this->project->id, 'items'=>$arrMenuIssue),
			                array('label'=> $steps . ' & Milestones', 'url'=>'#', 'items'=>array(
			                    
			                    array('label'=>'Create ' . $steps, 'url'=>'#', 'linkOptions'=>array('onclick'=>';versionJS();$("#dialogVersion").dialog("open"); return false;')),
								array('label'=>'Manage ' . $steps, 'url'=>array('project/versions', 'pid'=>$this->project->id)),
			                    '---',
			                    array('label'=>'Milestones'),
			                    array('label'=>'Create Milestone', 'url'=>'#', 'linkOptions'=>array('onclick'=>';milestoneJS();$("#dialogMilestone").dialog("open"); return false;')),
			                    array('label'=>'Manage Milestones', 'url'=>array('milestone/create', 'pid'=>$this->project->id)),
			                )),
			            ),
			        ),
			        array(
			            'class'=>'bootstrap.widgets.TbMenu',
			            'htmlOptions'=>array('class'=>'pull-right'),
			            'items'=>array(
			                array('label'=>'Operations', 'url'=>'#', 'items'=>array(
			                    array('label'=>'View', 'url'=>'/project/'.$this->project->id),
			                	array('label'=>'Update', 'url'=>'/project/update/'.$this->project->id),
			                    array('label'=>'History', 'url'=>'/project/history/'.$this->project->id),
			                    '---',
			                    array('label'=>'New Subproject', 'url'=>array('/project/create', 'parent_id' => $this->project->id)),
			                    '---',
			                    array('label'=>'Delete', 'url'=>'#'),
			                )),
			            ),
			        ),
			    ),
			));
		}
		
		?>
	</div><!-- mainmenu -->
	
	<div id="maincontent" style="margin-top: <?php echo $bodyMargin;?>px;">
	<?php if(isset($this->breadcrumbs)):?>
		<?php $this->widget('zii.widgets.CBreadcrumbs', array(
			'links'=>$this->breadcrumbs,
		)); ?><!-- breadcrumbs -->
	<?php endif?>
	
	<?php echo $content; ?>
	</div>
	
	<div class="clear"></div>

	<div id="footer">
		Copyright &copy; <?php echo date('Y'); ?> by R#.<br/>
		All Rights Reserved.<br/>
	</div><!-- footer -->

</div><!-- page -->

<?php
$this->beginWidget('zii.widgets.jui.CJuiDialog', 
					array( // the dialog
			    			'id'=>'dialogPassword',
			    			'options'=>array(
			        		'title'=>'Change Password',
			       			'autoOpen'=>false,
			        		'modal'=>true,
			        		'width'=>'320',
                    		'height'=>'auto',
							'minHeight'=>'160',
							'close' => 'js:function(){location.reload();}',
			    		),
			));
			
?>
<div class="passwordForm"></div>		 
<?php $this->endWidget();?>

<?php
$this->beginWidget('zii.widgets.jui.CJuiDialog', 
					array( // the dialog
			    			'id'=>'dialogPersonalData',
			    			'options'=>array(
			        		'title'=>'Personal Information',
			       			'autoOpen'=>false,
			        		'modal'=>true,
			        		'width'=>'440',
                    		'height'=>'auto',
							'minHeight'=>'180',
							'close' => 'js:function(){location.reload();}',
			    		),
			));
			
?>
<div class="personalDataForm"></div>		 
<?php $this->endWidget();?>

<script type="text/javascript">
function passwordJS()
{
	<?php echo CHtml::ajax(array(
            'url'=>array('user/password/id/'.Yii::app()->user->id),
            'data'=> "js:$(this).serialize()",
            'type'=>'post',
            'dataType'=>'json',
            'success'=>"function(data)
            {
                if (data.status == 'failure')
                {
                    $('#dialogPassword div.passwordForm').html(data.div);
                    // Here is the trick: on submit-> once again this function!
                    $('#dialogPassword div.passwordForm form').submit(passwordJS);
                }
                else
                {
                    $('#dialogPassword div.passwordForm').html(data.div);
                    setTimeout(\"$('#dialogPassword').dialog('close') \",3000);
                }
 
            } ",
            ))
    ?>;
    return false; 
 
}

function personalDataJS()
{
	<?php echo CHtml::ajax(array(
            'url'=>array('user/update/id/'.Yii::app()->user->id),
            'data'=> "js:$(this).serialize()",
            'type'=>'post',
            'dataType'=>'json',
            'success'=>"function(data)
            {
                if (data.status == 'failure')
                {
                    $('#dialogPersonalData div.personalDataForm').html(data.div);
                    // Here is the trick: on submit-> once again this function!
                    $('#dialogPersonalData div.personalDataForm form').submit(personalDataJS);
                }
                else
                {
                    $('#dialogPersonalData div.personalDataForm').html(data.div);
                    setTimeout(\"$('#dialogPersonalData').dialog('close') \",3000);
                }
 
            } ",
            ))
    ?>;
    return false; 
 
}
</script>

</body>
</html>
