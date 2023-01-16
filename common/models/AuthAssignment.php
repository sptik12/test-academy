<?php

namespace common\models;

use Yii;
use yii\helpers\Html;

/**
 * This is the model class for table "auth_assignment".
 *
 * @property integer $id
 * @property string $item_name
 * @property string $user_id
 * @property integer $created_at
 *
 * @property AuthItem $itemName
 */
class AuthAssignment extends BaseActiveRecord
{
	/**
	 * @inheritdoc
	 */
	public static function tableName()
	{
		return 'auth_assignment';
	}

	/**
	 * @inheritdoc
	 * @return AuthAssignmentQuery the active query used by this AR class.
	 */
	public static function find()
	{
		return new AuthAssignmentQuery(get_called_class());
	}

	/**
	 * @inheritdoc
	 */
	public function rules()
	{
		return [
			[['item_name', 'user_id'], 'required'],
			[['created_at'], 'integer'],
			[['item_name', 'user_id'], 'string', 'max' => 64],
			[['item_name'], 'exist', 'skipOnError' => true, 'targetClass' => AuthItem::class, 'targetAttribute' => ['item_name' => 'name']],
		];
	}

	/**
	 * Название роли
	 * @return string
	 */
	public function getAuthItemName()
	{
		if ($model = $this->authItem) {
			return $model->description;
		}
	}

	/**
	 * @inheritdoc
	 */
	public function attributeLabels()
	{
		return [
			'id' => Yii::t('app', 'ID'),
			'item_name' => Yii::t('app', 'Роль'),
			'user_id' => Yii::t('app', 'Пользователь'),
			'created_at' => Yii::t('app', 'Дата создания'),
		];
	}

	/**
	 * Get User
	 * @return \yii\db\ActiveQuery
	 */
	public function getUser()
	{
		return $this->hasOne(User::class, ['id' => 'user_id']);
	}

	/**
	 * Get Role
	 * @return \yii\db\ActiveQuery
	 */
	public function getAuthItem()
	{
		return $this->hasOne(AuthItem::class, ['name' => 'item_name']);
	}

	/**
	 * Get Role
	 * @return \yii\db\ActiveQuery
	 */
	public function getItemName()
	{
		return $this->hasOne(AuthItem::class, ['name' => 'item_name']);
	}
}
