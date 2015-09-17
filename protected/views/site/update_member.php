<?php 
    $this->pageTitle = Yii::app()->name . ' - Register'; 
    $this->widget('booster.widgets.TbBreadcrumbs', array(
        'links'=>array('Manage account'=>array('site/member'), 'Update')
    ));
?>

<h2 class="page-header">Update an account</h2>
<p>You can update account by filling this following form:</p> 

<?php echo $this->renderPartial('_form_member', array('model'=>$model)); ?>
