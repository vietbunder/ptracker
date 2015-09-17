<?php

class SiteController extends Controller {

    /**
     * Declares class-based actions.
     */
    //public $layout='//layouts/column2';
    public function actions() {
        return array(
            // captcha action renders the CAPTCHA image displayed on the contact page
            'captcha' => array(
                'class' => 'CCaptchaAction',
                'backColor' => 0xFFFFFF,
            ),
            // page action renders "static" pages stored under 'protected/views/site/pages'
            // They can be accessed via: index.php?r=site/page&view=FileName
            'page' => array(
                'class' => 'CViewAction',
            ),
        );
    }

    /**
     * This is the default 'index' action that is invoked
     * when an action is not explicitly requested by users.
     */
    public function actionIndex() {
        // renders the view file 'protected/views/site/index.php'
        // using the default layout 'protected/views/layouts/main.php'
        if(!Yii::app()->user->isGuest) {
            $this->redirect(array('dashboard'));
        }
        $this->render('index');
    }

    /**
     * This is the action to handle external exceptions.
     */
    public function actionError() {
        if ($error = Yii::app()->errorHandler->error) {
            if (Yii::app()->request->isAjaxRequest)
                echo $error['message'];
            else
                $this->render('error', $error);
        }
    }

    /**
     * Displays the login page
     */
    public function actionLogin() {
        $model = new LoginForm;

        // if it is ajax validation request
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'login-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }

        // collect user input data
        if (isset($_POST['LoginForm'])) {
            $model->attributes = $_POST['LoginForm'];
            // validate user input and redirect to the previous page if valid
            if ($model->validate() && $model->login()) {
                $member = Member::model()->find(array('condition' => 'account_id=' . Yii::app()->user->id));
                $member->last_login = date('Y-m-d');
                $member->save();

                if (Yii::app()->user->checkAccess('Admin')) {
                    $this->redirect(Yii::app()->user->returnUrl);
                } else if (Yii::app()->user->checkAccess('Manager') || Yii::app()->user->checkAccess('Staff')) {
                    $this->redirect(array('/site/dashboard'));
                }
            }
        }

        // display the login form
        $this->render('login', array('model' => $model));
    }

    /**
     * Logs out the current user and redirect to homepage.
     */
    public function actionLogout() {
        Yii::app()->user->logout();
        $this->redirect(Yii::app()->homeUrl);
    }

    public function actionDashboard() {
        if (Yii::app()->user->isGuest) {
            throw new CHttpException(403, 'You are not authorized to perform this action.');
        }

        //$this->layout = '//layouts/column2';
        if (Yii::app()->user->checkAccess('Staff')) {
            $this->render('dashboard_staff');
        } else {
            $this->render('dashboard');
        }
    }

    public function actionRegister() {
        $accountMember = new AccountMember;
        if (!Yii::app()->user->checkAccess('Admin')) {
            throw new CHttpException(403, 'You are not authorized to perform this action.');
        }

        // Collect input data
        if (isset($_POST['AccountMember'])) {
            $accountMember->attributes = $_POST['AccountMember'];
            if ($accountMember->validate()) {
                // Create account
                $account = new Account;
                $account->username = $accountMember->username;
                $account->password = $accountMember->password;

                // Create member
                if ($account->save()) {
                    $member = new Member;
                    $member->account_id = $account->id;
                    $member->name = $accountMember->name;
                    $member->department = $accountMember->department;
                    $member->role = $accountMember->role;
                    if ($member->save()) {
                        $auth = new AuthAssignment;
                        $auth->itemname = $member->role;
                        $auth->userid = $account->id;
                        if ($auth->save())
                            $this->redirect(array('member'));
                        //echo $account->password;
                    }
                    else {
                        $accountMember->addErrors($member->getErrors());
                    }
                } else {
                    $accountMember->addErrors($account->getErrors());
                }
            }
        }
        $this->render('register', array('model' => $accountMember));
    }

    public function actionMember() {
        if (!Yii::app()->user->checkAccess('Admin')) {
            throw new CHttpException(403, 'You are not authorized to perform this action.');
        }

        $this->layout = '//layouts/column2';
        /*$dataprovider = new CActiveDataProvider('Member', array('sort' => array('defaultOrder' => 'name ASC',
        )));*/
        $model = new Member('search');
        $model->unsetAttributes();  // clear any default values
        if(isset($_GET['Member'])) {
            $model->attributes=$_GET['Member'];
        }

        $this->render('member',array('model'=>$model,));
    }

