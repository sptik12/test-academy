<?php

namespace common\models;

use Yii;

/**
 * This is the ActiveQuery class for [[Order]].
 *
 * @see Order
 */
class OrderQuery extends \yii\db\ActiveQuery
{
	/*
	 * filter scope
	 */
	public function filter($status)
	{
		return $this->andWhere('[[order.status]] = '. $status);
	}

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
