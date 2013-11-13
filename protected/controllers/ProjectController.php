<?php

class ProjectController extends Controller
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout='//project/column2';
	public $project;
	public $search = null;
	public $hiddenMenu=array();
	public $viewMenu=array();
	public $issueMenu=array();
	public $versionMenu=array();
	public $memberMenu=array();
	public $arrMembers=array();
	public $arrVersions=array();
	public $arrProjects=array();
	public $addMemberLink;

	
	public function loadSidebar($model)
	{
		$this->arrMembers = array('id' => 'user-grid',
							'ajaxUpdate'=>true,
							'dataProvider' => $model->getMembers(),
							'itemView' => '/admin/user/_members',
							'enableSorting' => true,
							'viewData' => array('model' => $model));
		
		
   		$this->arrVersions = $model->getVersions();
	}

	/**
	 * Displays a particular model.
	 * @param integer $id the ID of the model to be displayed
	 */
	public function actionView($id)
	{
		$model = $this->loadModel($id);
		$model->attachBehavior('ProjectStatsBehavior', new ProjectStatsBehavior);
		
		$this->loadSidebar($model);
		
		//$diff = TextDiff::compare('sdqsdqsdqsd ddf', 'sdqsdqsdqsd azaz');
		
		$this->render('view',array(
			'model'=>$model,
		));
	}
	
	
	public function actionImport($id)
	{
		$model = $this->loadModel($id);
		$model->attachBehavior('ProjectImportBehavior', new ProjectImportBehavior);
		
		$file = new FileImportForm;
		
		if(!isset($_POST['cancel']) && $_POST['FileImportForm']){
			if($_POST['FileImportForm']['path']){
				$records = $model->importIssues($_POST['FileImportForm']['path'], $_GET['version'], $_GET['milestone']);
				Yii::app()->user->setFlash('success', $records);
			}else{
				$file->attributes=$_POST['FileImportForm'];
				$file->file = CUploadedFile::getInstance($file,'file');
				
				if(is_object($file->file)){
					$file->path = "assets/media/".$file->file;
					$path = Yii::app()->getBasePath() . "/../" . $file->path;

					$file->file->saveAs($path);
					
					$arrSheet = Yii::app()->yexcel->readActiveSheet($path);
				}
			}
			
		}
		
		$this->loadSidebar($model);
		
		$this->render('import',array(
				'filePath' => $path,
				'sheet'=>$arrSheet,
				'model'=>$model,
				'file'=>$file,
				'message' => $records
		));
	}
	
	/**
	 * Displays a particular model.
	 * @param integer $id the ID of the model to be displayed
	 */
	public function actionStatistic($id)
	{
		$model = $this->loadModel($id);
		$this->loadSidebar($model);
		
		$this->setDateRangeSearch($model->getStartDate());
		
		$this->render('statistic',array(
			'model'=>$model,
		));
	}

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate()
	{
		$this->layout='//layouts/column1';
		
		$model=new Project;
		$model->user_id = Yii::app()->user->id;
		
		if($_GET['parent_id'])
			$model->parent_id = $_GET['parent_id'];

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Project']))
		{
			$model->attributes=$_POST['Project'];
			if($model->save()){
				if(!empty($model->parent_id))
				{
					$relation = new ProjectRelation;
					$relation->related_id = $model->parent_id;
					$relation->project_id = $model->id;
					$relation->relation = 0;
					$relation->save();
				}
				$this->redirect(array('view','id'=>$model->id));
			}
				
		}

		$this->render('create',array(
			'model'=>$model,
		));
	}

	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id the ID of the model to be updated
	 */
	public function actionUpdate($id)
	{
		$this->layout='//layouts/column1';
		
		$model=$this->loadModel($id);

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Project']))
		{
			$model->attributes=$_POST['Project'];
			if($model->save())
				$this->redirect(array('view','id'=>$model->id));
		}

		$this->render('update',array(
			'model'=>$model,
		));
	}

	/**
	 * Deletes a particular model.
	 * If deletion is successful, the browser will be redirected to the 'admin' page.
	 * @param integer $id the ID of the model to be deleted
	 */
	public function actionDelete($id)
	{
		$this->loadModel($id)->delete();

		// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
		if(!isset($_GET['ajax']))
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
	}

	/**
	 * Lists all models.
	 */
	public function actionIndex()
	{
		if($_GET['highlight']){
			ProjectUser::model()->setVisibility($_GET['highlight'], $_GET['visibility']);
		}
		
		$criteria=new CDbCriteria;
		
		
		if($_POST['Project']){
			$criteria->compare('label', $_POST['Project']['label'], true);
			$criteria->compare('code', $_POST['Project']['label'], true, 'OR');
		}
		elseif($_GET['v'] == 'highlighted'){
			$criteria->with = array('projectUsers');
			$criteria->together = true;
			$criteria->addcondition('projectUsers.user_id=:user_id');
			$criteria->params[':user_id'] = Yii::app()->user->id;
			$criteria->addCondition('projectUsers.visibility=:visibility');
			$criteria->params[':visibility'] = 1;
		}
		elseif($_GET['v'] == 'root'){
			$criteria->addCondition('parent_id IS NULL');
		}
		
		if($_GET['topic']){
			$criteria->addNotInCondition('topic_id', split(',', $_GET['topic']));
		}


		$dataProvider=new CActiveDataProvider('Project', array(
			'criteria'=>$criteria,
			'pagination' => array('pageSize' => 100,),
		));
		$dataProvider->sort->defaultOrder='label ASC';
		
		//Yii::trace('PLABEL: ' . $dataProvider->data[0]->label,'models.project');
		if($_POST['Project'] && count($dataProvider->getData()) == 1){
			$this->redirect('/project/view/'.$dataProvider->data[0]->id);
		}
		
		$this->render('index',array(
			'dataProvider'=>$dataProvider,
		));
	}
	
	public function getTopic()
	{
		$dataProvider=new CActiveDataProvider('Topic', array(
			'criteria'=>$criteria,
		));
		$dataProvider->sort->defaultOrder='label ASC';
		
		$arrTopic = array('id' => 'versions-grid',
							'ajaxUpdate'=>true,
							'dataProvider' => $dataProvider,
							'itemView' => '/topic/_projectFilter',
							'enableSorting' => true,
							'viewData' => array('model' => Topic::model()));
		
		return $arrTopic;
	}
	
	/**
	 * Configurable by user
	 * Due dates changes
	 * 'Blocked by' + 'is Blocking' Projects
	 * Issue type Incidents + identified types by user
	 * Milestones due date warning: eg: 5 days before the end of the milestone / requested effort greater than allocated HR & available time => CRON + manual force update ...
	 * Owner changes
	 * Special trigger set by the user
	 */
	public function getAlerts()
	{
		
	}
	
	/**
	 * Lists my models.
	 */
	public function actionHighlight()
	{
		$criteria=new CDbCriteria;
		
		$criteria->with = array('projectUser');
		$criteria->together = true;
		$criteria->condition = 'projectUser.project_id=:project_id';
		$criteria->addCondition = 'projectUser.user_id=:user_id';
		$criteria->addCondition = 'projectUser.visibility=:visibility';
		$criteria->params = array('project_id' => $this->id, 'user_id' => Yii::app()->user->id, 'visibility' => 1);
		
		//$criteria->addCondition('parent_id IS NULL');

		$dataProvider=new CActiveDataProvider('Project', array(
			'criteria'=>$criteria,
		));
		$dataProvider->sort->defaultOrder='label ASC';
		
		$this->render('index',array(
			'dataProvider'=>$dataProvider,
		));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new Project('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Project']))
			$model->attributes=$_GET['Project'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}
	
	public function actionHistory($id)
	{
		$model = $this->loadModel($id);
		
		$dataProvider = new CActiveDataProvider('ProjectLogs', array(
            						'data'=>$model->projectLogs,
									'pagination' => array('pageSize' => 20)
   							 ));
   		$dataProvider->sort->defaultOrder='creation_date ASC';
   		
   		$arrLogs = array('id' => 'versions-grid',
							'ajaxUpdate'=>true,
							'dataProvider' => $dataProvider,
							'itemView' => '/project/_history',
							'enableSorting' => true,
							'viewData' => array('model' => $model));
		
		$this->render('history',array(
			'model'=>$model,
			'arrLogs'=>$arrLogs,
		));
	}
	
	
	public function actionKanban($id = null)
	{
		if(!is_null($id)){
			$model = $this->loadModel($id);
			$this->loadSidebar($model);
			$types = $model->kanbanIsues();
		}else{
			$model = Project::model();
			$order = 'project_id ASC';
		}
		
		
		
		$issue=new Issue('search');
		$issue->unsetAttributes();  // clear any default values
		
		if(Yii::app()->session['myIssues'] == 'true')
			$issue->assignee_id = Yii::App()->user->id;
		
		if(isset($_GET['Issue']))
			$issue->attributes=$_GET['Issue'];
		
		//$dataProvider = $model->getDataProviderIssues($issue);

		$this->render('kanban',array(
			'model'=>$model, 'issue'=>$issue,'dataProvider'=>$dataProvider, 'types' => $types
		));
	}
	
	
	public function actionGtd($id = null)
	{
		if(!is_null($id)){
			$model = $this->loadModel($id);
			$this->loadSidebar($model);
		}else{
			$model = Project::model();
			$order = 'project_id ASC';
		}
		
		$project = null;
		if($_POST['Project'] && !empty($_POST['Project']['label'])){
			$project = $_POST['Project']['label'];
		}
		
		$issue=new Issue('search');
		$issue->unsetAttributes();
		
		if(isset($_GET['Issue']))
			$issue->attributes=$_GET['Issue'];
			
		if(Yii::app()->session['myIssues'] == 'true')
			$issue->assignee_id = Yii::App()->user->id;
		
		//$dataProvider = $model->getDataProviderIssues($issue, 'ist.alias ASC, ist.rank ASC, type_id ASC');
		$openIssues = $model->getIssues($issue, $filter = 'open', null, $project);
		$todoIssues = $model->getIssues($issue, $filter = 'todo', null, $project);
		$doneIssues = $model->getIssues($issue, $filter = 'done', null, $project);

		$this->render('gtd',array(
			'model'=>$model, 'openIssues'=>$openIssues,'todoIssues'=>$todoIssues,'doneIssues' => $doneIssues
		));
	}
	
	public function actionTreeview()
	{
		$label = null;
		if($_POST['Project']['label']){
			$label = $_POST['Project']['label'];
		}

		$model = Project::model();
		$treedata = $model->getSubprojects(null, $label);
		
		if(count($treedata) == 1 && !is_null($treedata[0]['id'])){
			$this->redirect('/project/view/'.$treedata[0]['id']);
		}
			
		
		$this->render('treeview',array(
			'model'=>$model, 'treedata'=>$treedata,
		));
	}
	
	
	public function actionIssues($id)
	{
		$model = $this->loadModel($id);
		$this->loadSidebar($model);
		
		$issue=new Issue('search');
		$issue->unsetAttributes();  // clear any default values
		//$issue->project_id = $id;
		
		if(isset($_GET['Issue']))
			$issue->attributes=$_GET['Issue'];
			
		if(Yii::app()->session['myIssues'] == 'true')
			$issue->assignee_id = Yii::App()->user->id;
		
		$dataProvider = $model->getDataProviderIssues($issue);

		$this->render('issues',array(
			'model'=>$model, 'issue'=>$issue,'dataProvider'=>$dataProvider,
		));
	}
	
	
	public function actionVersions($id)
	{
		$this->layout='//project/column2';
		
		if(is_null($id))
			$this->redirect('project/index');
			
		$versions = new CActiveDataProvider('Version', array(
																'criteria' => array(
																		'condition' => 'project_id=:projectId', 'params' => array(':projectId' => $this->loadModel($id)->id)),
																'pagination' => array('pageSize' => 10,)
															)
											);
			
		$this->render('versions',array(
			'model'=>$this->loadModel($id), 'versions' => $versions
		));
	}
	
	
	public function actionRoadmap($id)
	{
		if(is_null($id))
			$this->redirect('project/index');
			
		$model = $this->loadModel($id);
		
		$this->loadSidebar($model);
		
		
		if(isset($_GET['milestone'])){
			$milestoneId = $_GET['milestone'];
			$milestone = Milestone::model()->findByPk($milestoneId);
		}
		elseif(isset($_GET['version']))
		{
			$versionId = $_GET['version'];
			$version = Version::model()->findByPk($versionId);
		}
			
			
		$dataCompletion = $model->computeCompletion();
		
		$versions = new CActiveDataProvider('Version', array(
																'criteria' => array(
																		'condition' => 'project_id=:projectId', 'params' => array(':projectId' => $this->loadModel($id)->id)),
																'pagination' => array('pageSize' => 10,)
															)
											);
		$arrV = $versions->getData();
					
		if(empty($arrV)){
			$this->render('roadmap',array(
				'model'=>$model, 'dataCompletion' => $dataCompletion
			));
		}	
		elseif(isset($_GET['milestone'])){
			$this->render('milestone',array(
				'model'=>$model, 'version' => $version, 'milestone' => $milestone
			));
		}
		elseif(isset($_GET['version'])){
			$this->render('version',array(
				'model'=>$model, 'version' => $version
			));
		}
		else{
			$this->render('roadmapVersions',array(
				'model'=>$model, 'dataCompletion' => $dataCompletion, 'versions' => $versions
			));
		}
	}
	
	
	public function actionSetmembers($id)
	{
		$this->layout='//project/modal';
		
		$userModel = new User();
		$userModel->unsetAttributes();  // clear any default values
		if(isset($_GET['User']))
			$userModel->attributes=$_GET['User'];
		
		
		$this->render('setmembers',array(
			'model'=>$this->loadModel($id),'userModel' => $userModel
		));
	}
	
	
	/**
	 * 
	 * Enter description here ...
	 */
	public function actionAjaxupdate()
	{
		if($_GET['act'] == 'doDelete')
		{
			ProjectUser::model()->deleteAll('project_id = :projectId AND user_id = :userId', array('projectId' => $_GET['id'], 'userId' => $_GET['user_id']));
			echo 'Member successfully unregister from project.';
		}
		else
		{
			
			$projectId = $_GET['id'];
			$role = $_POST['ProjectUser']['role'];
			$autoIdAll = $_POST['autoId'];
	        if(count($autoIdAll)>0)
	        {
	            $newMembers = 0;
	            $error = 0;
	        	foreach($autoIdAll as $autoId)
	            {
	               $model = ProjectUser::model()->findByAttributes(array('project_id' => $projectId, 'user_id' => $autoId, 'role' => $role));
	               if(is_null($model))
	               {
	               		$model = new ProjectUser;
	               		$model->project_id = $projectId;
	               		$model->user_id = $autoId;
	               		$model->role = $role;
	               		
	               		if($model->save())
	               		{
		                    //Yii::app()->user->setFlash('success', "Data saved!");
		                    //$bizRule = 'return isset($params["project"]) && $params["project"]->checkAccess();';
		                    Rights::assign($role, $autoId);
		                    $newMembers++;
	               		}
		                else
		                {
		                	//Yii::app()->user->setFlash('error', "Data failed!");
		                	$error++;
		                }
		                    
	               }
	            }
	            if($error > 0)
	            	echo $error . ' user(s) unsuccessfully added!';
	            if($newMembers > 0)
	            	echo $newMembers . ' user(s) successfully added as '.$role.'!';
	        }
		}
		
	}
	
	public function actionRelated($id)
	{
		$project = $this->loadModel($id);
		
		$projectList = CHtml::listData(Project::model()->findAll());
		
		$model = new ProjectRelation;
		$model->project_id = $id;

		if(isset($_POST['ProjectRelation']))
        {
			$model->attributes=$_POST['ProjectRelation'];
            
            if($model->validate() && $model->save())
            {
            	$related = new ProjectRelation;
				$related->project_id = $model->related_id;
				$related->related_id = $model->project_id;
				
				$related->relation = $model->getOppositeRelation();
				Yii::trace('RELATION ID: ' . $related->relation,'models.project');
				if($related->relation !== false)
					$related->save();
            	
            	if (Yii::app()->request->isAjaxRequest)
                {
                    echo CJSON::encode(array(
                        'status'=>'success', 
                        'div'=>'Relation set!'//$this->renderPartial('_success', array('model'=>$model), true, true)
                        ));
                    exit;
                }
                /*else
                	$this->redirect(array('view','id'=>$model->id));*/
            } 
        }
        
 
        if (Yii::app()->request->isAjaxRequest)
        {
            echo CJSON::encode(array(
                'status'=>'failure', 
                'div'=>$this->renderPartial('relatedForm', array('model'=>$model,'projectList'=>$projectList, 'project' => $project), true, true)));
            exit;               
        }
        else
            $this->render('relatedForm',array('model'=>$model,'projectList'=>$projectList, 'project' => $project));
	}


	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return Project the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=Project::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		$this->project = $model;
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param Project $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='project-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
	
	public function filterTopicContext($filterChain)
	{
		$filterChain->run();
	}
	
	protected function setDateRangeSearch($from = null)
	{
		$dateRange = array('from' => null, 'to' => null);
		
		$search = new DateRangeForm;
		if(is_null($from))
			$search->from = $dateRange['from'] = date('Y-m-d', strtotime('monday this week'));
		else
			$search->from = $dateRange['from'] = $from;
		
		
		if($_POST['DateRangeForm']){
			if($_POST['DateRangeForm']['from']){
				$search->from = $dateRange['from'] = $_POST['DateRangeForm']['from'];
			}
		
			if($_POST['DateRangeForm']['to']){
				$search->to = $dateRange['to'] = $_POST['DateRangeForm']['to'];
			}
		
			if($_POST['DateRangeForm']['name']){
				$search->name = $_POST['DateRangeForm']['name'];
		
				$criteria = new CDbCriteria;
				$criteria->compare('username', $search->name, true, 'OR');
				$criteria->compare('firstname', $search->name, true, 'OR');
				$criteria->compare('lastname', $search->name, true, 'OR');
			}
		}
		
		$this->search = $search;
	}
	/* (non-PHPdoc)
	 * @see RController::accessDenied()
	 */
	public function accessDenied($message = null) {
		// TODO: Auto-generated method stub

	}

	
}
