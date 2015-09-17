<?php

class ProjectController extends Controller
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout='//layouts/column2';

	/**
	 * @return array action filters
	 */
	public function filters()
	{
		return array(
			'accessControl', // perform access control for CRUD operations
			'postOnly + delete', // we only allow deletion via POST request
		);
	}

	/**
	 * Specifies the access control rules.
	 * This method is used by the 'accessControl' filter.
	 * @return array access control rules
	 */
	public function accessRules()
	{
		return array(
			array('allow',
				'users'=>array('@'),
			),
			array('deny'),
		);
	}

	/**
	 * Displays a particular model.
	 * @param integer $id the ID of the model to be displayed
	 */
	public function actionView($id)
	{
            if($this->loadModel($id)->deleted==1) {
                throw new CHttpException(403, 'The page you are requested are invalid');
            }
            
            $tasks = new Task('search');
            $tasks->unsetAttributes();  // clear any default values
            $tasks->has_parent = 0;
            $tasks->project_id = $id;
            if(isset($_GET['Task'])) {
                $tasks->attributes = $_GET['Task'];
            }
            
            $this->render('view',array(
                'model'=>$this->loadModel($id),
                'tasks'=>$tasks,
            ));
	}

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate()
	{
            if (!Yii::app()->user->checkAccess('Manager') && !Yii::app()->user->checkAccess('Admin')) { 
                    throw new CHttpException(403, 'You are not authorized to perform this action.'); 
            }
                
		$model = new Project;
                $model->scenario = 'create';

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Project']))
		{
			$model->attributes = $_POST['Project'];
                        $model->creator_id = Yii::app()->user->id;
                        
                        // Check start date
                        if($model->start_date > date('Y-m-d')) {
                            $model->status = "Upcoming";
                        }
                        else {
                            $model->status = "Active";
                        }
                        
			if($model->save())
				$this->redirect(array('index'));
		}

		$this->render('create',array(
			'model'=>$model,
		));
	}
        
        /*public function actionMyProjects()
        {
            if (!Yii::app()->user->checkAccess('Manager')) { 
                    throw new CHttpException(403, 'You are not authorized to perform this action.'); 
            }
            
            $model = new Project('search');
            $model->unsetAttributes();  // clear any default values
            if(isset($_GET['Project'])) {
                $model->attributes=$_GET['Project'];
            }
            
            $this->render('myprojects',array('model'=>$model,));
        }*/

	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id the ID of the model to be updated
	 */
	public function actionUpdate($id)
	{
            if (!Yii::app()->user->checkAccess('Manager') && !Yii::app()->user->checkAccess('Admin')) { 
                    throw new CHttpException(403, 'You are not authorized to perform this action.'); 
            }
            
		$model=$this->loadModel($id);

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		// Check whether this project owned by login user
                if ($model->creator_id != Yii::app()->user->id) {
                    throw new CHttpException(403, 'This is not your project!');
                }
                
                if(isset($_POST['Project']))
		{
			$model->attributes=$_POST['Project'];
                        
                        // Check start date
                        if($model->start_date > date('Y-m-d')) {
                            $model->status = "Upcoming";
                        }
                        else {
                            $model->status = "Active";
                        }
                        
                        // Check due date
                        if($model->due_date >= date('Y-m-d')) {
                            $model->status = "Active";
                        }
                        
			if($model->save())
				$this->redirect(array('view','id'=>$model->id));
		}

		$this->render('update',array(
			'model'=>$model,
		));
	}

	/**
	 * Deletes a particular model.
	 * If deletion is successful, the browser will be redirected to the 'admin' page.
	 * @param integer $id the ID of the model to be deleted
	 */
	public function actionDelete($id)
	{
            if (!Yii::app()->user->checkAccess('Manager') && !Yii::app()->user->checkAccess('Admin')) { 
                    throw new CHttpException(403, 'You are not authorized to perform this action.'); 
            }
            
                $model = $this->loadModel($id);
                // Check whether this project owned by login user
                if ($model->creator_id != Yii::app()->user->id) {
                    throw new CHttpException(403, 'This is not your project!');
                }
                
		$model->deleted=1;
                if($model->save()) {
                    echo $model->name.' : '.$model->deleted.'<br>';
                    // Delete all its tasks and subtasks
                    $tasks = Task::model()->findAll(array('condition'=>'project_id='.$id.' AND deleted=0'));
                    foreach($tasks as $task) {
                        $subtasks = Subtask::model()->findAll(array('condition'=>'task_id='.$task->id.' AND deleted=0'));
                        $task->deleted=1;
                        $task->save();
                        echo '-'.$task->title.' : '.$task->deleted.'<br>';
                        foreach($subtasks as $subtask) {
                            $subtask->deleted=1;
                            $subtask->save();
                            echo '--'.$subtask->title.' : '.$subtask->deleted.'<br>';
                        }
                    }
                }

		// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
		if(!isset($_GET['ajax']))
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
	}

	/**
	 * Lists all models.
	 */
	public function actionIndex()
	{
            if (!Yii::app()->user->checkAccess('Manager') && !Yii::app()->user->checkAccess('Admin')) { 
                    throw new CHttpException(403, 'You are not authorized to perform this action.'); 
            }
            
            $model = new Project('search');
            $model->unsetAttributes();  // clear any default values
            if(isset($_GET['Project'])) {
                $model->attributes=$_GET['Project'];
            }
            
            if(Yii::app()->user->checkAccess('Manager')) {
                $this->render('myprojects',array('model'=>$model,));
            }
            
            else if(Yii::app()->user->checkAccess('Admin')) {
                $this->render('index',array('model'=>$model,));
            }
	}

        public function actionTimetable($id) {
            if (!Yii::app()->user->checkAccess('Manager') && !Yii::app()->user->checkAccess('Admin')) { 
                    throw new CHttpException(403, 'You are not authorized to perform this action.'); 
            }
            
            $tasks = Task::model()->findAll(array('condition'=>'project_id='.$id.' AND deleted=0', 'order'=>'code ASC'));
            $project = $this->loadModel($id);
            $this->renderPartial('timetable',array('tasks'=>$tasks, 'project'=>$project),false,false);
        }

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return Project the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=Project::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param Project $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='project-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
