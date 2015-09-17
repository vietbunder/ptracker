<?php

class TaskController extends Controller
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
            $tasks->parent_id = $id;
            if(isset($_GET['Task'])) {
                $tasks->attributes=$_GET['Task'];
            }
            $this->render('view',array('model'=>$this->loadModel($id), 'tasks'=>$tasks));
	}

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate($id)
	{
            if (!Yii::app()->user->checkAccess('Manager') && !Yii::app()->user->checkAccess('Admin')) { 
                    throw new CHttpException(403, 'You are not authorized to perform this action.'); 
            }
                // Check whether this user is the owner of the projects
                $ownerid = Project::model()->findByPk($id)->creator_id;
                if(Yii::app()->user->id != $ownerid) {
                    throw new CHttpException(403, 'You are not authorized to perform this action.');
                }
                
		$model = new Task;
                $model->scenario = 'create';
                
                $assignment = new TaskAssignment;

		if(isset($_POST['Task']) && isset($_POST['TaskAssignment']))
		{
			$model->attributes = $_POST['Task'];
                        
                        // Synchronize due date with project
                        $project = Project::model()->findByPk($id);
                        if($project->due_date < $model->due_date) {
                            $project->due_date = $model->due_date;
                            $project->save();
                        }
                        
                        $model->project_id = $id;
                        $model->creator_id = Yii::app()->user->id;
                        $model->status = "Not complete";
			if($model->save()) {
                            if(isset($_POST['TaskAssignment']['member_id'])) {
                                $assignment->task_id = $model->id;
                                $assignment->member_id = $_POST['TaskAssignment']['member_id'];
                                $assignment->save();
                            }
                            $this->redirect(array('view','id'=>$model->id));
                        }
		}

		$this->render('create',array(
			'model'=>$model, 'id'=>$id, 'assignment'=>$assignment,
		));
	}
        
        public function actionCreateChild($id) {
            if (!Yii::app()->user->checkAccess('Manager') && !Yii::app()->user->checkAccess('Admin')) { 
                    throw new CHttpException(403, 'You are not authorized to perform this action.'); 
            }
            // Retrieve the parent task
            $parent_task = Task::model()->findByPk($id);
            
            // Check whether this user is the owner of the projects
            $ownerid = Project::model()->findByPk($parent_task->project_id)->creator_id;
            if(Yii::app()->user->id != $ownerid) {
                throw new CHttpException(403, 'You are not authorized to perform this action.');
            }
            
            $model = new Task;
            $model->scenario = 'create';
            
            // Get the member_id of parent task_assignment
            $parent_memberid = TaskAssignment::model()->find(array('condition'=>'task_id='.$parent_task->id));
            
            $assignment = new TaskAssignment;
            if($parent_memberid) {
                $assignment->member_id = $parent_memberid->member_id;
            }

            if(isset($_POST['Task']) && isset($_POST['TaskAssignment'])) {
                $model->attributes = $_POST['Task'];
                
                // Synchronize due date with project
                $project = Project::model()->findByPk($parent_task->project_id);
                if($project->due_date < $model->due_date) {
                    $project->due_date = $model->due_date;
                    $project->save();
                }
                
                // Synchronize due date with parent task
                $parent = Task::model()->findByPk($id);
                while($parent->has_parent) {
                    if($parent->due_date < $model->due_date) {
                        $parent->due_date = $model->due_date;
                        $parent->save();
                    }
                    $parent = Task::model()->findByPk($parent->parent_id);
                }
                if($parent->due_date < $model->due_date) {
                    $parent->due_date = $model->due_date;
                    $parent->save();
                }
                
                $model->project_id = $parent_task->project_id;
                $model->creator_id = Yii::app()->user->id;
                $model->has_parent = 1;
                $model->parent_id = $id;
                $model->status = "Not complete";
                if($model->save()) {
                    if(isset($_POST['TaskAssignment']['member_id'])) {
                        $assignment->task_id = $model->id;
                        $assignment->member_id = $_POST['TaskAssignment']['member_id'];
                        $assignment->save();
                    }
                    //echo $model->id;
                    $this->check_parent($model->id);
                    $this->uncheck_parent($model->id);
                    $this->redirect(array('view','id'=>$parent_task->id));
		}
            }
            
            $this->render('create_child',array('model'=>$model, 'parent_task'=>$parent_task, 'assignment'=>$assignment));
        }

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
                // Check whether this user is the owner of the projects
                $ownerid = Task::model()->findByPk($id)->project->creator_id;
                if($this->loadModel($id)->deleted==1) {
                    throw new CHttpException(403, 'The page you are requested are invalid');
                }
                
                if(Yii::app()->user->id != $ownerid) {
                    throw new CHttpException(403, 'You are not authorized to perform this action.');
                }
                
		$model = $this->loadModel($id);
                $model->scenario = 'update';
                
                // Load the parent task_assignment (if exist)
                $assignment = TaskAssignment::model()->find(array('condition'=>'task_id='.$model->id.' AND deleted=0'));
                if(!$assignment) {
                    $assignment = new TaskAssignment;
                }

		if(isset($_POST['Task']) && isset($_POST['TaskAssignment']))
		{
			$model->attributes=$_POST['Task'];
                        
                        // Synchronize due date with project
                        $project = Project::model()->findByPk($model->project_id);
                        if($project->due_date < $model->due_date) {
                            $project->due_date = $model->due_date;
                            $project->save();
                        }

                        // Synchronize due date with parent task
                        $parent = Task::model()->findByPk($id);
                        while($parent->has_parent) {
                            if($parent->due_date < $model->due_date) {
                                $parent->due_date = $model->due_date;
                                $parent->save();
                            }
                            $parent = Task::model()->findByPk($parent->parent_id);
                        }
                        if($parent->due_date < $model->due_date) {
                            $parent->due_date = $model->due_date;
                            $parent->save();
                        }
                        
			if($model->save()) {
                            if($_POST['TaskAssignment']['member_id'] != null) {
                                $assignment->task_id = $model->id;
                                $assignment->member_id = $_POST['TaskAssignment']['member_id'];
                                $assignment->save();
                                $this->redirect(array('view','id'=>$model->id));
                            }
                            else {
                                $assignment->task_id = $model->id;
                                $assignment->deleted = 1;
                                $assignment->save();
                                $this->redirect(array('view','id'=>$model->id));
                            }
                        }
		}
                $this->render('update',array('model'=>$model, 'assignment'=>$assignment));
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
                // Check whether this user is the owner of the projects
                $ownerid = Task::model()->findByPk($id)->project->creator_id;
                $model = $this->loadModel($id);
                
                if($this->loadModel($id)->deleted==1) {
                    throw new CHttpException(403, 'The page you are requested are invalid');
                }
                if(Yii::app()->user->id != $ownerid) {
                    throw new CHttpException(403, 'You are not authorized to perform this action.');
                }
                
                // Change 'deleted' status
		$this->delete($id);
                
                // Is it affecting status of its parent_id
                if($model->parent) {
                    $this->check_parent($id);
                    $this->uncheck_parent($id);
                }

		// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
		if(!isset($_GET['ajax']))
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
	}
        
        public function delete($id) {
            $task =  $this->loadModel($id);
            $task->deleted = 1;
            if($task->save()) {
                $childtask = Task::model()->findAll(array('condition'=>'parent_id='.$id));
                if($childtask) {
                    foreach($childtask as $item) {
                        $this->delete($item->id);
                    }
                }
            }
            else {
                throw new CHttpException(403, 'And error has occured while processing your request.');
            }
        }
        
        public function actionMyTask()
        {
            $mytask = new TaskAssignment('my');
            $mytask->unsetAttributes();  // clear any default values
            if(isset($_GET['TaskAssignment'])) {
                $mytask->attributes=$_GET['TaskAssignment'];
            }
            $this->render('mytask',array('mytask'=>$mytask,));
            
            /*$criteria = new CDbCriteria;
            $criteria->join = 'JOIN t_task ON t.task_id = t_task.id';
            $criteria->condition = 'member_id='.Yii::app()->user->id.' AND t_task.deleted=0 AND t.deleted=0 AND status != "Complete"';
            
            $mytask = new CActiveDataProvider('TaskAssignment', array('criteria'=>$criteria));
            $this->render('mytask',array(
			'mytask'=>$mytask,
		));*/
        }
        
        public function actionCheck($id)
        {
            if (!Yii::app()->user->checkAccess('Manager') && !Yii::app()->user->checkAccess('Admin')) { 
                    throw new CHttpException(403, 'You are not authorized to perform this action.'); 
            }
            // Check whether this user is the owner of the projects
            $ownerid = Task::model()->findByPk($id)->project->creator_id;
            $model = $this->loadModel($id);
            
            if($this->loadModel($id)->deleted==1) {
                throw new CHttpException(403, 'The page you are requested are invalid');
                
            }
            if(Yii::app()->user->id != $ownerid) {
                throw new CHttpException(403, 'You are not authorized to perform this action.');
            }
            // Change the status
            $this->check($id);
            
            if($model->parent) {
                // Are all of child tasks of this parent task complete ?
                $this->check_parent($id);
                $this->redirect(array('view', 'id'=>$this->loadModel($id)->parent_id));
            }
            else {
                $this->redirect(array('project/view', 'id'=>$model->project_id));
            }
        }
        
        public function check($id) {
            $task =  $this->loadModel($id);
            $task->status = "Complete";
            if($task->save()) {
                $childtask = Task::model()->findAll(array('condition'=>'parent_id='.$id));
                if($childtask) {
                    foreach($childtask as $item) {
                        $this->check($item->id);
                    }
                }
            }
            else {
                throw new CHttpException(403, 'And error has occured while processing your request.');
            }
        }
        
        
        public function check_parent($id) {
            $parent_id = Task::model()->findByPk($id)->parent_id;
            if(isset($parent_id)) {
                // Check whether it has child task or not
                $childtask = Task::model()->count(array('condition'=>'parent_id='.$parent_id.' AND deleted=0'));
                if($childtask == 0) {
                    // Nothing happen
                    return 0;
                }
                else {
                    $uncomplete_task = Task::model()->count(array('condition'=>'parent_id='.$parent_id.' AND deleted=0 AND status="Not complete"'));

                    if($uncomplete_task == 0) {
                        //echo $parent_id.'<br>';
                        $model = $this->loadModel($parent_id);
                        $model->status = "Complete";
                        $model->save();

                        if($model->has_parent) {
                            $parent_id = $model->parent_id;
                            $this->check_parent($parent_id);
                        }
                    }
                }
                
            }
        }
        
        public function actionUncheck($id)
        {
            if (!Yii::app()->user->checkAccess('Manager') && !Yii::app()->user->checkAccess('Admin')) { 
                    throw new CHttpException(403, 'You are not authorized to perform this action.'); 
            }
            // Check whether this user is the owner of the projects
            $ownerid = Task::model()->findByPk($id)->project->creator_id;
            $model = $this->loadModel($id);
            
            if($this->loadModel($id)->deleted==1) {
                throw new CHttpException(403, 'The page you are requested are invalid');
                
            }
            if(Yii::app()->user->id != $ownerid) {
                throw new CHttpException(403, 'You are not authorized to perform this action.');
            }
            
            // Change the status
            $this->uncheck($id);
            
            if($model->parent) {
                // Are all of child tasks of this parent task complete ?
                $this->uncheck_parent($id);
                $this->redirect(array('view', 'id'=>$model->parent_id));
            }
            else {
                $this->redirect(array('project/view', 'id'=>$model->project_id));
            }
        }
        
        public function uncheck($id) {
            $task =  $this->loadModel($id);
            $task->status = "Not complete";
            if($task->save()) {
                $childtask = Task::model()->findAll(array('condition'=>'parent_id='.$id));
                if($childtask) {
                    foreach($childtask as $item) {
                        $this->uncheck($item->id);
                    }
                }
            }
            else {
                throw new CHttpException(403, 'And error has occured while processing your request.');
            }
        }
        
        public function uncheck_parent($id) {
            $parent_id = Task::model()->findByPk($id)->parent_id;
            if(isset($parent_id)) {
                $uncomplete_task = Task::model()->count(array('condition'=>'parent_id='.$parent_id.' AND deleted=0 AND status="Not complete"'));

                if($uncomplete_task > 0) {
                    //echo $parent_id.'<br>';
                    $model = $this->loadModel($parent_id);
                    $model->status = "Not complete";
                    $model->save();

                    if($model->has_parent == 1) {
                        $parent_id = $model->parent_id;
                        $this->uncheck_parent($parent_id);
                    }
                }
            }
        }

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return Task the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=Task::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param Task $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='task-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
