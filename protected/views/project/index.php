<?php
/* @var $this ProjectController */
/* @var $dataProvider CActiveDataProvider */

$this->widget('booster.widgets.TbBreadcrumbs', array(
    'links'=>array('Project(s)')
));

$this->menu=array(
	array('label'=>'Add new project', 'url'=>array('project/create')),
);

// Get active projects
$expiredprojects = Project::model()->count(array('condition'=>'status="Expired" AND deleted=0'));
$activeprojects = Project::model()->count(array('condition'=>'status="Active" AND deleted=0'));
$upcomingprojects = Project::model()->count(array('condition'=>'status="Upcoming" AND deleted=0'))
?>

<h2 class="page-header">Projects</h2>

<!--<div class="span-19" style="margin: 0px; padding: 0px;">
<table class="span-4" style="margin: 0px; padding: 0px;">
    <tr>
        <td class="span-4" style="margin: 0px; padding: 0px;"">
            <h4><?php $this->widget('booster.widgets.TbLabel',array('context' => 'danger','label' => 'Expired projects :'));?></h4>
            <h4><?php $this->widget('booster.widgets.TbLabel',array('context' => 'success','label' => 'Active projects :'));?></h4>
            <h4><?php $this->widget('booster.widgets.TbLabel',array('context' => 'primary','label' => 'Upcoming projects :'));?></h4>
        </td>
        <td class="span-4" style="margin: 0px; padding: 0px;"">
            <h4><?php $this->widget('booster.widgets.TbLabel',array('context' => 'danger','label' => $expiredprojects));?></h4>
            <h4><?php $this->widget('booster.widgets.TbLabel',array('context' => 'success','label' => $activeprojects));?></h4>
            <h4><?php $this->widget('booster.widgets.TbLabel',array('context' => 'primary','label' => $upcomingprojects));?></h4>
        </td>
    </tr>
</table>
</div>-->

<div class="span-24">
<?php $this->widget('booster.widgets.TbGridView',
    array(
        'id' => 'project-index',
        'type'=>'striped bordered condensed hover',
        'dataProvider' => $model->search(),
        'filter'=> $model,
        'columns' => array(
            array(
                    'header' => 'No',
                    'value' => '$this->grid->dataProvider->pagination->currentPage*$this->grid->dataProvider->pagination->pageSize + $row+1',
                ),
            array(
                    'header' => 'ID',
                    'name'=>'code',
                    'value' => '$data->code',
                ),
            'name',
            array(
                    'header'=>'Project Owner',
                    'value'=> 'Member::model()->find(array("condition"=>"account_id=$data->creator_id"))->name',
                    'htmlOptions'=>array('width'=>165),
                ),
            array(
                    'header'=>'Department',
                    'value'=> 'Member::model()->find(array("condition"=>"account_id=$data->creator_id"))->department',
                    'htmlOptions'=>array('width'=>100),
                ),
            array(
                    'header' => 'Status',
                    'name' => 'status',
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
                    'htmlOptions'=>array('width'=>60),
            ),
            array(
                        'header'=>'Options',
			'class'=>'booster.widgets.TbButtonColumn',
                        'headerHtmlOptions'=>array(
                            'style'=>'text-align:left;'
                        ),
                        'htmlOptions'=>array(
                            'width'=>40,
                        )
		),
        )
    )
);
?>
</div>
