<?php
//Yii::import('application.models.ProjectMilestoneForm');

class MilestoneWidget extends CWidget
{
    public $title='Project Milestone';
    public $visible=true; 
    public $project_id;
    public $id = 'MilestoneWidget';
    public $name = 'ProjectMilestoneForm';
    public $form;
    public $model;
    
 
    public function init()
    {
        if($this->visible)
        {
 			//$this->project_id = $model->id;
        }
    }
 
    public function run()
    {
		if($this->visible)
        {
            $this->renderContent();
        }
    }
 
    protected function renderContent()
    {
        $model=new Milestone;
        $model->project_id = $this->project_id;
        
        //$this->performAjaxValidation($model);

		if(isset($_POST['Milestone']))
		//if(isset($_POST))
		{
			echo '-xxxxxxxx----';
			$model->attributes=$_POST['Milestone'];
			if($model->save())
			{
				//$this->redirect(array('view','id'=>$model->id));
			}
			else
			{
				echo '------';
			}
				
		}

		$this->render('milestone',array(
			'model'=>$model,
		));
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