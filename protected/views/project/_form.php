<?php
/* @var $this ProjectController */
/* @var $model Project */
/* @var $form CActiveForm */
?>

<div class="form">

<?php 
    $form = $this->beginWidget(
        'booster.widgets.TbActiveForm',
        array(
            'id' => 'projectForm',
            'type' => 'vertical',
            'htmlOptions' => array('class' => 'well'), // for inset effect
        )
    );
?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>
        
        <?php echo $form->textFieldGroup($model, 'code'); ?>

        <?php echo $form->textFieldGroup($model, 'name'); ?>
        
        <?php echo $form->textAreaGroup(
			$model,
			'description',
			array(
				'wrapperHtmlOptions' => array(
					'class' => 'col-sm-5',
				),
				'widgetOptions' => array(
					'htmlOptions' => array('rows' => 5),
				)
			)
		); ?>
        
	<?php echo $form->datePickerGroup(
			$model,
			'start_date',
			array(
				'widgetOptions' => array(
					'options' => array(
						'language' => 'en',
                                                'format' => 'yyyy-mm-dd'
					),
				),
				'wrapperHtmlOptions' => array(
					'class' => 'col-sm-5',
				),
			)
		); ?>

	<?php echo $form->datePickerGroup(
			$model,
			'due_date',
			array(
				'widgetOptions' => array(
					'options' => array(
						'language' => 'en',
                                                'format' => 'yyyy-mm-dd'
					),
				),
				'wrapperHtmlOptions' => array(
					'class' => 'col-sm-5',
				),
			)
		); ?>

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

<?php $this->endWidget(); ?>

</div><!-- form -->