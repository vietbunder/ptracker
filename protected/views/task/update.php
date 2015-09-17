<?php
/* @var $this TaskController */
/* @var $model Task */

$this->widget('booster.widgets.TbBreadcrumbs', array(
    'links'=>array('Projects'=>array('project/index'), 
                    $model->project->name=>array('project/view', 'id'=>$model->project_id),
                    $model->title=>array('task/view', 'id'=>$model->id),
                    'Update'
        ),
));
?>

<h2 class="page-header">Update Task : <?php echo $model->title; ?></h2>

<?php echo $this->renderPartial('_form', array('model'=>$model, 'assignment'=>$assignment)); ?>