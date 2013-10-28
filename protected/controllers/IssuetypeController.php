<?php

class IssuetypeController extends Controller
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout='//layouts/column2';
	public $title =  'Issue Type';


	/**
	 * Displays a particular model.
	 * @param integer $id the ID of the model to be displayed
	 */
	public function actionView($id)
	{
		$this->render('view',array(
			'model'=>$this->loadModel($id),
		));
	}

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate()
	{
		$model=new IssueType;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['IssueType']))
		{
			$model->attributes=$_POST['IssueType'];
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

		if(isset($_POST['IssueType']))
		{
			$model->attributes=$_POST['IssueType'];
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
		$dataProvider=new CActiveDataProvider('IssueType');
		$this->render('index',array(
			'dataProvider'=>$dataProvider,
		));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new IssueType('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['IssueType']))
			$model->attributes=$_GET['IssueType'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}
	
	public function actionTypetopic()
	{
		$this->title = 'Association Types & Topics';
		$arrType = CHtml::listData(IssueType::model()->findAll(), 'id', 'label');
		$arrTopic = CHtml::listData(Topic::model()->findAll(), 'id', 'label');
						
		if(isset($_POST['Matrix']))
		{
			IssueType::model()->saveTopicRelation($_POST['Matrix'], 'Topic');
		}
		
		$objTypeTopic = IssueTypeTopic::model()->findAll();
		foreach($objTypeTopic as $obj)
        	$arrTypeTopic[] = $obj->type_id.':'.$obj->topic_id;

		$this->render('typeMatrix',array(
			'arrType'=>$arrType, 'arrRelation'=>$arrTopic, 'arrTypeRelation' => $arrTypeTopic
		));
	}
	
	public function actionTyperole()
	{
		$criteria=new CDbCriteria;
		$criteria->compare('name', 'Project', true);
		$criteria->compare('type', 2);
		
		$this->title = 'Association Types & Roles';
		$arrType = CHtml::listData(IssueType::model()->findAll(), 'id', 'label');
		$arrRole = CHtml::listData(AuthItem::model()->findAll($criteria), 'name', 'name');
	
		if(isset($_POST['Matrix']))
		{
			IssueType::model()->saveRoleRelation($_POST['Matrix'], 'Role');
		}
	
		$objTypeRole = RoleIssueType::model()->findAll();
		foreach($objTypeRole as $obj)
			$objTypeRole[] = $obj->type_id.':'.$obj->role;
	
		$this->render('typeMatrix',array(
				'arrType'=>$arrType, 'arrRelation'=>$arrRole, 'arrTypeRelation' => $objTypeRole
		));
	}
	
	public function actionTypestatus()
	{
		$this->title = 'Association Types & Status';
		$arrType = CHtml::listData(IssueType::model()->findAll(), 'id', 'label');
		$arrTopic = CHtml::listData(IssueStatus::model()->findAll(), 'id', 'label');
						
		if(isset($_POST['Matrix']))
		{
			IssueType::model()->saveTopicRelation($_POST['Matrix'], 'Status');
		}
		
		$objTypeStatus = IssueTypeStatus::model()->findAll();
		foreach($objTypeStatus as $obj)
        	$arrTypeStatus[] = $obj->type_id.':'.$obj->status_id;

		$this->render('typeMatrix',array(
			'arrType'=>$arrType, 'arrRelation'=>$arrTopic, 'arrTypeRelation' => $arrTypeStatus
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return IssueType the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=IssueType::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param IssueType $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='issue-type-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
