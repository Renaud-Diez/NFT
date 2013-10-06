<?php

class ProjectRelationController extends Controller
{
	public function actionDelete($id)
	{
		$this->layout='modal';
		$model = $this->loadModel($id);
		
		if(isset($_POST['ProjectRelation'])){
			$model->delete();
		}
		else
			$this->render('delete', array('model' => $model));
	}

	public function actionIndex()
	{
		$this->render('index');
	}

	public function actionView()
	{
		$this->render('view');
	}
	
	public function loadModel($id)
	{
		$model=ProjectRelation::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	// Uncomment the following methods and override them if needed
	/*
	public function filters()
	{
		// return the filter configuration for this controller, e.g.:
		return array(
			'inlineFilterName',
			array(
				'class'=>'path.to.FilterClass',
				'propertyName'=>'propertyValue',
			),
		);
	}

	public function actions()
	{
		// return external action classes, e.g.:
		return array(
			'action1'=>'path.to.ActionClass',
			'action2'=>array(
				'class'=>'path.to.AnotherActionClass',
				'propertyName'=>'propertyValue',
			),
		);
	}
	*/
}