<?php

namespace common\models;

use Yii;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;

class BaseActiveRecord extends ActiveRecord
{
	/**
	 * @var array
	 */
	public static $_cache = [];

	/**
	 * return now() from db
	 * @return string
	 */
	public static function getNowDb()
	{
		if (!array_key_exists('getNowDb', static::$_cache)) {
			static::$_cache['getNowDb'] = (new \yii\db\Query())->select('NOW() as cur_time')->scalar();
		}
		return static::$_cache['getNowDb'];
	}

	/**
	 * return curdate() from db
	 * @return string
	 */
	public static function getCurdateDb()
	{
		if (!array_key_exists('getCurdateDb', static::$_cache)) {
			static::$_cache['getCurdateDb'] = (new \yii\db\Query())->select('CURDATE() as cur_date')->scalar();
		}
		return static::$_cache['getCurdateDb'];
	}

	/**
	 * Save data to default fields
	 * @param bool $insert
	 * @return bool
	 * @throws \yii\base\InvalidConfigException
	 */
	public function beforeSave($insert)
	{
		if ($insert) {
			if ($this->hasAttribute('owner_id')) {
				if (empty($this->owner_id)) {
					$user = Yii::$app->get('user', false);
					$this->owner_id = $user && !$user->isGuest ? $user->id : null;
				}
			}
		}

		return parent::beforeSave($insert);
	}

	/*
	 * Log save event
	* @return bool
	 */
	public function logSave($title, $insert, $changedAttributes)
	{
		if ($insert) {
			return $this->logEvent($title, EventLog::INSERT);
		} else {
			return $this->logEvent($title, EventLog::UPDATE, $changedAttributes);
		}
	}

	/*
	 * Log event
	 * @return bool
	 */
	public function logEvent($title, $action_id, $changedAttributes = [])
	{
		$model = new EventLog();
		$model->title = $title;
		$model->action_id = $action_id;
		$model->record_id = $this->primaryKey;
		$model->table_name = $this->tableName();
		$model->model_name = $this->formName();
		$model->status = EventLog::STATUS_ACTIVE;
		if ($model->save(false)) {
			if ($changedAttributes) {
				$exclude_attributes = ['created_at', 'updated_at', 'owner_id'];
				foreach ($changedAttributes as $attribute => $old_value) {
					if (!in_array($attribute, $exclude_attributes)) {
						$change = new EventLogChange();
						$change->event_log_id = $model->id;
						$change->attribute = $attribute;
						$change->label = $this->getAttributeLabel($attribute);
						$change->old_value = $old_value;
						$change->new_value = $this->getAttribute($attribute);
						$change->save();
					}
				}
			}
			if ($model->action_id == EventLog::DELETE) {
				EventLog::updateAll(['status' => EventLog::STATUS_DELETED], ['record_id' => strval($model->record_id), 'model_name' => $model->model_name]);
			}
			return true;
		}
		return false;
	}

	/*
	 * Log delete event
	 * @return bool
	 */
	public function logDelete($title)
	{
		return $this->logEvent($title, EventLog::DELETE);
	}

	/**
	 * Get event log records count
	 * @return int
	 */
	public function getEventLogsCount()
	{
		return ($this->isNewRecord) ? 0 : $this->getEventLogs()->count();
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getEventLogs()
	{
		$keys = $this->primaryKey();
		$key = reset($keys);
		return $this->hasMany(EventLog::className(), ['record_id' => $key])->onCondition(['event_log.model_name' => $this->formName()]);
	}

	/*
	 * Get log route
	 * @return array
	 */
	public function getLogRoute($params = [])
	{
		return ArrayHelper::merge(['/event-log'], $params, ['EventLogSearch[model_name]' => $this->formName(), 'EventLogSearch[record_id]' => $this->primaryKey, 'clear' => 1]);
	}

}
