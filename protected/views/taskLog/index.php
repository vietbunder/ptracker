<?php
/* @var $this TaskLogController */
/* @var $dataProvider CActiveDataProvider */
$this->widget('booster.widgets.TbBreadcrumbs', array(
    'links'=>array('Log activity')
));

$this->menu=array(
        array('label'=>'View Task(s)', 'url'=>array('task/mytask')),
	array('label'=>'Create log', 'url'=>array('tasklog/create')),
);
?>

<h2 class="page-header">Log activity</h2>
<?php $this->widget('booster.widgets.TbGridView',
    array(
        'id' => 'log-activity',
        'type'=>'striped bordered condensed hover',
        'dataProvider' => $tasklog->index(),
        'filter'=>$tasklog,
        'columns' => array(
            array(
                    'header' => 'No',
                    'value' => '$this->grid->dataProvider->pagination->currentPage*$this->grid->dataProvider->pagination->pageSize + $row+1',
                    'htmlOptions'=>array('width'=>40),
                ),
            array(
                    'header' => 'Staff',
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
                    'htmlOptions'=>array('width'=>100),
                ),
            array(
                'class'=>'booster.widgets.TbButtonColumn',
                'header'=>'Options',
                'headerHtmlOptions'=>array(
                    'style' => 'text-align: center;',
                    ),
                'template'=>'{view} {update} {delete}',
                'htmlOptions'=>array(
                    'width'=>80,
                    'style'=>'text-align:center;'
                    ),
                'buttons'=>array(
                    'view'=>array(
                        'url'=>'$data->id',
                        'click'=>'js:function() {
                                    $("#tasklog-detail-header").html("Log Detail");
                                    $("#tasklog-detail-body").load("'.Yii::app()->createUrl('tasklog/view').'/"+$(this).attr("href")+"?asModal=true");
                                    $("#tasklog-detail").modal();
                                    return false;
                                }'
                        )
                    ),
		),
        )
    )
);

Yii::app()->clientScript->registerScript('re-install-date-picker', "
    function reinstallDatePicker(id, data) {
        $('#log_date').datepicker({
            format: 'dd-mm-yyyy',
            autoclose: true,
        });
    }
");
?>

<?php $this->beginWidget('booster.widgets.TbModal',array('id' => 'tasklog-detail')); ?>
            <div class="modal-header">
                <a class="close" data-dismiss="modal">&times;</a>
                <h4 id="tasklog-detail-header">Modal header</h4>
            </div>
            
            <div class="modal-body" id="tasklog-detail-body"></div>
            
            <div class="modal-footer">
                <?php $this->widget('booster.widgets.TbButton',
                        array(
                            'label' => 'Close',
                            'url' => '#',
                            'htmlOptions' => array('data-dismiss' => 'modal'),
                        )
                ); ?>
            </div>
                
<?php $this->endWidget(); ?>
