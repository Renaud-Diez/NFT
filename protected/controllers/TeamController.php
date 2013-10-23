<?php

class TeamController extends Controller
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout='//team/column2';
	public $search;

	/**
	 * Displays a particular model.
	 * @param integer $id the ID of the model to be displayed
	 */
	public function actionView($id)
	{
		$issue = new Issue();
		$issue->unsetAttributes();  // clear any default values
		if(isset($_GET['Issue']))
			$issue->attributes=$_GET['Issue'];
		
		$this->render('view',array(
			'model'=>$this->loadModel($id), 
			'issue' => $issue
		));
	}

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate()
	{
		$model=new Team;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Team']))
		{
			$model->attributes=$_POST['Team'];
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

		if(isset($_POST['Team']))
		{
			$model->attributes=$_POST['Team'];
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
		$dataProvider=new CActiveDataProvider('Team');
		$this->render('index',array(
			'dataProvider'=>$dataProvider,
		));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new Team('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Team']))
			$model->attributes=$_GET['Team'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}
	
	
	public function actionIssues($id)
	{
		$model = $this->loadModel($id);
		
		$issue = new Issue();
		$issue->unsetAttributes();  // clear any default values
		if(isset($_GET['Issue']))
			$issue->attributes=$_GET['Issue'];
		
		$this->render('issues',array(
				'model'=>$model, 'issue' => $issue
		));
	}
	
	public function actionGtd($id)
	{
		$model = $this->loadModel($id);

		$project = null;
		if($_POST['Project'] && !empty($_POST['Project']['label'])){
			$project = $_POST['Project']['label'];
		}
		
		$issue=new Issue('search');
		$issue->unsetAttributes();
		
		if(isset($_GET['Issue']))
			$issue->attributes=$_GET['Issue'];
		
		$openIssues = $model->getTeamIssues($issue, $filter = 'open', null, $project);
		$todoIssues = $model->getTeamIssues($issue, $filter = 'todo', null, $project);
		$doneIssues = $model->getTeamIssues($issue, $filter = 'done', null, $project);

		$this->render('gtd',array(
			'model'=>$model, 'openIssues'=>$openIssues,'todoIssues'=>$todoIssues,'doneIssues' => $doneIssues
		));
	}
	
	public function actionWorkload($id)
	{	
		$this->render('workload',array(
				'model'=>$this->loadModel($id),
		));
	}
	
	public function actionWeekly($id = null)
	{
		$model = $this->loadModel($id);
	
		$criteria = $this->setDateRangeSearch();
	
		$dataProvider = $model->getTeamWeekly($this->search);
	
		$this->render('weekly',array(
				'model' => $model,
				'dataProvider'=>$dataProvider,
		));
	}
	
	
	public function actionMembership($id)
	{
		$this->layout='//project/modal';
	
		$userModel = new User();
		$userModel->unsetAttributes();  // clear any default values
		if(isset($_GET['User']))
			$userModel->attributes=$_GET['User'];
	
		$this->render('membership',array(
				'model'=>$this->loadModel($id),'userModel' => $userModel
		));
	}
	
	public function actionSetMembership($id)
	{
		$model = $this->loadModel($id);
		
		if($_POST['act'] == 'unregister')
		{
			$model->unregisterMember($_POST['user_id']);
			echo 'Member successfully unregister from Team.';
		}
		else
		{
			$arr = $model->registerMember($_POST['autoId']);
				
			if($arr['error'] != 0)
				echo $arr['error'];
			if($arr['member'] != 0)
				echo $arr['member'];
		}
	}
	
	protected function setDateRangeSearch()
	{
		$criteria = null;
	
		$search = new DateRangeForm;
		$search->from = date('Y-m-d', strtotime('monday this week'));
	
	
		if($_POST['DateRangeForm']){
			if($_POST['DateRangeForm']['from']){
				$search->from = $_POST['DateRangeForm']['from'];
			}
	
			if($_POST['DateRangeForm']['to']){
				$search->to = $_POST['DateRangeForm']['to'];
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
		return $criteria;
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return Team the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=Team::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param Team $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='team-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
