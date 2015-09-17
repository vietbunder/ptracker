<?php

/**
 * This is the model class for table "t_task_report".
 *
 * The followings are the available columns in table 't_task_report':
 * @property integer $id
 * @property integer $task_assignment_id
 * @property string $start_date
 * @property string $finish_date
 * @property string $description
 * @property string $status
 * @property integer $deleted
 * @property string $timestamp
 *
 * The followings are the available model relations:
 * @property TTaskAssignment $taskAssignment
 */
class TaskReport extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return TaskReport the static model class
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
		return 't_task_report';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('task_assignment_id, start_date, finish_date, status', 'required'),
			array('task_assignment_id, deleted', 'numerical', 'integerOnly'=>true),
			array('status', 'length', 'max'=>64),
			array('description', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, task_assignment_id, start_date, finish_date, description, status, deleted, timestamp', 'safe', 'on'=>'search'),
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
			'taskAssignment' => array(self::BELONGS_TO, 'TaskAssignment', 'task_assignment_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'task_assignment_id' => 'Task Assignment',
			'start_date' => 'Start Date',
			'finish_date' => 'Finish Date',
			'description' => 'Description',
			'status' => 'Status',
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
		$criteria->compare('task_assignment_id',$this->task_assignment_id);
		$criteria->compare('start_date',$this->start_date,true);
		$criteria->compare('finish_date',$this->finish_date,true);
		$criteria->compare('description',$this->description,true);
		$criteria->compare('status',$this->status,true);
		$criteria->compare('deleted',$this->deleted);
		$criteria->compare('timestamp',$this->timestamp,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}