<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\EventLogChange;

/**
 * EventLogChangeSearch represents the model behind the search form of `common\models\EventLogChange`.
 */
class EventLogChangeSearch extends EventLogChange
{
	/**
	 * {@inheritdoc}
	 */
	public function rules()
	{
		return [
			[['id', 'event_log_id'], 'integer'],
			[['attribute', 'label', 'old_value', 'new_value'], 'safe'],
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
		$query = EventLogChange::find();

		$dataProvider = new ActiveDataProvider([
			'query' => $query,
		]);

		$this->load($params);

		if (!$this->validate()) {
			// uncomment the following line if you do not want to return any records when validation fails
			// $query->where('0=1');
			return $dataProvider;
		}

		// grid filtering conditions
		$query->andFilterWhere([
			'id' => $this->id,
			'event_log_id' => $this->event_log_id,
		]);

		$query->andFilterWhere(['like', 'attribute', $this->attribute])
			->andFilterWhere(['like', 'label', $this->label])
			->andFilterWhere(['like', 'old_value', $this->old_value])
			->andFilterWhere(['like', 'new_value', $this->new_value]);

		return $dataProvider;
	}
}
