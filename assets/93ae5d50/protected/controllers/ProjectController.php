<?php

class ProjectController extends Controller
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout='//project/column2';
	public $hiddenMenu=array();
	public $issueMenu=array();
	public $versionMenu=array();
	public $memberMenu=array();
	public $arrMembers=array();
	public $arrVersions=array();
	public $arrProjects=array();
	public $addMemberLink;
	//public $arrMilestones=array();
	//public $arrVersions=array();

	/**
	 * @return array action filters
	 */
	/*public function filters()
	{
		return array(
			'accessControl', // perform access control for CRUD operations
			'topicContext + create, update', // check to ensure valid topic context
			'postOnly + delete', // we only allow deletion via POST request
		);
	}*/

	/**
	 * Specifies the access control rules.
	 * This method is used by the 'accessControl' filter.
	 * @return array access control rules
	 */
	/*public function accessRules()
	{
		return array(
			array('allow',  // allow all users to perform 'index' and 'view' actions
				'actions'=>array('index','view'),
				'users'=>array('*'),
			),
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'=>array('create','update'),
				'users'=>array('@'),
			),
			array('allow', // allow admin user to perform 'admin' and 'delete' actions
				'actions'=>array('admin','delete','ajaxupdate','members','versions','setmembers'),
				'users'=>array('admin'),
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}*/

	/**
	 * Displays a particular model.
	 * @param integer $id the ID of the model to be displayed
	 */
	public function actionView($id)
	{
		$model = $this->loadModel($id);
		
		$this->arrMembers = array('id' => 'user-grid',
							'ajaxUpdate'=>true,
							'dataProvider' => $model->getMembers(),
							'itemView' => '/admin/user/_members',
							'enableSorting' => true,
							'viewData' => array('model' => $model));
		
		
   		$this->arrVersions = $model->getVersions();
   		
   		//$this->arrProjects = $model->getRelatedProject();
		
		
		$this->render('view',array(
			'model'=>$model,
		));
	}

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate()
	{
		$model=new Project;
		$model->user_id = Yii::app()->user->id;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Project']))
		{
			$model->attributes=$_POST['Project'];
			if($model->save())
				$this->redirect(array('view','id'=>$model->id));
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
		$dataProvider=new CActiveDataProvider('Project');
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
		//print_r($_POST);
		if($_GET['act'] == 'doDelete')
		{
			ProjectUser::model()->deleteAll('project_id = :projectId AND user_id = :userId', array('projectId' => $_GET['id'], 'userId' => $_GET['user_id']));
			echo 'Member successfully unregister from project.';
		}
		else
		{
			//print_r($_POST['ProjectUser']['role']);
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
	
}
