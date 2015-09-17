<?php
/* @var $this SiteController */

$this->pageTitle=Yii::app()->name;
?>

<?php $this->beginWidget(
    'booster.widgets.TbJumbotron',
    array(
        'heading' => 'Project Tracker Application',
    )
); ?>
 
    <p>
        This is a web-based application to handle projects under Wilmar Group Plantation.
    </p>
    <p>
        This application is used by project managers and staff. 
        For those who have not been enrolled into this application can contact the administrator.
    </p>
    
 
    <p><?php $this->widget(
            'booster.widgets.TbButton',
            array(
                'context' => 'primary',
                'size' => 'large',
                'label' => 'Learn more',
            )
        ); ?></p>
 
<?php $this->endWidget(); ?>