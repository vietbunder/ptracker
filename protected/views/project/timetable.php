<html>
<head>
    <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/jsgantt.css"/>
    <script language="javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/css/jsgantt.js"></script>
</head>

<body>
<h2 class="page-header"><?php echo $project->name; ?> - Timetable</h2><hr>
<div style="position:relative" class="gantt" id="GanttChartDIV"></div>
<script>

  var g = new JSGantt.GanttChart('g',document.getElementById('GanttChartDIV'), 'day');
  g.setShowRes(1); // Show/Hide Responsible (0/1)
  g.setShowDur(1); // Show/Hide Duration (0/1)
  g.setShowComp(0); // Show/Hide % Complete(0/1)
  g.setCaptionType('Resource');  // Set to Show Caption

  if( g ) {
      <?php
      print_task($tasks);
      
      function print_task($tasks) {
          $id = 1;
          foreach($tasks as $task) {
          $childcount = Task::model()->count(array('condition'=>'parent_id='.$task->id.' AND deleted=0'));
          $assignment = TaskAssignment::model()->find(array('condition'=>'task_id='.$task->id.' AND deleted=0'));
          if($childcount > 0) {
              $childtasks = Task::model()->findAll(array('condition'=>'parent_id='.$task->id.' AND deleted=0'));
              echo 'g.AddTaskItem(new JSGantt.TaskItem("'.$task->id.'", "'.$task->title.'", "'.date('m/d/Y', strtotime($task->start_date)).'", "'.date('m/d/Y', strtotime($task->due_date)).'", "659af7", "#", 0, "'.(isset($assignment) ? $assignment->member->username : '').'", 0, 1, '.($task->has_parent ? $task->parent_id : 0).', 0));';
              print_task($childtasks);
          }
          else {
              echo 'g.AddTaskItem(new JSGantt.TaskItem("'.$task->id.'", "'.$task->title.'", "'.date('m/d/Y', strtotime($task->start_date)).'", "'.date('m/d/Y', strtotime($task->due_date)).'", "659af7", "#", 0, "'.(isset($assignment) ? $assignment->member->username : '').'", 0, 0, '.($task->has_parent ? $task->parent_id : 0).', 0));';
              
              // set criteria for tasklog
              $criteria = new CDbCriteria;
              $criteria->with = array('taskAssignment.task');
              $criteria->order = 'date ASC';
              $criteria->condition = 'type="Project" AND task.id='.$task->id.' AND t.deleted=0 and task.deleted=0';
              
              $criteria2 = new CDbCriteria;
              $criteria2->with = array('taskAssignment.task');
              $criteria2->condition = 'type="Non-project" AND t.member_id='.$assignment->member_id.' AND t.deleted=0 AND t.date BETWEEN "'.$task->start_date.'" AND "'.$task->due_date.'"';
              
              $criteria3 = new CDbCriteria;
              $criteria3->with = array('taskAssignment.task');
              $criteria3->condition = 'type="Project" AND t.member_id='.$assignment->member_id.' AND t.deleted=0 AND t.date BETWEEN "'.$task->start_date.'" AND "'.$task->due_date.'"';
              
              // Merge criteria
              $criteria->mergeWith($criteria2, 'OR');
              $criteria->mergeWith($criteria3, 'OR');
              
              // Get tasklog based on criteria
              $tasklog = TaskLog::model()->findAll($criteria);
              
              if($tasklog) {
                echo 'g.AddTaskItem(new JSGantt.TaskItem("'.$task->id.'.1", "'.$task->title.' - REAL", "", "", "fe7e7e", "#", 0, "-", 0, 1, '.($task->has_parent ? $task->parent_id : 0).', 0));';
                foreach($tasklog as $item) {
                    if($item->type=="Non-project") {
                        echo 'g.AddTaskItem(new JSGantt.TaskItem("'.$task->id.'.'.$item->id.'", "'.$item->date.' - '.$item->task_title.' (Non-project)", "'.date('m/d/Y', strtotime($item->date)).'", "'.date('m/d/Y', strtotime($item->date)).'", "737070", "#", 0, "'.$item->member->username.'", 0, 0, "'.$task->id.'.1", "'.$task->title.'-REAL", 0));';
                    }
                    else if($item->type=="Project" && $item->taskAssignment->task->id !=$task->id) {
                        echo 'g.AddTaskItem(new JSGantt.TaskItem("'.$task->id.'.'.$item->id.'", "'.$item->date.' - '.$item->taskAssignment->task->title.' (Different project)", "'.date('m/d/Y', strtotime($item->date)).'", "'.date('m/d/Y', strtotime($item->date)).'", "737070", "#", 0, "'.$item->member->username.'", 0, 0, "'.$task->id.'.1", "'.$task->title.'-REAL", 0));';
                    }
                    else {
                        echo 'g.AddTaskItem(new JSGantt.TaskItem("'.$task->id.'.'.$item->id.'", "'.$item->date.'", "'.date('m/d/Y', strtotime($item->date)).'", "'.date('m/d/Y', strtotime($item->date)).'", "fe7e7e", "#", 0, "'.$item->member->username.'", 0, 0, "'.$task->id.'.1", "'.$task->title.'-REAL", 0));';
                    }
                }
              }
          }
      }
      }
      ?>
    g.Draw();	
    g.DrawDependencies();


  }
  else
  {
    alert("not defined");
  }

</script>
</html>