<?php
/* @var $this ProjectController */
/* @var $dataProvider CActiveDataProvider */
//$task = Task::model()->count(array('condition'=>'project_id='.$model->id));
//$projectworker = TaskAssignment::model()->count(array('condition'=>'project_id='.$model->id));
$this->widget('booster.widgets.TbBreadcrumbs', array(
    'links'=>array('My project(s)')
));

$this->menu=array(
	array('label'=>'Add new project', 'url'=>array('project/create')),
	array('label'=>'All projects', 'url'=>array('project/index')),
);
?>

<h2 class="page-header">Projects</h2>  
<?php $this->widget('booster.widgets.TbGridView',
    array(
        'id' => 'project-myproject',
        'type'=>'striped bordered condensed hover',
        'dataProvider' => $model->myprojects(),
        'filter' => $model,
        'columns' => array(
            array(
                    'header' => 'No',
                    'value' => '$this->grid->dataProvider->pagination->currentPage*$this->grid->dataProvider->pagination->pageSize + $row+1',
                ),
            'code',
            'name',
            array(
                    'header' => 'Status',
                    'name'=>'status',
                    'type'=>'raw',
                    'value' => '$data->status=="Upcoming" ? Yii::app()->controller->widget(\'booster.widgets.TbLabel\', array(\'context\' => \'primary\',\'label\' => \'Upcoming\'), true) : '
                                . '($data->status=="Active" ? Yii::app()->controller->widget(\'booster.widgets.TbLabel\', array(\'context\' => \'success\',\'label\' => \'Active\'), true) : '
                                . 'Yii::app()->controller->widget(\'booster.widgets.TbLabel\', array(\'context\' => \'danger\',\'label\' => \'Expired\'), true))',
                    'filter'=>array(''=>'All', 'Upcoming'=>'Upcoming', 'Active'=>'Active', 'Expired'=>'Expired'),
                    'htmlOptions'=>array('width'=>100),
                ),
            array(
                    'header' => 'Progress',
                    'type' => 'raw',
                    'value' => 'Task::model()->count(array(\'condition\'=>\'project_id=\'.$data->id.\' AND has_parent=0 AND deleted=0\')) == 0 ? "0.00 %" : CHtml::encode(sprintf("%.2f", Task::model()->count(array(\'condition\'=>\'project_id=\'.$data->id.\' AND has_parent=0 AND deleted=0 AND status="Complete"\'))/Task::model()->count(array(\'condition\'=>\'project_id=\'.$data->id.\' AND has_parent=0 AND deleted=0\'))*100))." %"',
                    //'htmlOptions'=>array('width'=>160),
                ),
            array(
                        'header'=>'Options',
                        'headerHtmlOptions'=>array(
                                    'style' => 'text-align: left;',
                                ),
			'class'=>'booster.widgets.TbButtonColumn',
                        'htmlOptions'=>array(
                            'width'=>60,
                        )
		),
        )
    )
);
?>