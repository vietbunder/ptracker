<!-- form --> 
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
    
    <p class="note">Fields with <span  class="required">*</span> are required.</p>
    
    <?php echo $form->errorSummary($model); ?>
    
    <div>
    <?php echo $form->textFieldGroup(
			$model,
			'name',
			array(
                                'widgetOptions' => array(
					'htmlOptions' => array('maxlength'=>64, 'class'=>'span-12')
				),
			));
    ?>
    </div>
    
    <?php echo $form->textFieldGroup(
			$model,
			'department',
			array(
                                'widgetOptions' => array(
					'htmlOptions' => array('maxlength'=>64, 'class'=>'span-12')
				),
			));
    ?>
    
    <?php echo $form->dropDownListGroup(
			$model,
			'role',
			array(
				'wrapperHtmlOptions' => array(
					'class' => 'col-sm-5',
				),
				'widgetOptions' => array(
					'data' => array('Admin'=>'Admin', 'Manager'=>'Manager', 'Staff'=>'Staff'),
					'htmlOptions' => array('style'=>'width:120px;'),
				)
			)
		); 
    ?>
    
    <?php echo $form->textFieldGroup(
			$model,
			'username',
			array(
                                'widgetOptions' => array(
					'htmlOptions' => array('maxlength'=>25, 'class'=>'span-8')
				),
			));
    ?>
    
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

