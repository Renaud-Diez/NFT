<?php

class IssueController extends Controller
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout='//issue/column2';
	public $project;
	public $issue;
	
	public $menuRelations = array();
	public $menuUsers = array();
	public $menuWorkload = array();
	public $menuDocuments = array();
	
	public $addUserLink = 'Add Collaborators';

	/**
	 * @return array action filters
	 */
	public function filters()
	{
		return array(
			'accessControl', // perform access control for CRUD operations
			'postOnly + delete', // we only allow deletion via POST request
		);
	}

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
				'actions'=>array('create','update','loadmilestones','loadstatus'),
				'users'=>array('@'),
			),
			array('allow', // allow admin user to perform 'admin' and 'delete' actions
				'actions'=>array('admin','delete'),
				'users'=>array('run'),
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
		$this->project = $this->loadProject($model->project_id);
		//$dpLogs = $model->getLogs();
		
		$this->addDocument($id);
		
		$this->render('view',array(
			'model'=>$model
		));
	}

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate()
	{
		$this->layout='column1';
		
		if($_GET['parent_id']){
			$parent = $model=Issue::model()->findByPk($_GET['parent_id']);
			$pid = $parent->project_id;
		}elseif($_GET['pid']){
			$pid = $_GET['pid'];
		}
		
		$this->project = $this->loadProject($pid);
		
		$model=new Issue;
		$model->project_id = $this->project->id;
		$model->user_id = Yii::app()->user->id;
		
		if($parent){
			$model->milestone_id = $parent->milestone_id;
			$model->version_id = $parent->version_id;
			$model->parent_id = $parent->id;
		}
		elseif($_GET['milestone'])
		{
			$milestone = Milestone::model()->find($_GET['milestone']);
			if($milestone->project_id == $pid){
				$model->milestone_id = $_GET['milestone'];
				$model->version_id = $_GET['version'];
			}
			
		}
		elseif($_GET['version']){
			$version = Version::model()->find('id=:id', array(':id'=>$_GET['version']));
			if($version->project_id == $pid)
				$model->version_id = $version->id;
				
			Yii::trace('Version ID: ' . $version->id,'models.project');
		}

		
		if(isset($_POST['Issue']))
		{
			//$model->persist();
			$model->attributes=$_POST['Issue'];

			if($model->save())
			{
				
				if(!empty($model->parent_id))
				{
					$relation = new IssueRelation;
					$relation->related_id = $model->parent_id;
					$relation->issue_id = $model->id;
					$relation->relation = 0;
					$relation->save();
					
					/*$relation = new IssueRelation;
					$relation->related_id = $model->id;
					$relation->issue_id = $model->parent_id;
					$relation->relation = 0;
					$relation->save();*/
				}
				
				$this->redirect(array('view','id'=>$model->id));
			}
		}
		
		$this->render('create',array(
			'model'=>$model, 'relation'=>$relation
		));
	}
	
	
	public function actionTimeLog($id)
	{
		$model = $this->loadModel($id);
		$this->project = $this->loadProject($model->project_id);
		$timeLog = $model->getTimeLog();
		
		$this->render('timelog',array(
			'model'=>$model, 'timeLog' => $timeLog,
		));
	}

	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id the ID of the model to be updated
	 */
	public function actionUpdate($id)
	{
		$this->layout='column1';
		
		$model=$this->loadModel($id);
		$this->project = $this->loadProject($model->project_id);

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Issue']))
		{
			$model->attributes=$_POST['Issue'];
			$model->comment = $_POST['Issue']['comment'];
			
			if($model->save())
				$this->redirect(array('view','id'=>$model->id));
		}

		$this->render('update',array(
			'model'=>$model,
		));
	}
	
	
	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id the ID of the model to be updated
	 */
	public function actionComment($id)
	{
		$this->layout='column1';
	
		$model=$this->loadModel($id);
		$this->project = $this->loadProject($model->project_id);
	
		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);
	
		if(isset($_POST['Issue']))
		{
			$model->attributes=$_POST['Issue'];
			$model->comment = $_POST['Issue']['comment'];
				
			if($model->save())
				$this->redirect(array('view','id'=>$model->id));
		}
	
		$this->render('comment',array(
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
		$model = $this->loadModel($id);
		$project_id = $model->project_id;
		
		$model->delete();

		// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
		if(!isset($_GET['ajax']))
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('/project/issues', 'id'=>$project_id));
	}
	
	

	/**
	 * Lists all models.
	 */
	public function actionIndex()
	{
		$dataProvider=new CActiveDataProvider('Issue');
		$this->render('index',array(
			'dataProvider'=>$dataProvider,
		));
	}
	
	
	/**
	 * Lists all models.
	 */
	public function actionCritical()
	{
		$model=new Issue('search');
		$model->unsetAttributes();
		
		if(isset($_GET['Issue']))
			$model->attributes=$_GET['Issue'];
		
		$dataProvider= $model->criticalIssues();
		
		$this->render('critical',array(
				'model' => $model,
				//'dataProvider'=>$dataProvider,
		));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new Issue('search');
		$model->unsetAttributes();  // clear any default values
		
		if($_GET['pid'])
		{
			$pid = $_GET['pid'];
			$this->project = $this->loadProject($pid);
			$model->project_id = $pid;
		}
		
		
		if(isset($_GET['Issue']))
			$model->attributes=$_GET['Issue'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}
	
	
	public function actionSetParticipant($id)
	{
		$this->layout='//layout/modal';
		
		$userModel = new User();
		$userModel->unsetAttributes();  // clear any default values
		if(isset($_GET['User']))
			$userModel->attributes=$_GET['User'];
		
		
		$this->render('setParticipant',array(
			'model'=>$this->loadModel($id),'userModel' => $userModel
		));
	}
	
	public function actionAjaxSetParticipant($id)
	{
		$model = $this->loadModel($id);
		$project_id = $model->project_id;
		
		if($_POST['act'] == 'unregister')
		{
			$model->unregisterParticipant($_POST['user_id']);
			echo 'Participant successfully unregister from issue.';
		}
		else
		{
			$arr = $model->registerParticipant($_POST['autoId']);
			
			if($arr['error'] != 0)
				echo $arr['error'];
			if($arr['member'] != 0)
				echo $arr['member'];
		}
	}
	
	public function actionImOnIt($id)
	{
		$model = $this->loadModel($id);
		$message = $model->imOnIt();
		
		echo $message;
	}
	
	public function actionRelated($id)
	{
		$issue = $this->loadModel($id);
		$this->project = $this->loadProject($issue->project_id);
		
		
		//$issueList = CHtml::listData(Issue::model()->findAll());
		
		$model = new IssueRelation;
		$model->issue_id = $id;

		if(isset($_POST['IssueRelation']))
        {
			$model->attributes=$_POST['IssueRelation'];
            
            if($model->validate() && $model->save())
            {
            	$related = new IssueRelation;
				$related->issue_id = $model->related_id;
				$related->related_id = $model->issue_id;
				
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
            } 
        }
        
 
        if (Yii::app()->request->isAjaxRequest)
        {
            echo CJSON::encode(array(
                'status'=>'failure', 
                'div'=>$this->renderPartial('relatedForm', array('model'=>$model, 'issue' => $issue), true, true)));
            exit;               
        }
        else
            $this->render('relatedForm',array('model'=>$model, 'issue' => $issue));
	}
	
	public function actionKanban()
	{
		
		
		$this->render('kanban',array(
			'model'=>$model,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return Issue the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=Issue::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
			
		$this->issue = $model;
		return $model;
	}
	
	public function loadProject($id)
	{
		$model=Project::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}
	
	public function actionLoadmilestones()
	{
		$data = Milestone::model()->findAll('version_id=:version_id', array(':version_id'=>(int) $_POST['version_id']));
	 
	   	$data = CHtml::listData($data,'id','label');
	 
		echo "<option value=''>Select Milestone</option>";
		foreach($data as $value=>$milestone){
			if(!empty($_POST['milestone_id']) && $_POST['milestone_id'] == $value)
				echo CHtml::tag('option', array('value'=>$value, 'selected' => 'selected'),CHtml::encode($milestone),true);
			else
				echo CHtml::tag('option', array('value'=>$value),CHtml::encode($milestone),true);
		}
			
	}
	
	public function actionLoadstatus()
	{
		$data = IssueStatus::model()->getAvailableStatus($_POST['type_id']);
	 
		if(empty($data))
			echo "<option value=''>Select a type first ...</option>";
		foreach($data as $value=>$status){
			if(!empty($_POST['status_id']) && $_POST['status_id'] == $value)
				echo CHtml::tag('option', array('value'=>$value, 'selected' => 'selected'),CHtml::encode($status),true);
			else
				echo CHtml::tag('option', array('value'=>$value),CHtml::encode($status),true);
		}
			
	}

	/**
	 * Performs the AJAX validation.
	 * @param Issue $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='issue-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
	
	protected function addDocument($id)
	{
		$model = new Document;

		if(isset($_POST['Document']))
        {
			$model->attributes=$_POST['Document'];
            $model->file = CUploadedFile::getInstance($model,'file');

            if($model->validate() && $model->save())
            {
            	$binding = new IssueDocument;
            	$binding->issue_id = $id;
            	$binding->document_id = $model->id;
            	$binding->save();
            } 
        }
	}
}
