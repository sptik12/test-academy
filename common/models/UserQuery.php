<?php

namespace common\models;

use Yii;

/**
 * This is the ActiveQuery class for [[User]].
 *
 * @see User
 */
class UserQuery extends \yii\db\ActiveQuery
{
	/*
	 * Managed users scope
	 */
	public function active()
	{
		return $this->andWhere('[[user.status]] = '. User::STATUS_ACTIVE);
	}

	/*
	 * Inactive users scope
	 */
	public function inactive()
	{
		return $this->andWhere('[[user.status]] = '. User::STATUS_INACTIVE);
	}

	/**
	 * @inheritdoc
	 * @return User[]|array
	 */
	public function all($db = null)
	{
		return parent::all($db);
	}

	/**
	 * @inheritdoc
	 * @return User|array|null
	 */
	public function one($db = null)
	{
		return parent::one($db);
	}
}
