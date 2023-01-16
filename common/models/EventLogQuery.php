<?php

namespace common\models;

/**
 * This is the ActiveQuery class for [[EventLog]].
 *
 * @see EventLog
 */
class EventLogQuery extends \yii\db\ActiveQuery
{
	/**
	 * {@inheritdoc}
	 * @return EventLog[]|array
	 */
	public function all($db = null)
	{
		return parent::all($db);
	}

	/**
	 * {@inheritdoc}
	 * @return EventLog|array|null
	 */
	public function one($db = null)
	{
		return parent::one($db);
	}
}
