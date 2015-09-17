<?php
/* @var $this SiteController */
$this->widget('booster.widgets.TbBreadcrumbs', array(
    'links'=>array('Dashboard')
));

// Retrieve user task info
$my_tasks = TaskAssignment::model()->with('task')->findAll(array('condition'=>'t.deleted=0 AND task.status != "Complete" AND t.member_id='.Yii::app()->user->id, 
    'order'=>'t.timestamp DESC', 'limit'=>4));


// Retrieve all projects info
$all_upcomingprojects = Project::model()->count(array('condition'=>'status="Upcoming" AND deleted=0'));
$all_activeprojects = Project::model()->count(array('condition'=>'status="Active" AND deleted=0'));
$all_completedprojects = Project::model()->count(array('condition'=>'status="Complete" AND deleted=0'));
$all_expiredprojects = Project::model()->count(array('condition'=>'status="Expired" AND deleted=0'));

// Retrieve last activity
$recentlogs = TaskLog::model()->findAll(array('order'=>'timestamp DESC', 'limit'=>3, 'condition'=>'deleted=0 AND type="Project"'));
$recentprojects = Project::model()->findAll(array('order'=>'timestamp DESC', 'limit'=>3, 'condition'=>'deleted=0'));
$recentassignments = TaskAssignment::model()->findAll(array('order'=>'timestamp DESC', 'limit'=>3, 'condition'=>'deleted=0'));
//$recentcompletions = SubtaskCompletion::model()->findAll(array('order'=>'timestamp DESC', 'limit'=>3, 'condition'=>'deleted=0'));
?>

<!-- HEADER -->
<h2 class="page-header">Dashboard</h2>

<!-- TASK PANEL -->
<div class="span-6">
    <!-- MY TASKS -->
    <div class="panel panel-primary">
        <!-- heading -->
        <div class="panel-heading">
            <span class="glyphicon glyphicon-folder-open"></span>
            <span><strong>&nbsp; My tasks</strong></span>
        </div>
        
        <!-- list group -->
        <ul class="list-group">
            <?php
                if(!$my_tasks) {
            ?>
            <li class="list-group-item">
                <i>No task yet.</i>
            </li>
            <?php
                }
                else {
                    foreach($my_tasks as $task) {
            ?>
            <li class="list-group-item">
                <strong><?php echo $task->task->project->name; ?></strong>
                - 
                <a href="<?php echo Yii::app()->createUrl('task/view', array('id'=>$task->task_id)); ?>"><?php echo $task->task->title; ?></a><br>
                <div style="text-align: right; font-style: italic; font-size: 10px;">
                    Deadline : <?php echo $task->task->due_date <= date('Y-m-d') ? '<font color="red">'.$task->task->due_date.'</font>' : $task->task->due_date; ?>
                </div>
            </li>
            <?php
                    }
                }
            ?>
        </ul>
        
        <!-- footer -->
        <a href="<?php echo Yii::app()->createUrl('task/mytask'); ?>">
        <div class="panel-footer">
            See all of my tasks
            <span class="pull-right"><i class="glyphicon glyphicon-circle-arrow-right"></i></span>
        </div>
        </a>
    </div>
    <hr>
    <!-- OTHER PROJECTS -->
    <!-- <div class="panel panel-success">
        <div class="panel-heading">
            <span class="glyphicon glyphicon-folder-close"></span>
            <span><strong>&nbsp; Projects</strong></span>
        </div>
        <ul class="list-group">
            <li class="list-group-item">Upcoming
                <?php $this->widget('booster.widgets.TbBadge',array('context' => 'default','label' => $all_upcomingprojects));?>
            </li>
            <li class="list-group-item">Active
                <?php $this->widget('booster.widgets.TbBadge',array('context' => 'info','label' => $all_activeprojects));?>
            </li>
            <li class="list-group-item">Completed
                <?php $this->widget('booster.widgets.TbBadge',array('context' => 'success','label' => $all_completedprojects));?>
            </li>
            <li class="list-group-item">Expired
                <?php $this->widget('booster.widgets.TbBadge',array('context' => 'danger','label' => $all_expiredprojects));?>
            </li>
        </ul>
        <a href="<?php echo Yii::app()->createUrl('project/index'); ?>">
        <div class="panel-footer">
            See all projects
            <span class="pull-right"><i class="glyphicon glyphicon-circle-arrow-right"></i></span>
        </div>
        </a>
    </div> -->
