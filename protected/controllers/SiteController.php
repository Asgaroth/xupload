<?php
Yii::import("xupload.models.XUploadForm");
class SiteController extends Controller {

	public function actions() {
		return array('upload' => array('class' => 'xupload.actions.XUploadAction', 'subfolderVar' => 'parent_id', 'path' => realpath(Yii::app() -> getBasePath() . "/../images/uploads"), ), );
	}

	/**
	 * This is the default 'index' action that is invoked
	 * when an action is not explicitly requested by users.
	 */
	public function actionIndex() {
		// renders the view file 'protected/views/site/index.php'
		// using the default layout 'protected/views/layouts/main.php'
		$model = new XUploadForm;
		$this -> render('index', array('model' => $model, ));
	}

	/**
	 * Single queued file upload
	 */
	public function actionQueue() {
		$model = new XUploadForm;
		$this -> render('queue', array('model' => $model, ));
	}

	/**
	 * Single queued file upload
	 */
	public function actionMultiple() {
		$model = new XUploadForm;
		$this -> render('multiple', array('model' => $model, ));
	}

	/**
	 * This is the action to handle external exceptions.
	 */
	public function actionError() {
		if ($error = Yii::app() -> errorHandler -> error) {
			if (Yii::app() -> request -> isAjaxRequest)
				echo $error['message'];
			else
				$this -> render('error', $error);
		}
	}

}
