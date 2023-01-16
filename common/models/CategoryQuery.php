<?php

namespace common\models;

use Yii;

/**
 * This is the ActiveQuery class for [[Category]].
 *
 * @see Category
 */
class CategoryQuery extends \yii\db\ActiveQuery
{
	/**
	 * @inheritdoc
	 * @return Order[]|array
	 */
	public function all($db = null)
	{
		return parent::all($db);
	}

	/**
	 * @inheritdoc
	 * @return Order|array|null
	 */
	public function one($db = null)
	{
		return parent::one($db);
	}
}
