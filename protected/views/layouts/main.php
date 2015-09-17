<?php /* @var $this Controller */ ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="language" content="en" />
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
        
	<!-- blueprint CSS framework -->
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/screen.css" media="screen, projection" />
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/print.css" media="print" />
	<!--[if lt IE 8]>
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/ie.css" media="screen, projection" />
	<![endif]-->
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/main.css" />
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/form.css" />

	<title><?php echo CHtml::encode($this->pageTitle); ?></title>
</head>

<body>
<!-- Mainmenu -->
<div class="container" id="page" style="margin-top: 40px;">
<div id="navbar">
        <?php
            $this->widget(
            'booster.widgets.TbNavbar',
            array(
                //'type' => 'inverse',
                'brand' => 'Project Tracker Application',
                'brandOptions' => array('style' => 'width:auto; margin-left:110px; padding:0px;'),
                'brand'=>CHtml::image(Yii::app()->getBaseUrl().'/images/wilmar-brand.png'),
                'collapse' => true,
                'fixed' => 'top',
                'fluid' => true,
                'htmlOptions' => array('style' => 'background:#00525e;'),
                'items' => array(
                    array(
                        'class' => 'booster.widgets.TbMenu',
                        'type' => 'navbar',
                        //'htmlOptions' => array('style' => 'color:black;'),
                        'items' => array(
                            array('label' => 'Home', 'url'=>array('/site/index'), 'visible'=>(Yii::app()->user->isGuest || Yii::app()->user->checkAccess('Admin'))),
                            array('label' => 'Dashboard', 'url'=>array('/site/dashboard'), 'visible'=>(Yii::app()->user->checkAccess('Manager') || Yii::app()->user->checkAccess('Staff'))),
                            //array('label'=>'My project(s)', 'url'=>array('/project/myprojects'), 'visible'=>(!Yii::app()->user->isGuest && Yii::app()->user->checkAccess('Manager'))),
                            array('label'=>'Task(s)', 'url'=>array('/task/mytask'), 'visible'=>Yii::app()->user->checkAccess('Staff')),
                            array('label'=>'Project(s)', 'url'=>array('/project/index'), 'visible'=>Yii::app()->user->checkAccess('Admin') || Yii::app()->user->checkAccess('Manager')),
                            array('label'=>'Log activity', 'url'=>Yii::app()->user->checkAccess('Admin') ? array('/tasklog/index') : array('/tasklog/mylog'), 'visible'=>Yii::app()->user->checkAccess('Admin') || Yii::app()->user->checkAccess('Staff')),
                            array('label'=>'Account(s)', 'url'=>array('/site/member'), 'visible'=>Yii::app()->user->checkAccess('Admin')),
                        )
                    ),
                    array(
                        'class' => 'booster.widgets.TbMenu',
                        'type' => 'navbar',
                        'htmlOptions'=>array(
                            'class'=>'pull-right',
                            'style'=>'margin-right:110px;'
                        ),
                        'items' => array(
                            array('label'=>'Login', 'icon'=>'log-in', 'url'=>array('/site/login'), 'visible'=>Yii::app()->user->isGuest),
                            array('label'=>Yii::app()->user->name, 'icon'=>'user', 'url'=>array('/site/login'), 'visible'=>!Yii::app()->user->isGuest, 
                                'items'=>array(
                                    array('label'=>'Change Password', 'url'=>array('/site/changePassword'), 'visible'=>!Yii::app()->user->isGuest),
                                    array('label'=>'Logout', 'url'=>array('/site/logout'), 'visible'=>!Yii::app()->user->isGuest)
                                )),
                        )
                    )
                )
            )
        );
        ?>
</div>
<!-- mainmenu -->

	<?php if(isset($this->breadcrumbs)):?>
		<?php $this->widget('zii.widgets.CBreadcrumbs', array(
			'links'=>$this->breadcrumbs,
		)); ?><!-- breadcrumbs -->
	<?php endif?>

	<?php echo $content; ?>

	<div class="clear"></div>

	<div id="footer">
		Copyright &copy; <?php echo date('Y'); ?> by Management Information System.<br/>
		All Rights Reserved.<br/>
		<?php echo Yii::powered(); ?>
	</div><!-- footer -->

</div><!-- page -->

</body>
</html>
