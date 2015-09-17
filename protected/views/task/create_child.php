<?php
/* @var $this TaskController */
/* @var $model Task */
$project = Project::model()->find(array('condition'=>'id='.$parent_task->project_id))->name;

$this->widget('booster.widgets.TbBreadcrumbs', array(
    'links'=>array(
        'Projects'=>array('project/index'), 
        $project=>array('project/view', 'id'=>$parent_task->project_id),
        $parent_task->title=>array('task/view', 'id'=>$parent_task->id),
        'New subtask')
));

?>

<h2 class="page-header">Create New Subtask</h2>

<?php echo $this->renderPartial('_form', array('model'=>$model, 'assignment'=>$assignment)); ?>