<?php
/* @var $this TaskController */
/* @var $model Task */
$project = Project::model()->find(array('condition'=>'id='.$id))->name;

$this->widget('booster.widgets.TbBreadcrumbs', array(
    'links'=>array(
        'Projects'=>array('project/index'), 
        $project=>array('project/view', 'id'=>$id),
        'New task')
));

?>

<h2 class="page-header">Create New Task</h2>

<?php echo $this->renderPartial('_form', array('model'=>$model, 'assignment'=>$assignment)); ?>