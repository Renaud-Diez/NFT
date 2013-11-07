<?php

class VersionController extends Controller
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
		$model = new Version;
		$model->project_id = $_GET['pid'];
		$model->creation_date = date('Y-m-d');

		if(isset($_POST['Version']))
        {
			$model->attributes=$_POST['Version'];
            
            if($model->validate() && $model->save())
            {
            	if (Yii::app()->request->isAjaxRequest)
                {
                    echo CJSON::encode(array(
                        'status'=>'success', 
                        'div'=>'houra!'//$this->renderPartial('_success', array('model'=>$model), true, true)
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
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id the ID of the model to be updated
	 */
	public function actionUpdate($id)
	{
		$model=$this->loadModel($id);

		if(isset($_POST['Version']))
        {
			$model->attributes=$_POST['Version'];
            
            if($model->validate() && $model->save())
            {
            	if (Yii::app()->request->isAjaxRequest)
                {
                    echo CJSON::encode(array(
                        'status'=>'success', 
                        'div'=>'houra!'//$this->renderPartial('_success', array('model'=>$model), true, true)
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
		$dataProvider=new CActiveDataProvider('Version');
		$this->render('index',array(
			'dataProvider'=>$dataProvider,
		));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new Version('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Version']))
			$model->attributes=$_GET['Version'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}
	
	public function actionRelated($id)
	{
		$version = $this->loadModel($id);

	
		$model = new VersionRelation;
		$model->source_id = $id;
	
		if(isset($_POST['VersionRelation']))
		{
			$model->attributes=$_POST['VersionRelation'];
	
			if($model->validate() && $model->save())
			{
				$related = new VersionRelation;
				$related->source_id = $model->target_id;
				$related->target_id = $model->source_id;
	
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
					'div'=>$this->renderPartial('relatedForm', array('model'=>$model, 'version' => $version), true, true)));
			exit;
		}
		else
			$this->render('relatedForm',array('model'=>$model, 'issue' => $issue));
	}
	
	public function actionLoadversions()
	{
		$data = Version::model()->findAll('project_id=:project_id', array(':project_id'=>(int) $_POST['project_id']));
	
		$data = CHtml::listData($data,'id','label');
	
		echo "<option value=''>Select Version</option>";
		foreach($data as $value=>$version){
			if(!empty($_POST['version_id']) && $_POST['version_id'] == $value)
				echo CHtml::tag('option', array('value'=>$value, 'selected' => 'selected'),CHtml::encode($version),true);
			else
				echo CHtml::tag('option', array('value'=>$value),CHtml::encode($version),true);
		}
			
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return Version the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=Version::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param Version $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='version-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
