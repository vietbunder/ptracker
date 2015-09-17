<?php
/* @var $this TaskLogController */
/* @var $model TaskLog */
/* @var $form CActiveForm */
$task_list = CHtml::listData(TaskAssignment::model()->with('task')->findAll(array('condition'=>'task.deleted=0 AND task.status != "Complete" AND t.deleted=0')), 'id', 'task.concatened');
?>

<div class="form">
    <script>
        $(document).ready(function(){
            if(document.getElementById('type').value === "Non-project") {
                $("#task_assignment_id").val(null);
                $("#task_assignment_id").prop('disabled', true);
                $("#task_title").prop('disabled', false);
            }
            else if(document.getElementById('type').value === "Project") {
                $("#task_title").val(null);
                $("#task_assignment_id").prop('disabled', false);
                $("#task_title").prop('disabled', true);
            }
            
            $("#type").change(function(){
                if(this.value === "Non-project") {
                    $("#task_assignment_id").val(null);
                    $("#task_assignment_id").prop('disabled', true);
                    $("#task_title").prop('disabled', false);
                }
                else if(this.value === "Project") {
                    $("#task_title").val(null);
                    $("#task_assignment_id").prop('disabled', false);
                    $("#task_title").prop('disabled', true);
                }
            });
        });
    </script>
<?php 
    $form = $this->beginWidget(
        'booster.widgets.TbActiveForm',
        array(
            'id' => 'tasklog-form',
            'type' => 'vertical',
            'htmlOptions' => array('class' => 'well'), // for inset effect
        )
    );
?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>
        
        <?php echo $form->datePickerGroup(
			$model,
			'date',
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
			$model,
			'type',
			array(
				'wrapperHtmlOptions' => array(
					'class' => 'col-sm-5',
				),
				'widgetOptions' => array(
					'data' => array('Project'=>'Project', 'Non-project'=>'Non-project'),
					'htmlOptions' => array('id'=>'type', 'style'=>'width:220px;',),
				),
			)
		); 
        ?>
        
        <?php echo $form->dropDownListGroup(
			$model,
			'task_assignment_id',
			array(
				'wrapperHtmlOptions' => array(
					'class' => 'col-sm-5',
				),
				'widgetOptions' => array(
					'data' => $task_list,
					'htmlOptions' => array('id'=>'task_assignment_id', 'empty'=>'- Select task -', 'style'=>'width:320px;'),
				)
			)
		); 
        ?>
        
        <?php echo $form->textFieldGroup($model, 'task_title', array('widgetOptions'=>array('htmlOptions'=>array('id'=>'task_title')))); ?>
        
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