<?php
// Making task array
$task = $model;
$links = array();
$links = array_merge(array('Log activity'), $links);
$links = array_merge(array($task->title=>array('task/view', 'id'=>$task->id)), $links);
while($task->has_parent) {
    $task = Task::model()->findByPk($task->parent_id);
    $links = array_merge(array($task->title=>array('task/view', 'id'=>$task->id)), $links);
}
$links = array_merge(array($model->project->name=>array('project/view', 'id'=>$model->project_id)), $links);
$links = array_merge(array('Projects'=>array('project/index')), $links);

// Displaying breadcrumbs
$this->widget('booster.widgets.TbBreadcrumbs', array(
    'links'=>$links
));
?>
<h2 class="page-header">Log activity</h2>
<?php $this->widget('booster.widgets.TbGridView',
    array(
        'id' => 'log-activity',
        'type'=>'striped bordered condensed hover',
        'dataProvider' => $tasklog->view($model->id),
        'filter' => $tasklog,
        'afterAjaxUpdate' => 'reinstallDatePicker',
        'columns' => array(
            array(
                    'header' => 'No',
                    'value' => '$this->grid->dataProvider->pagination->currentPage*$this->grid->dataProvider->pagination->pageSize + $row+1',
                    'htmlOptions'=>array('width'=>40),
                ),
            array(
                    'header' => 'Member',
                    'name'=>'member_search',
                    'value'=> '$data->member->username',
                    'htmlOptions'=>array('width'=>180),
                ),
            array(
                    'header' => 'Type',
                    'name'=>'type',
                    'value'=> '$data->type',
                    'filter'=>array(''=>'All', 'Project'=>'Project', 'Non-project'=>'Non-project'),
                    'htmlOptions'=>array('width'=>100),
                ),
            array(
                    'header' => 'Task',
                    'name'=>'title_search',
                    'value'=> '$data->type == "Non-project" ? $data->task_title : $data->taskAssignment->task->title',
                    'htmlOptions'=>array('width'=>500),
                ),
            array(
                    'header'=>'Date',
                    'name'=>'date_search',
                    'value'=> 'date(\'d-m-Y\', strtotime($data->date))',
                    'filter' => $this->widget('zii.widgets.jui.CJuiDatePicker',
                            array(
                                'model' => $tasklog,
                                'attribute' => 'date_search',
                                'language' => 'en',
                                //'i18nScriptFile' => 'jquery.ui.datepicker-ja.js',
                                'options'=>array(
                                    'format' => 'dd-mm-yyyy',
                                ),
                                'htmlOptions' => array(
                                    'id' => 'log_date',
                                ),
                            ),true),
                    /*'filter'=>$this->widget(
                                'booster.widgets.TbDatePicker',
                                array(
                                    'model'=>$tasklog,
                                    'attribute'=>'date_search',
                                    'htmlOptions' => array('id'=>'log_date'),
                                    'options' => array(
                                        'format'=>'dd-mm-yyyy',
                                        'language'=>'en',
                                        'autoclose' => true,
                                    ),
                                ),true),*/
                    'htmlOptions'=>array('width'=>100),
                ),
        )
    )
);

Yii::app()->clientScript->registerScript('re-install-date-picker', "
    function reinstallDatePicker(id, data) {
        $('#log_date').datepicker({
            format: 'dd-mm-yyyy',
            autoclose: true
        });
    }
");
?>