<?php echo 'Test Calendar by: Yoel Simanjuntak'; ?>

<div class="span-8">
    <center>
    <?php $this->widget('ext.fullcalendar.EFullCalendarHeart', array(
        //'themeCssFile'=>'cupertino/jquery-ui.min.css',
        'options'=>array(
            'header'=>array(
                'left'=>'prev,next,today',
                'center'=>'title',
                //'right'=>'month,agendaWeek,agendaDay',
            ),
            'eventLimit'=>true,
            'editable'=>false,
            'events'=>$this->createUrl('site/calendarEvents'), // URL to get event
        )));
    ?>
    </center>
</div>