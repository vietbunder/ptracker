<?php

/**
 * This is the model class for table "t_task_assignment".
 *
 * The followings are the available columns in table 't_task_assignment':
 * @property integer $id
 * @property integer $task_id
 * @property integer $member_id
 * @property integer $deleted
 * @property string $timestamp
 *
 * The followings are the available model relations:
 * @property TAccount $member
 * @property TTask $task
 * @property TTaskReport[] $tTaskReports
 */
class TaskAssignment extends CActiveRecord
{
        public $search_code;
        public $search_project;
        public $search_task;
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return TaskAssignment the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 't_task_assignment';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('task_id, member_id, deleted', 'required'),
			array('task_id, member_id, deleted', 'numerical', 'integerOnly'=>true),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, task_id, member_id, deleted, timestamp', 'safe', 'on'=>'search'),
                        array('id, search_code, search_project, search_task, task_id, member_id, deleted, timestamp', 'safe', 'on'=>'my'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'member' => array(self::BELONGS_TO, 'Account', 'member_id'),
			'task' => array(self::BELONGS_TO, 'Task', 'task_id'),
			'taskReports' => array(self::HAS_MANY, 'TaskReport', 'task_assignment_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'task_id' => 'Task',
			'member_id' => 'Staff',
			'deleted' => 'Deleted',
			'timestamp' => 'Timestamp',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search()
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		$criteria->compare('task_id',$this->task_id);
		$criteria->compare('member_id',$this->member_id);
		$criteria->compare('deleted',$this->deleted);
		$criteria->compare('timestamp',$this->timestamp,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
        
        public function my()
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		$criteria=new CDbCriteria;
                $criteria->with=array('task', 'task.project');
		$criteria->compare('id',$this->id);
		$criteria->compare('task_id',$this->task_id);
		$criteria->compare('member_id',Yii::app()->user->id);
                $criteria->compare('task.code',$this->search_code,true);
                $criteria->compare('task.title',$this->search_task,true);
                $criteria->compare('project.name',$this->search_project,true);
                $criteria->compare('task.status','Not complete');
                $criteria->compare('task.deleted',0);
		$criteria->compare('t.deleted',0);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}