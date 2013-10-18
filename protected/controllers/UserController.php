<?php 
class UserController extends Controller
{
	public $user = null;
	public $project = null;
	public $issue = null;
	public $search = null;
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout='//user/column2';
	
	public function actionWhosOnWhat()
	{
		$criteria = $this->setDateRangeSearch();
		
		$dataProvider = new CActiveDataProvider('User', array('criteria' => $criteria));
		

		$this->render('index',array(
			'dataProvider'=>$dataProvider
		));
	}
	
	public function actionWeeklyReport($id = null)
	{
		//$this->layout='column1';
		if(is_null($id))
			$id = Yii::app()->user->id;
		
		$model = $this->loadModel($id);
		
		$criteria = $this->setDateRangeSearch();
		
		$dataProvider = $model->getUserWeekly($this->search);
		
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
	
	public function actionView($id)
	{
		$model = $this->loadModel($id);
		
		$dataProvider = $model->issueList($issue);
		
		$issue=new Issue('search');
		$issue->unsetAttributes();  // clear any default values
		
		if($_GET['project'])
		{
			$pid = $_GET['project'];
			$this->project = $this->loadProject($pid);
			$model->project_id = $pid;
		}
		
		if(Yii::app()->session['myIssues'] == 'true')
			$issue->assignee_id = Yii::App()->user->id;
		
		if(isset($_GET['Issue']))
			$issue->attributes=$_GET['Issue'];
		
		$dataProvider = $model->issueList($issue);
		
		
		$this->render('view',array(
				'model'=>$model, 'issue' => $issue, 'dataProvider' => $dataProvider
		));
	}
	
	public function actionGtd($id)
	{
		$this->loadModel($id);
		
		$model = Project::model();
		$order = 'project_id ASC, due_date DESC, t.label ASC';;
		
		$project = false;
		if($_POST['Project'] && !empty($_POST['Project']['label'])){
			$project = $_POST['Project']['label'];
		}
		
		$issue=new Issue('search');
		$issue->unsetAttributes();
		
		if(Yii::app()->session['myIssues'] == 'true')
			$issue->assignee_id = Yii::App()->user->id;
		
		if(isset($_GET['Issue']))
			$issue->attributes=$_GET['Issue'];
		
		//$dataProvider = $model->getDataProviderIssues($issue, 'ist.alias ASC, ist.rank ASC, type_id ASC');
		$openIssues = $this->user->issueList($issue, $statusAlias = 'open', $order, $project);
		$todoIssues = $this->user->issueList($issue, $statusAlias = 'todo', $order, $project);
		$doneIssues = $this->user->issueList($issue, $statusAlias = 'done', $order, $project);
		
		$this->render('gtd',array(
				'model'=>$model, 'openIssues'=>$openIssues,'todoIssues'=>$todoIssues,'doneIssues' => $doneIssues
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
}

