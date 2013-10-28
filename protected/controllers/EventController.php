<?php

class EventController extends Controller
{
	public function actionDelete()
	{
		$this->render('delete');
	}

	public function actionIndex()
	{
		$this->render('index');
	}

	public function actionView()
	{
		$this->render('view');
	}

}