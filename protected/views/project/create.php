<?php
/* @var $this ProjectController */
/* @var $model Project */

$this->widget('booster.widgets.TbBreadcrumbs', array(
    'links'=>array(
        'Projects'=>array('project/index'), 
        'New Project')
));

$this->menu=array(
	array('label'=>'See my projects', 'url'=>array('project/myProjects')),
        array('label'=>'See all projects', 'url'=>array('project/index')),
);
?>

<h2 class="page-header">Add new Project</h2>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>