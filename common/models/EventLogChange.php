<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "event_log_change".
 *
 * @property int $id
 * @property int $event_log_id
 * @property string $attribute
 * @property string $label
 * @property string $old_value
 * @property string $new_value
 */
class EventLogChange extends BaseActiveRecord
{
	/**
	 * {@inheritdoc}
	 */
	public static function tableName()
	{
		return 'event_log_change';
	}

	/**
	 * {@inheritdoc}
	 * @return EventLogChangeQuery the active query used by this AR class.
	 */
	public static function find()
	{
		return new EventLogChangeQuery(get_called_class());
	}

	/**
	 * {@inheritdoc}
	 */
	public function rules()
	{
		return [
			[['event_log_id'], 'required'],
			[['event_log_id'], 'integer'],
			[['old_value', 'new_value'], 'safe'],
			[['attribute', 'label'], 'string', 'max' => 255],
			['new_value', 'compare', 'compareAttribute' => 'old_value', 'operator' => '!='],
		];
	}

	/**
	 * {@inheritdoc}
	 */
	public function attributeLabels()
	{
		return [
			'id' => 'ID',
			'event_log_id' => 'Event Log ID',
			'attribute' => Yii::t('app','Атрибут'),
			'label' => Yii::t('app','Имя атрибута'),
			'old_value' => Yii::t('app','с'),
			'new_value' => Yii::t('app','на'),
		];
	}
}
