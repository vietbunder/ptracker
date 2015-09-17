<?php
/* @var $this SiteController */
/* @var $error array */

$this->pageTitle=Yii::app()->name . ' - Error';
$this->widget('booster.widgets.TbBreadcrumbs', array(
    'links'=>array('Error')
));
?>

<h4 class="page-header"><b>Error!</b></h4>
<div class="error">
<?php echo CHtml::encode($message); ?>
</div>