<?php

class MilestoneController extends Controller
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout='//layouts/column2';

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
		$model = new Milestone;
		$model->project_id = $_GET['pid'];
		$model->creation_date = date('Y-m-d');

		if(isset($_POST['Milestone']))
        {
			$model->version_id = $_POST['Milestone']['version_id'];
        	$model->attributes=$_POST['Milestone'];
            
            if($model->validate() && $model->save())
            {
            	if (Yii::app()->request->isAjaxRequest)
                {
                    echo CJSON::encode(array(
                        'status'=>'success', 
                        'div'=>$this->renderPartial('_success', array('model'=>$model, 'message' => 'Milestone successfully created'), true, true)
                        ));
                    exit;
                }
                else
                	$this->redirect(array('view','id'=>$model->id));
            } 
        }
        
 
        if (Yii::app()->request->isAjaxRequest)
        {
            echo CJSON::encode(array(
                'status'=>'failure', 
                'div'=>$this->renderPartial('_form', array('model'=>$model), true, true)));
            exit;               
        }
        else
            $this->render('create',array('model'=>$model,));
	}

	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id the ID of the model to be updated
	 */
	public function actionUpdate($id)
	{
		$model=$this->loadModel($id);

		if(isset($_POST['Milestone']))
        {
			$model->version_id = $_POST['Milestone']['version_id'];
        	$model->attributes=$_POST['Milestone'];
            
            if($model->validate() && $model->save())
            {
            	if (Yii::app()->request->isAjaxRequest)
                {
                    echo CJSON::encode(array(
                        'status'=>'success', 
                        'div'=>$this->renderPartial('_success', array('model'=>$model, 'message' => 'Milestone successfully updated'), true, true)
                        ));
                    exit;
                }
                else
                	$this->redirect(array('view','id'=>$model->id));
            } 
        }
        
 
        if (Yii::app()->request->isAjaxRequest)
        {
            Yii::app()->clientScript->scriptMap['jquery.js'] = false;
            Yii::app()->clientScript->scriptMap['jquery.min.js'] = false;
        	echo CJSON::encode(array(
                'status'=>'failure', 
                'div'=>$this->renderPartial('_form', array('model'=>$model), true, true)));
            exit;               
        }
        else
            $this->render('create',array('model'=>$model,));
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
		$dataProvider=new CActiveDataProvider('Milestone');
		$this->render('index',array(
			'dataProvider'=>$dataProvider,
		));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new Milestone('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Milestone']))
			$model->attributes=$_GET['Milestone'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return Milestone the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=Milestone::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param Milestone $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='milestone-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
