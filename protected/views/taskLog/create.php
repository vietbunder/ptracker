<?php
/* @var $this TaskLogController */
/* @var $model TaskLog */

$this->widget('booster.widgets.TbBreadcrumbs', array(
    'links'=>array(
        'Log activity'=>array('tasklog/index'),
        'Create'
    ),
));
?>

<h2 class="page-header">Create Log activity</h2>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>