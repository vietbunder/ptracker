<?php

/**
 * This is the model class for table "t_task".
 *
 * The followings are the available columns in table 't_task':
 * @property integer $id
 * @property string $code
 * @property integer $project_id
 * @property integer $creator_id
 * @property integer $has_parent
 * @property integer $parent_id
 * @property string $title
 * @property string $start_date
 * @property string $due_date
 * @property string $status
 * @property integer $deleted
 * @property string $timestamp
 *
 * The followings are the available model relations:
 * @property Task $parent
 * @property Task[] $tTasks
 * @property TAccount $creator
 * @property TProject $project
 * @property TTaskAssignment[] $tTaskAssignments
 */
class Task extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Task the static model class
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
		return 't_task';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('code, project_id, creator_id, title, start_date, due_date', 'required'),
			array('project_id, creator_id, parent_id, deleted', 'numerical', 'integerOnly'=>true),
			array('title, status', 'length', 'max'=>64),
                        array('code', 'unique'),
                        array('start_date, due_date', 'date', 'format'=>'yyyy-M-d'),
                        array('start_date', 'compare', 'compareValue'=>date('Y-m-d'), 'operator'=>'>=', 'on'=>'create'),
                        array('due_date', 'compare', 'compareValue'=>date('Y-m-d'), 'operator'=>'>=', 'on'=>'create'),
                        //array('due_date', 'compare', 'compareValue'=>date('Y-m-d'), 'operator'=>'>=', 'on'=>'update'),
                        array('due_date', 'compare', 'compareAttribute'=>'start_date', 'operator'=>'>=', 'on'=>'create'),
                        array('due_date', 'compare', 'compareAttribute'=>'start_date', 'operator'=>'>=', 'on'=>'update'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
                        //array('id, code, project_id, creator_id, parent_id, title, start_date, due_date, status, deleted, timestamp', 'safe', 'on'=>'search'),
			array('id, code, project_id, creator_id, has_parent, parent_id, title, start_date, due_date, status, deleted, timestamp', 'safe', 'on'=>'search'),
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
			'parent' => array(self::BELONGS_TO, 'Task', 'parent_id'),
			'tasks' => array(self::HAS_MANY, 'Task', 'parent_id'),
			'creator' => array(self::BELONGS_TO, 'Account', 'creator_id'),
			'project' => array(self::BELONGS_TO, 'Project', 'project_id'),
			'tTaskAssignments' => array(self::HAS_MANY, 'TaskAssignment', 'task_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
                        'code' => 'ID',
			'project_id' => 'Project',
			'creator_id' => 'Creator',
                        'has_parent' => 'Has Parent',
			'parent_id' => 'Parent',
			'title' => 'Title',
			'start_date' => 'Start Date',
			'due_date' => 'Due Date',
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
                $criteria->compare('code',$this->code,true);
                $criteria->compare('has_parent',$this->has_parent);
		$criteria->compare('project_id',$this->project_id);
		$criteria->compare('creator_id',$this->creator_id);
		$criteria->compare('parent_id',$this->parent_id);
		$criteria->compare('title',$this->title,true);
		$criteria->compare('start_date',$this->start_date,true);
		$criteria->compare('due_date',$this->due_date,true);
		$criteria->compare('status',$this->status);
		$criteria->compare('deleted',0);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
                        'sort'=>array('defaultOrder'=>'code ASC',),
		));
	}
        
        public function getConcatened()
	{
		return $this->code.' - '.$this->title;
	}
}