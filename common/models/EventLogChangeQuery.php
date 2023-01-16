<?php

namespace common\models;

/**
 * This is the ActiveQuery class for [[EventLogChange]].
 *
 * @see EventLogChange
 */
class EventLogChangeQuery extends \yii\db\ActiveQuery
{
	/**
	 * {@inheritdoc}
	 * @return EventLogChange[]|array
	 */
	public function all($db = null)
	{
		return parent::all($db);
	}

	/**
	 * {@inheritdoc}
	 * @return EventLogChange|array|null
	 */
	public function one($db = null)
	{
		return parent::one($db);
	}
}
