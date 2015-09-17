<?php
/* @var $this SiteController */
/* @var $dataProvider CActiveDataProvider */
$this->pageTitle = Yii::app()->name . ' - Account'; 
$this->widget('booster.widgets.TbBreadcrumbs', array(
    'links'=>array('Manage account')
));

$this->menu=array(
	array('label'=>'Register new account', 'url'=>array('site/register')),
);
?>

<h2 class="page-header">Manage account</h2>
<div class="span-24">
<?php $this->widget('booster.widgets.TbGridView',
    array(
        'id' => 'manage-account',
        'type'=>'striped condensed hover',
        'dataProvider' => $model->search(),
	'filter'=>$model,
        'columns' => array(
            array(
                    'header' => 'No',
                    'value' => '$this->grid->dataProvider->pagination->currentPage*$this->grid->dataProvider->pagination->pageSize + $row+1',
                ),
            'name',
            'account.username',
            'department',
            'role',
            'last_login',
            array(
			'class'=>'booster.widgets.TbButtonColumn',
                        'header'=>'Options',
                        'template'=>'{update} {delete}',
                        'headerHtmlOptions'=>array(
                            'style' => 'text-align: left;',
                        ),
                        'htmlOptions'=>array(
                            'width'=>100,
                            'style' => 'align:left',
                        ),
                        'buttons'=>array(
                            'update'=>array(
                                'url'=>'Yii::app()->createUrl("site/updateMember", array("id"=>$data->id))',
                            ),
                            'delete'=>array(
                                'url'=>'Yii::app()->createUrl("site/deleteMember", array("id"=>$data->id))',
                            ),
                        )
		),
        )
    )
);
?>
</div>
