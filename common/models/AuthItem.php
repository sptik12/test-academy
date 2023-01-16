<?php

namespace common\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "auth_item".
 *
 * @property string $name
 * @property integer $type
 * @property string $description
 * @property string $rule_name
 * @property resource $data
 * @property integer $created_at
 * @property integer $updated_at
 *
 * @property AuthAssignment[] $authAssignments
 * @property AuthRule $ruleName
 * @property AuthItemChild[] $authItemChildren
 * @property AuthItemChild[] $authItemChildren0
 * @property AuthItem[] $children
 * @property AuthItem[] $parents
 */
class AuthItem extends BaseActiveRecord
{
	/*
	 * roles
	 */
	const ROLE_ADMIN = 'admin';
	const ROLE_MANAGER = 'manager';

	/*
	 * permissions
	 */
	const PERMISSION_USER_VIEW = 'users-view';
	const PERMISSION_USER_EDIT= 'users-edit';
	const PERMISSION_EVENT_LOG = 'event-log';
	const PERMISSION_ORDERS = 'orders';

	/**
	 * @inheritdoc
	 */
	public static function tableName()
	{
		return 'auth_item';
	}

	/**
	 * Get DropDown list of roles
	 * @return array
	 */
	public static function getOptions()
	{
		$authItems = Yii::$app->authManager->getRoles();
		return ArrayHelper::map($authItems, 'name', 'description');
	}

	/**
	 * @inheritdoc
	 */
	public function rules()
	{
		return [
			[['name', 'type'], 'required'],
			[['type', 'created_at', 'updated_at'], 'integer'],
			[['description', 'data'], 'string'],
			[['name'], 'string', 'max' => 64],
		];
	}

	/**
	 * @inheritdoc
	 */
	public function attributeLabels()
	{
		return [
			'name' => Yii::t('app', 'Название'),
			'type' => Yii::t('app', 'Тип'),
			'description' => Yii::t('app', 'Описание'),
			'rule_name' => Yii::t('app', 'Правило'),
			'data' => Yii::t('app', 'Данные'),
			'created_at' => Yii::t('app', 'Дата создания'),
			'updated_at' => Yii::t('app', 'Дата изменения'),
		];
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getAuthAssignments()
	{
		return $this->hasMany(AuthAssignment::class, ['item_name' => 'name']);
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getRuleName()
	{
		return $this->hasOne(AuthRule::class, ['name' => 'rule_name']);
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getAuthItemChildren()
	{
		return $this->hasMany(AuthItemChild::class, ['parent' => 'name']);
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getAuthItemChildren0()
	{
		return $this->hasMany(AuthItemChild::class, ['child' => 'name']);
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getChildren()
	{
		return $this->hasMany(AuthItem::class, ['name' => 'child'])->viaTable('auth_item_child', ['parent' => 'name']);
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getParents()
	{
		return $this->hasMany(AuthItem::class, ['name' => 'parent'])->viaTable('auth_item_child', ['child' => 'name']);
	}
}
