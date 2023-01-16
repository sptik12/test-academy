<?php

namespace common\models;

use Yii;

/**
 * This is the ActiveQuery class for [[AuthAssignment]].
 *
 * @see AuthAssignment
 */
class AuthAssignmentQuery extends \yii\db\ActiveQuery
{
	/**
	 * @inheritdoc
	 * @return AuthAssignment[]|array
	 */
	public function all($db = null)
	{
		return parent::all($db);
	}

	/**
	 * @inheritdoc
	 * @return AuthAssignment|array|null
	 */
	public function one($db = null)
	{
		return parent::one($db);
	}

	/**
	 * Ordered scope
	 *
	 * @return $this
	 */
	public function ordered()
	{
		$this->orderBy(['created_at' => SORT_DESC]);
		return $this;
	}
}
