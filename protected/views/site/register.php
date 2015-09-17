<?php 
    $this->pageTitle = Yii::app()->name . ' - Register'; 
    $this->widget('booster.widgets.TbBreadcrumbs', array(
        'links'=>array('Manage account'=>array('site/member'), 'Register')
    ));
?>

<h2 class="page-header">Register new account</h2>
<p>Please fill out the following form to register:</p> 

<?php echo $this->renderPartial('_form_member', array('model'=>$model)); ?>
