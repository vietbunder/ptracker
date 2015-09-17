<?php 
if(!Yii::app()->user->checkAccess('Staff')) {
    $project_owner = Member::model()->find(array('condition'=>'account_id='.$model->creator->id));
    $this->widget('booster.widgets.TbDetailView', array(
            'id'=>'event-detail',
            'data'=>$model,
            'attributes'=>array(
                    'code',
                    array(
                        'label'=>'Project Owner',
                        'value'=>$project_owner->name,
                    ),
                    array(
                        'label'=>'Department',
                        'value'=>$project_owner->department,
                    ),
                    'description',
                    'status',
                    'start_date',
                    'due_date',
            ),
    )); 
}
else {
    $project_manager = Account::model()->findByPk($model->creator_id);
    $this->widget('booster.widgets.TbDetailView', array(
            'id'=>'event-detail',
            'data'=>$model,
            'attributes'=>array(
                'code',
                array(
                        'label'=>'Project',
                        'value'=>$model->project->name,
                    ),
                array(
                        'label'=>'Manager',
                        'value'=>Member::model()->find(array('condition'=>'account_id='.$project_manager->id))->name,
                    ),
                array(
                            'label'=>'Parent Task',
                            'value'=>$model->has_parent ? $model->parent->title : CHtml::encode('-'),
                        ),
                'start_date',
                'due_date',
                'status'
            ),
    ));
}
?>