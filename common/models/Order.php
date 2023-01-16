<?php

namespace common\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\helpers\ArrayHelper;
use yii\helpers\FileHelper;
use common\helpers\Html;

/**
 * This is the model class for table "order".
 *
 * @property integer $id
 * @property string $title
 * @property string $first_name
 * @property string $last_name
 * @property string $phone
 * @property string $comment
 * @property int $category_id
 * @property float $price
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 */
class Order extends BaseActiveRecord
{
	/*
	 * Order statuses
	 */
	const ORDER_STATUS_CONSIDERED = 0;
	const ORDER_STATUS_ACCEPTED = 1;
	const ORDER_STATUS_DECLINED = 2;

	const FULL_NAME_TEMPLATE = '{last_name} {first_name}';

	/**
	 * {@inheritdoc}
	 */
	public static function tableName()
	{
		return 'order';
	}

	/**
	 * {@inheritdoc}
	 * @return OrderQuery the active query used by this AR class.
	 */
	public static function find()
	{
		return new OrderQuery(get_called_class());
	}

	/**
	 * {@inheritdoc}
	 */
	public function rules()
	{
		return [
			[['title', 'first_name', 'last_name', 'phone', 'category_id', 'price'], 'required', 'on'=> ['create', 'update']],
			[['title', 'first_name', 'last_name', 'phone'], 'filter', 'filter' => 'trim', 'on'=> ['create', 'update']],
			[['first_name', 'last_name'], 'string', 'max' => 64],
			[['title'], 'string', 'max' => 256],
			[['phone'], 'string', 'max' => 32],
			[['comment', 'phone'], 'safe'],
			[['price'], 'number', 'min' => 1, 'tooSmall' => Yii::t('app', 'Недопустимая цена')],
			[['status'], 'in', 'range' => [self::ORDER_STATUS_CONSIDERED, self::ORDER_STATUS_ACCEPTED, self::ORDER_STATUS_DECLINED],
				'on' => ['create', 'update', 'change-status']],
		];
	}

	/**
	 * {@inheritdoc}
	 */
	public function behaviors()
	{
		return [
			TimestampBehavior::class,
		];
	}

	/**
	 * {@inheritdoc}
	 */
	public function attributeLabels()
	{
		return [
			'id' => Yii::t('app', 'ID'),
			'title' => Yii::t('app', 'Заголовок'),
			'first_name' => Yii::t('app', 'Имя'),
			'last_name' => Yii::t('app', 'Фамилия'),
			'fullName' => Yii::t('app','Имя'),
			'full_name' => Yii::t('app','Имя'),
			'phone' => Yii::t('app','Телефон'),
			'comment' => Yii::t('app','Комментарий'),
			'status' => Yii::t('app','Статус'),
			'price' => Yii::t('app','Цена'),
			'category_id' => Yii::t('app','Товар'),
			'created_at' => Yii::t('app', 'Дата создания'),
			'updated_at' => Yii::t('app', 'Дата изменения'),
		];
	}

	/**
	 * Get Category
	 * @return \yii\db\ActiveQuery
	 */
	public function getCategory()
	{
		return $this->hasOne(Category::class, ['id' => 'category_id']);
	}

	/**
	 * @inheritdoc
	 */
	public function beforeDelete()
	{
		$this->logDelete($this->category->name);
		return parent::beforeDelete();
	}

	/**
	 * @inheritdoc
	 */
	public function afterSave($insert, $changedAttributes)
	{
		$this->logSave($this->category->name, $insert, $changedAttributes);
		parent::afterSave($insert, $changedAttributes);
	}

	/*
	 * Полное имя заказчика
	 *
 	 * @return string
	 */
	public function getFullName()
	{
		return trim(Yii::t('app', self::FULL_NAME_TEMPLATE, ['first_name' => $this->first_name, 'last_name' => $this->last_name]));
	}

	/*
	 *  Список возможных статусов
	 *  @return array
	 */
	public static function getStatusOptions()
	{
		return [
			self::ORDER_STATUS_CONSIDERED => Yii::t('app', 'Создана'),
			self::ORDER_STATUS_ACCEPTED => Yii::t('app', 'Принята'),
			self::ORDER_STATUS_DECLINED => Yii::t('app', 'Отклонена'),
		];
	}

	/*
	 *
	 *  @return string
	 */
	public function getStatusName()
	{
		return self::getStatusOptions()[$this->status];
	}

}
