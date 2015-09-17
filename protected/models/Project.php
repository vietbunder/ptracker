<?php

/**
 * This is the model class for table "t_project".
 *
 * The followings are the available columns in table 't_project':
 * @property integer $id
 * @property string $code
 * @property integer $creator_id
 * @property string $name
 * @property string $description
 * @property string $start_date
 * @property string $due_date
 * @property string $status
 * @property integer $deleted
 * @property string $timestamp
 *
 * The followings are the available model relations:
 * @property TAccount $creator
 * @property TTask[] $tTasks
 * @property TTaskAssignment[] $tTaskAssignments
 */
class Project extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Project the static model class
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
		return 't_project';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('code, creator_id, name, description, start_date, due_date, status', 'required'),
			array('creator_id, deleted', 'numerical', 'integerOnly'=>true),
			array('name, status', 'length', 'max'=>64),
                        array('code', 'unique'),
                        array('start_date, due_date', 'date', 'format'=>'yyyy-M-d'),
                        array('start_date', 'compare', 'compareValue'=>date('Y-m-d'), 'operator'=>'>=', 'on'=>'create'),
                        array('due_date', 'compare', 'compareValue'=>date('Y-m-d'), 'operator'=>'>='),
                        array('due_date', 'compare', 'compareAttribute'=>'start_date', 'operator'=>'>='),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, code, creator_id, name, description, start_date, due_date, status, deleted, timestamp', 'safe', 'on'=>'search'),
                        array('id, code, creator_id, name, description, start_date, due_date, status, deleted, timestamp', 'safe', 'on'=>'myprojects'),
                        array('id, code, creator_id, name, description, start_date, due_date, status, deleted, timestamp', 'safe', 'on'=>'export'),
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
			'creator' => array(self::BELONGS_TO, 'Account', 'creator_id'),
			'tTasks' => array(self::HAS_MANY, 'Task', 'project_id'),
			'tTaskAssignments' => array(self::HAS_MANY, 'TaskAssignment', 'project_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'Project ID',
                        'code'=> 'ID',
			'creator_id' => 'Creator',
			'name' => 'Project Name',
			'description' => 'Description',
			'start_date' => 'Start date',
			'due_date' => 'Due date',
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
		$criteria->compare('creator_id',$this->creator_id);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('start_date',$this->start_date,true);
		$criteria->compare('due_date',$this->due_date,true);
                $criteria->compare('status',$this->status);
                $criteria->compare('deleted',0,true);
                
		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
                        'sort'=>array(
                            'defaultOrder'=>'code ASC'),
		));
	}
        
        public function myprojects()
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
                $criteria->compare('code',$this->code,true);
		$criteria->compare('creator_id',Yii::app()->user->id);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('start_date',$this->start_date,true);
		$criteria->compare('due_date',$this->due_date,true);
                $criteria->compare('status',$this->status);
                $criteria->compare('deleted',0,true);
                //$criteria->condition = 'creator_id='.Yii::app()->user->id.' AND deleted=0';

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
                        'sort'=>array('defaultOrder'=>'code ASC'),
		));
	}
}