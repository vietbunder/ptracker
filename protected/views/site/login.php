<?php
/* @var $this SiteController */
/* @var $model LoginForm */
/* @var $form CActiveForm  */

$this->pageTitle=Yii::app()->name . ' - Login';
$this->widget('booster.widgets.TbBreadcrumbs', array(
    'links'=>array('Login')
));
?>

<h2 class="page-header">Login</h2>
<div class="form">
<?php 
    $form = $this->beginWidget(
        'booster.widgets.TbActiveForm',
        array(
            'id' => 'login-form',
            'type' => 'vertical',
            'htmlOptions' => array('class' => 'span-19 well'), // for inset effect
        )
    );
?>
        <p><i>Please fill out the following form with your login credentials:</i></p>
        
        <?php echo $form->errorSummary($model); ?>
        
        <div class="row span-5">
        <?php echo $form->textFieldGroup($model, 'username'); ?>
	
        <?php echo $form->passwordFieldGroup($model, 'password'); ?>

	<div class="row buttons">
		<?php $this->widget(
			'booster.widgets.TbButton',
			array(
				'buttonType' => 'submit',
				'context' => 'primary',
				'label' => 'Login'
			)
		); ?>
	</div>

<?php $this->endWidget(); ?>
</div><!-- form -->
