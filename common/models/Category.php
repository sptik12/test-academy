<?php

namespace common\models;

use Yii;
use yii\helpers\ArrayHelper;
use yii\helpers\FileHelper;
use common\helpers\Html;

/**
 * This is the model class for table "category".
 *
 * @property integer $id
 * @property string $name
 * @property integer $created_at
 * @property integer $updated_at
 */
class Category extends BaseActiveRecord
{
	/**
	 * {@inheritdoc}
	 */
	public static function tableName()
	{
		return 'category';
	}

	/**
	 * {@inheritdoc}
	 * @return CategoryQuery the active query used by this AR class.
	 */
	public static function find()
	{
		return new CategoryQuery(get_called_class());
	}

	/**
	 * {@inheritdoc}
	 */
	public function rules()
	{
		return [
			[['name'], 'required', 'on'=> ['create', 'update']],
		];
	}


	/**
	 * {@inheritdoc}
	 */
	public function attributeLabels()
	{
		return [
			'id' => Yii::t('app', 'ID'),
			'name' => Yii::t('app', 'Товар'),
			'created_at' => Yii::t('app', 'Дата создания'),
			'updated_at' => Yii::t('app', 'Дата изменения'),
		];
	}

}
