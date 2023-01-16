<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\helpers\Format;
use yii\helpers\ArrayHelper;
use common\models\EventLog;
use common\models\User;

/**
 * EventLogSearch represents the model behind the search form of `common\models\EventLog`.
 */
class EventLogSearch extends EventLog
{
	/**
	 * @var string
	 */
	public $start_date;

	/**
	 * @var string
	 */
	public $end_date;

	/**
	 * @var string
	 */
	public $keyword;

	const RANGE_SEPARATOR = ' - ';

	/**
	 * {@inheritdoc}
	 */
	public function rules()
	{
		return [
			[['id', 'action_id', 'owner_id'], 'integer'],
			[['record_id', 'table_name', 'model_name', 'title', 'created_at', 'start_date', 'end_date', 'keyword'], 'safe'],
			[['title', 'record_id', 'keyword'], 'filter', 'filter' => 'trim'],
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
		$query = EventLog::find()->indexBy('id');
		$query->with(['user', 'eventLogChanges']);

		$query->andWhere(['model_name' => $this->getPermittedModelNames()]);

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
			'action_id' => $this->action_id,
			'owner_id' => $this->owner_id,
			'model_name' => $this->model_name,
			'table_name' => $this->table_name,
			'record_id' => $this->record_id,
		]);

		$query->andFilterWhere(['like', 'title', $this->title]);

		if($this->created_at){
			$array = explode(self::RANGE_SEPARATOR, $this->created_at);
			$this->end_date = end($array);
			$this->start_date = reset($array);
		}


		if (!empty($this->start_date) && !empty($this->end_date)) {
			$query->andFilterWhere(['between', 'created_at', Format::getStartDate($this->start_date), Format::getFinishDate($this->end_date)]);

		}
		if ($this->keyword) {
			$query->andWhere([self::tableName() . '.license_id' => LicenseSearch::extractIds($this->keyword)]);
		}


		return $dataProvider;
	}

	/**
	 * Creates permitted model names
	 *
	 * @return array
	 */
	public function getPermittedModelNames()
	{
		$permissions = EventLog::$permissions;
		$res = [];
		foreach ($permissions as $model_name => $permission) {
			if (Yii::$app->user->can($permission)) {
				$res[] = $model_name;
			}
		}
		return $res;
	}

	/**
	 * Creates permitted model list in 'name => title' format
	 *
	 * @return array
	 */
	public function getPermittedModelItems()
	{
		$items = EventLog::$items;
		$allowed = $this->getPermittedModelNames();
		return array_intersect_key($items, array_flip($allowed));
	}

	/**
	 * Creates user list in 'id => name' format
	 *
	 * @return array
	 */
	public function getUserItems()
	{
		return User::find()->select(['username'])->orderBy(['username' => SORT_ASC])->indexBy('id')->column();
	}

}
