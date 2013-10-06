<?php
/**
 * Controller is the customized base controller class.
 * All controller classes for this application should extend from this base class.
 */
class Controller extends RController
{
	/**
	 * @var string the default layout for the controller view. Defaults to '//layouts/column1',
	 * meaning using a single column layout. See 'protected/views/layouts/column1.php'.
	 */
	public $layout='//layouts/column1';
	/**
	 * @var array context menu items. This property will be assigned to {@link CMenu::items}.
	 */
	public $menu=array();
	/**
	 * @var array the breadcrumbs of the current page. The value of this property will
	 * be assigned to {@link CBreadcrumbs::links}. Please refer to {@link CBreadcrumbs::links}
	 * for more details on how to specify this property.
	 */
	public $breadcrumbs=array();
	
	public function filters() 
	{
		return array( 'rights' );
	}

	public function init()
	{
		//Yii::trace('SEARCH: ' . $_POST['issue'],'models.project');
		if($_POST['issue']){
			$model = Issue::model()->findByPk($_POST['issue']);
			if(!is_null($model))
				$this->redirect('/issue/view/'.$model->id);
		}
		
		if($_GET['me'] == 'true'){
			Yii::app()->session['myIssues'] = true;
		}elseif($_GET['me'] == 'false'){
			unset(Yii::app()->session['myIssues']);
		}
	}
}