<?php
/* @var $this ProjectController */
/* @var $model Project */

$this->widget('booster.widgets.TbBreadcrumbs', array(
    'links'=>array(
        'Projects'=>array('project/index'),
        $model->name=>array('project/view', 'id'=>$model->id),
        'Update')
));

$this->menu=array(
	array('label'=>'See my projects', 'url'=>array('project/myProjects')),
        array('label'=>'See all projects', 'url'=>array('project/index')),
);
?>

<h2 class="page-header">Update Project - <?php echo $model->name; ?></h2>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>