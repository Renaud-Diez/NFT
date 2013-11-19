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
	
	
	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id the ID of the model to be updated
	 */
	public function actionPassword($id)
	{
		$model=$this->loadModel($id);
	
		if(isset($_POST['User']))
		{
			$model->attributes=$_POST['User'];
			$model->setScenario('password');
	
			if($model->validate() && $model->save())
			{
				if (Yii::app()->request->isAjaxRequest)
				{
					echo CJSON::encode(array(
							'status'=>'success',
							'div'=>'Password successfully modified'//$this->renderPartial('_success', array('model'=>$model), true, true)
					));
					exit;
				}
				/*else
					$this->redirect(array('view','id'=>$model->id));*/
			}
		}
	
	
		if (Yii::app()->request->isAjaxRequest)
		{
			Yii::app()->clientScript->scriptMap['jquery.js'] = false;
			Yii::app()->clientScript->scriptMap['jquery.min.js'] = false;
			echo CJSON::encode(array(
					'status'=>'failure',
					'div'=>$this->renderPartial('password', array('model'=>$model), true, true)));
			exit;
		}
		else
			$this->render('password',array('model'=>$model,));
	}
	
	public function actionImport()
	{
		$model = User::model();
		$model->attachBehavior('UserImportBehavior', new UserImportBehavior);
	
		$file = new FileImportForm;
	
		if(!isset($_POST['cancel']) && $_POST['FileImportForm']){
			if($_POST['FileImportForm']['path']){
				$records = $model->importUsers($_POST['FileImportForm']['path'], $_GET['version'], $_GET['milestone']);
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
	
		//$this->loadSidebar($model);
	
		$this->render('import',array(
				'filePath' => $path,
				'sheet'=>$arrSheet,
				'model'=>$model,
				'file'=>$file,
				'message' => $records
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
	
		if(isset($_POST['User']))
		{
			$model->attributes=$_POST['User'];
			//$model->setScenario('password');
	
			if($model->validate() && $model->save())
			{
				if (Yii::app()->request->isAjaxRequest)
				{
					echo CJSON::encode(array(
							'status'=>'success',
							'div'=>'Information successfully updated'//$this->renderPartial('_success', array('model'=>$model), true, true)
					));
					exit;
				}
			}
		}
	
	
		if (Yii::app()->request->isAjaxRequest)
		{
			Yii::app()->clientScript->scriptMap['jquery.js'] = false;
			Yii::app()->clientScript->scriptMap['jquery.min.js'] = false;
			echo CJSON::encode(array(
					'status'=>'failure',
					'div'=>$this->renderPartial('update', array('model'=>$model), true, true)));
			exit;
		}
		else
			$this->render('update',array('model'=>$model,));
	}
	
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
	
	public function actionWorkload($id)
	{
		$this->setDateRangeSearch();
		
		if(!$_POST['DateRangeForm']['from'])
			$this->search->from = date('Y-m-d', strtotime('first day of last month'));;
	
		$this->render('workload',array(
				'model'=>$this->loadModel($id),
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
	
	public function actionView($id = null)
	{
		if(is_null($id))
			$id = Yii::app()->user->id;
		
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
	
	public function actionGtd($id = null)
	{
		if(is_null($id))
			$id = Yii::app()->user->id;
		
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
				'model'=>$model, 'issue' => $issue, 'openIssues'=>$openIssues,'todoIssues'=>$todoIssues,'doneIssues' => $doneIssues
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
			
			if($_POST['DateRangeForm']['team']){
				$search->team = $_POST['DateRangeForm']['team'];
				
				if($search->team > 0){
					$team = Team::model()->findByPk($search->team);
					Yii::trace('TEAM: ' . $team->id,'models.team');
					$arr = $team->getAllMembers();
					if(is_array($arr)){
						$arrIds = $arr['id'];
				
						if(is_null($criteria))
							$criteria = new CDbCriteria;
						
						$criteria->addInCondition('id', $arrIds);
					}
				}
			}
		}
	
		$this->search = $search;
		return $criteria;
	}
}

