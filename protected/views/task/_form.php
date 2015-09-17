<?php
/* @var $this TaskController */
/* @var $model Task */
/* @var $form CActiveForm */
$staff_list = CHtml::listData(Member::model()->findAll(array('condition'=>'role="Staff"')),'account_id', 'name');
?>

<div class="form">

<?php 
    $form = $this->beginWidget(
        'booster.widgets.TbActiveForm',
        array(
            'id' => 'taskForm',
            'type' => 'vertical',
            'htmlOptions' => array('class' => 'well'), // for inset effect
        )
    );
?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>
        
        <?php echo $form->textFieldGroup($model, 'code'); ?>

        <?php echo $form->textFieldGroup($model, 'title'); ?>
        
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
        <?php echo $form->dropDownListGroup(
			$assignment,
			'member_id',
			array(
				'wrapperHtmlOptions' => array(
					'class' => 'col-sm-5',
				),
				'widgetOptions' => array(
					'data' => $staff_list,
					'htmlOptions' => array('style'=>'width:320px;','empty'=>'- Select Staff -',),
				)
			)
		); 
        ?>

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