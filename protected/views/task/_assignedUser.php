<?php $this->widget('booster.widgets.TbGridView',
    array(
        'id' => 'assigneduser-list',
        'dataProvider' => $assignedUser,
        'columns' => array(
            array(
                    'header' => 'No',
                    'value' => '$this->grid->dataProvider->pagination->currentPage*$this->grid->dataProvider->pagination->pageSize + $row+1',
                ),
            array(
                    'header' => 'No',
                    'value' => '$data->member->username'
                ),
            
        )
    )
);
?>

