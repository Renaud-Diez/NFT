<?php 
class UserController extends Controller
{
	public $user = null;
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout='//user/column2';
	
	public function actionWhosOnWhat()
	{
		$dataProvider = new CActiveDataProvider('User');
		
		$this->render('index',array(
			'dataProvider'=>$dataProvider,
		));
	}
	
	public function actionWeeklyReport($id = null)
	{
		$this->layout='column1';
		if(is_null($id))
			$id = Yii::app()->user->id;
		
		$model = $this->loadModel($id);
		$dataProvider = $model->getUserWeekly();
		
		$this->render('weekly',array(
			'model' => $model,
			'dataProvider'=>$dataProvider,
		));
	}
	
	public function actionWelcom($id = null)
	{
		
		if(is_null($id))
			$id = Yii::app()->user->id;
		
		$model = $this->loadModel($id);
		
		$this->render('welcom',array(
			'model' => $model,
		));
	}
	
	protected function loadModel($id)
	{
		$model=User::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
			
		$this->user = $model;
		return $model;
	}
}