    public function actionUpdateMember($id) {
        if (!Yii::app()->user->checkAccess('Admin')) {
            throw new CHttpException(403, 'You are not authorized to perform this action.');
        }

        $member = Member::model()->findByPk($id);
        $account = Account::model()->find(array('condition' => 'id=' . $member->account_id));
        $auth = AuthAssignment::model()->find(array('condition' => 'userid=' . $member->account_id));

        $accountMember = new AccountMember;
        $accountMember->name = $member->name;
        $accountMember->username = $account->username;
        $accountMember->department = $member->department;
        $accountMember->role = $member->role;

        if (isset($_POST['AccountMember'])) {
            $accountMember->attributes = $_POST['AccountMember'];
            if ($accountMember->validate()) {
                // Update account
                $account->username = $accountMember->username;
                $account->password = $accountMember->password;

                if ($account->save()) {
                    // Update member
                    $member->name = $accountMember->name;
                    $member->department = $accountMember->department;
                    $member->role = $accountMember->role;

                    if ($member->save()) {
                        // Update AuthAssignment
                        $auth->itemname = $member->role;
                        $auth->userid = $account->id;
                        if ($auth->save())
                            $this->redirect(array('member'));
                        //echo $account->password;
                    }
                    else {
                        $accountMember->addErrors($member->getErrors());
                    }
                } else {
                    $accountMember->addErrors($account->getErrors());
                }
            }
        }
        $this->render('update_member', array('model' => $accountMember));
    }

    public function actionDeleteMember($id) {
        if (!Yii::app()->user->checkAccess('Admin')) {
            throw new CHttpException(403, 'You are not authorized to perform this action.');
        }

        $member = Member::model()->findByPk($id);
        $account = Account::model()->find(array('condition' => 'id=' . $member->account_id));
        $auth = AuthAssignment::model()->find(array('condition' => 'userid=' . $member->account_id));

        if ($member->delete() && $account->delete() && $auth->delete()) {
            $this->redirect(array('member'));
        }
    }
    
    public function actionChangePassword() {
        if (Yii::app()->user->isGuest) {
            throw new CHttpException(403, 'You are not authorized to perform this action.');
        }
        
        $account = Account::model()->findByPk(Yii::app()->user->id);
        $member = Member::model()->find(array('condition'=>'account_id='.Yii::app()->user->id));
        
        $accountMember = new AccountMember;
        $accountMember->username = $account->username;
        $accountMember->name = $member->name;
        $accountMember->department = $member->department;
        $accountMember->role = $member->role;
        
        if(isset($_POST['AccountMember'])) {
            $accountMember->attributes = $_POST['AccountMember'];
            if($accountMember->validate()) {
                $account->password = $accountMember->password;
                if($account->save()) {
                    $this->redirect(array('index'));
                }
            }
        }
        $this->render('_form_changepassword', array('model' => $accountMember));
        
    }

    public function actionCalendarEvents() {
        $items = array();
        $model = Project::model()->findAll(array('condition' => 'creator_id='.Yii::app()->user->id.' AND deleted=0'));
        foreach ($model as $value) {
            $items[] = array(
                'title' => $value->name,
                'start' => $value->start_date,
                'end' => date('Y-m-d', strtotime('+1 day', strtotime($value->due_date))),
                'color' => $value->status == "Expired" ? '#fe6e6e' : 'default',
                'idevent' => $value->id,
                    //'allDay'=>true,
            );
        }
        echo CJSON::encode($items);
        Yii::app()->end();
    }
    
    public function actionCalendarEventsStaff() {
        $items = array();
        $model = TaskAssignment::model()->findAll(array('condition'=>'member_id='.Yii::app()->user->id.' AND deleted=0'));
        foreach ($model as $value) {
            //echo $value->task->title;
            $items[] = array(
                'title' => $value->task->title,
                'start' => $value->task->start_date,
                'end' => date('Y-m-d', strtotime('+1 day', strtotime($value->task->due_date))),
                'color' => $value->task->status == "Not complete" ? '#fe6e6e' : 'default',
                'idevent' => $value->task->id,
                    //'allDay'=>true,
            );
        }
        echo CJSON::encode($items);
        Yii::app()->end();
    }

    public function actionEventDetail($id) {
            if (@$_GET['asModal'] == true) {
                if(!Yii::app()->user->checkAccess('Staff')) {
                    $model = Project::model()->findByPk($id);
                }
                else {
                    $model = Task::model()->findByPk($id);
                }
            }
            $this->renderPartial('event_detail',array('model'=>$model),false,true);
    }
    
    public function actionJsgantt() {
        $this->renderPartial('jsgantt',null,false,false);
    }

}
