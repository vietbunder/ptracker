<?php 
    $this->pageTitle = Yii::app()->name . ' - Register'; 
    $this->widget('booster.widgets.TbBreadcrumbs', array(
        'links'=>array('Change password'),
    ));
?>

<h2 class="page-header">Change password</h2>
<p>You can update password by filling this following form:</p> 

<div class="form">
    <?php 
        $form = $this->beginWidget(
            'booster.widgets.TbActiveForm',
            array(
                'id' => 'registration-form',
                'type' => 'horizontal',
                'htmlOptions' => array('class' => 'well'), // for inset effect
            )
        );
    ?>
    
    <?php echo $form->errorSummary($model); ?>
    
    <?php echo $form->passwordFieldGroup(
			$model,
			'password',
			array(
                                'widgetOptions' => array(
					'htmlOptions' => array('maxlength'=>25, 'class'=>'span-8')
				),
			));
    ?>
    
    <?php echo $form->passwordFieldGroup(
			$model,
			'retype_password',
			array(
                                'widgetOptions' => array(
					'htmlOptions' => array('maxlength'=>25, 'class'=>'span-8')
				),
			));
    ?>
    <center>
    <div class="form-actions">
		<?php $this->widget(
			'booster.widgets.TbButton',
			array(
				'buttonType' => 'submit',
				'context' => 'primary',
				'label' => 'Submit'
			)
		); ?>
		<?php $this->widget(
			'booster.widgets.TbButton',
			array('buttonType' => 'reset', 'label' => 'Reset')
		); ?>
	</div> 
    </center>
    <?php $this->endWidget(); ?>
</div>