</div>

<!-- PROJECT CALENDAR -->
<div class="span-13">
    <div class="panel panel-default">
        <div class="panel-heading">
            <span class="glyphicon glyphicon-calendar"></span>
            <span><strong>&nbsp; Project calendar</strong></span>
        </div>
        <div class="panel-body">
            <?php $this->widget('ext.fullcalendar.EFullCalendarHeart', array(
                //'themeCssFile'=>'cupertino/jquery-ui.min.css',
                'options'=>array(
                    'header'=>array(
                        'left'=>'prev,next,today',
                        'center'=>'',
                        'right'=>'title',
                    ),
                    'eventLimit'=>true,
                    'editable'=>false,
                    'events'=>$this->createUrl('site/calendarEventsStaff'), // URL to get event
                    'eventClick'=> 'js:function(calEvent, jsEvent, view) {
                        $("#myModalHeader").html(calEvent.title);
                        $("#myModalBody").load("'.Yii::app()->createUrl('site/EventDetail').'/"+calEvent.idevent+"?asModal=true");
                        $("#myModal").modal();
                    }',
                )));
            ?>
            
            <?php $this->beginWidget('booster.widgets.TbModal',array('id' => 'myModal')); ?>
            <div class="modal-header">
                <a class="close" data-dismiss="modal">&times;</a>
                <h4 id="myModalHeader">Modal header</h4>
            </div>
            
            <div class="modal-body" id="myModalBody"></div>
            
            <div class="modal-footer">
                <?php $this->widget('booster.widgets.TbButton',
                        array(
                            'label' => 'Close',
                            'url' => '#',
                            'htmlOptions' => array('data-dismiss' => 'modal'),
                        )
                ); ?>
            </div>

            <?php $this->endWidget(); ?>
        </div>
    </div>
</div>

<!-- RECENT ACTIVITIES PANEL -->
<div class="span-6 pull-right" style="margin-right: 0px; width: 268px;">
    <div class="panel panel-warning">
        <div class="panel-heading">
            <span class="glyphicon glyphicon-pushpin"></span>
            <span><strong>&nbsp; Recent activities</strong></span>
        </div>
        
        <ul class="list-group">
            <!-- project related activity -->
            <?php
                foreach($recentprojects as $recent) {
            ?>
            <li class="list-group-item">
                <strong><?php echo $recent->creator->username; ?></strong>
                just created a project<br>
                <div style="text-align: right; font-style: italic; font-size: 10px;"><?php echo $recent->timestamp; ?></div>
            </li>
            <?php
                }
            ?>
            
            <!-- assignment related activity -->
            <?php
                foreach($recentassignments as $recent) {
            ?>
            <li class="list-group-item">
                <strong><?php echo $recent->member->username; ?></strong>
                just assigned to task : <?php echo $recent->task->title; ?><br>
                <div style="text-align: right; font-style: italic; font-size: 10px;"><?php echo $recent->timestamp; ?></div>
            </li>
            <?php
                }
            ?>
            
            <!-- log related activity -->
            <?php
                foreach($recentlogs as $recent) {
            ?>
            <li class="list-group-item">
                <strong><?php echo $recent->member->username; ?></strong>
                just create log at task : <?php echo $recent->taskAssignment->task->title; ?><br>
                <div style="text-align: right; font-style: italic; font-size: 10px;"><?php echo $recent->timestamp; ?></div>
            </li>
            <?php
                }
            ?>
        </ul>
    </div>
</div>
