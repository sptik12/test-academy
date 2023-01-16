<?php

namespace backend\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;

use common\models\Order;

/**
 * OrderSearch represents the model behind the search form of `common\models\Order`.
 */
class OrderSearch extends Order
{
	/*
	 * полное имя пользователя
	 */
	public $full_name;

	/**
	 * {@inheritdoc}
	 */
	public function rules()
	{
		return [
			[['id', 'status'], 'integer'],
			[['title', 'first_name', 'last_name', 'full_name'], 'safe'],
		];
	}

	/**
	 * {@inheritdoc}
	 */
	public function scenarios()
	{
		// bypass scenarios() implementation in the parent class
		return Model::scenarios();
	}

	/**
	 * Creates data provider instance with search query applied
	 *
	 * @param array $params
	 *
	 * @return ActiveDataProvider
	 */
	public function search($params)
	{
		$query = Order::find()->indexBy('id')->joinwith('category');

		$dataProvider = new ActiveDataProvider([
			'query' => $query,
		]);

		$dataProvider->sort->attributes['full_name'] = [
			'asc' => [self::tableName() . '.last_name' => SORT_ASC, self::tableName() . '.first_name' => SORT_ASC],
			'desc' => [self::tableName() . '.last_name' => SORT_DESC, self::tableName() . '.first_name' => SORT_DESC],
		];

		$this->load($params);

		if (!$this->validate()) {
			// uncomment the following line if you do not want to return any records when validation fails
			// $query->where('0=1');
			return $dataProvider;
		}

		// grid filtering conditions
		$query->andFilterWhere([
			'status' => $this->status,
		]);

		$query->andFilterWhere(['like', 'first_name', $this->first_name])
			->andFilterWhere(['like', 'last_name', $this->last_name])
			->andFilterWhere(['like', 'title', $this->title]);

		if ($this->full_name != null) {
			$keywords = explode(' ', $this->full_name);
			foreach ($keywords as $keyword) {
				$query->andFilterWhere([
					'or',
					['like', self::tableName() . '.first_name', $keyword],
					['like', self::tableName() . '.last_name', $keyword],
				]);
			}
		}

		return $dataProvider;
	}
}
