<?php
$this->widget('booster.widgets.TbDetailView', array(
        'id'=>'tasklog-detail',
	'data'=>$model,
	'attributes'=>array(
            array(
                'label'=>'Date',
                'value'=>$model->date,
            ),
            array(
                'label'=>'Type',
                'value'=>$model->type,
            ),
            array(
                'label'=>'Task title',
                'value'=>isset($model->task_assignment_id) ? $model->taskAssignment->task->title : $model->task_title,
            ),
            array(
                'label'=>'Description',
                'value'=>$model->description,
            ),
	),
)); 
//echo $model->status;
?>