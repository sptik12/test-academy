<?php

namespace common\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "event_log".
 *
 * @property int $id
 * @property string $record_id
 * @property string $table_name
 * @property int $action_id
 * @property string $model_name
 * @property string $title
 * @property int $owner_id
 * @property string $created_at
 */
class EventLog extends BaseActiveRecord
{
	/*
	 * Статусы записей
	 */
	const STATUS_DELETED = 0;
	const STATUS_ACTIVE = 1;

	/*
	 * Статусы действий
	 */
	const INSERT = 1;
	const UPDATE = 2;
	const DELETE = 3;

	public static $actions = [
		self::INSERT => 'Добавление',
		self::UPDATE => 'Редактирование',
		self::DELETE => 'Удаление',
	];

	public static $items = [
		'User' => 'Пользователи',
		'Order' => 'Заявки',
	];

	public static $permissions = [
		'User' => 'users-edit',
		'Order' => 'orders',
	];

	/**
	 * {@inheritdoc}
	 */
	public static function tableName()
	{
		return 'event_log';
	}

	/**
	 * {@inheritdoc}
	 */
	public function behaviors()
	{
		return [
			'TimestampBehavior' => [
				'class' => TimestampBehavior::class,
				'createdAtAttribute' => 'created_at',
				'updatedAtAttribute' => null,
			],
		];
	}

	/**
	 * {@inheritdoc}
	 * @return EventLogQuery the active query used by this AR class.
	 */
	public static function find()
	{
		return new EventLogQuery(get_called_class());
	}

	/**
	 * {@inheritdoc}
	 */
	public function rules()
	{
		return [
			[['action_id', 'owner_id', 'status'], 'integer'],
			[['title'], 'string'],
			[['created_at'], 'safe'],
			[['record_id', 'table_name', 'model_name'], 'string', 'max' => 255],
		];
	}

	/**
	 * @return string
	 */
	public function getActionName()
	{
		return ArrayHelper::getValue(self::$actions, $this->action_id);
	}

	/**
	 * @return string
	 */
	public function getItemName()
	{
		return ArrayHelper::getValue(self::$items, $this->model_name, $this->model_name);
	}

	/**
	 * @return string
	 */
	public function getUserName()
	{
		return ArrayHelper::getValue($this->user, 'username');
	}

	/**
	 * {@inheritdoc}
	 */
	public function attributeLabels()
	{
		return [
			'id' => 'ID',
			'record_id' => Yii::t('app','ID записи'),
			'table_name' => Yii::t('app','Таблица'),
			'action_id' => Yii::t('app','Действие'),
			'model_name' => Yii::t('app','Объект'),
			'title' => Yii::t('app','Наименование'),
			'owner_id' => Yii::t('app','Пользователь'),
			'created_at' => Yii::t('app', 'Дата'),
		];
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getUser()
	{
		return $this->hasOne(User::class, ['id' => 'owner_id']);
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getEventLogChanges()
	{
		return $this->hasMany(EventLogChange::class, ['event_log_id' => 'id']);
	}

	/*
	 * Возвращает путь к изменной записи из лога
	 *
	 * @return string|boolean
	 */
	public function getRecordRoute()
	{
		$res = false;
		if ($this->status == self::STATUS_ACTIVE) {
			switch ($this->model_name) {
				case 'User' :
					$res = ['/user/view', 'id' => $this->record_id];
					break;
				case 'Order' :
					$res = ['/order/view', 'id' => $this->record_id];
					break;
			}
		}
		return $res;
	}

	/**
	 * @inheritdoc
	 */
	public function beforeDelete()
	{
		EventLogChange::deleteAll(['event_log_id' => $this->id]);
		return parent::beforeDelete();
	}

}
