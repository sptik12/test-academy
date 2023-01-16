<?php

namespace backend\controllers;

use Yii;
use yii\web\Controller;
use yii\helpers\ArrayHelper;
use common\helpers\Html;

/**
 * Родительский контроллер
 */
class BaseController extends Controller
{
	/**
	 * Get Query Params and merge with state
	 * @param string $key
	 */
	public function getQueryParams($key = '', $param = 'page', $clear = 'clear')
	{
		$session_key = $this->id . '_' . $this->action->id . '_filter_' . $key;
		$query_params = Yii::$app->request->queryParams;
		if (isset($query_params[$clear])) {
			Yii::$app->session->set($session_key, []);
			unset($_GET[$clear]);
		}
		$session_params = Yii::$app->session->get($session_key);
		if (is_array($session_params)) {
			if (isset($session_params[$param]) && !isset($query_params[$param])) {
				$_GET[$param] = $session_params[$param];
			}
			$query_params = ArrayHelper::merge($session_params, $query_params);
		}
		Yii::$app->session->set($session_key, $query_params);
		return $query_params;
	}

	/**
	 * Set/get grid sort
	 * @param object $model
	 * @param array|null $defaultValue
	 * @return array|null
	 */
	public function getSortDefaultOrder($model, $defaultValue = null)
	{
		$getParam = 'sort';
		$key = get_class($model);
		$sessionParam = $getParam . $key;
		if (isset($_GET[$getParam])) {
			Yii::$app->session->set($sessionParam, $_GET[$getParam]);
		} else if (Yii::$app->session->get($sessionParam)) {
			$sortValue = Yii::$app->session->get($sessionParam);
			$sort = explode('-', $sortValue);
			return (count($sort) > 1) ? [$sort[1] => SORT_DESC] : [$sort[0] => SORT_ASC];
		}

		if ($defaultValue == null) {
			$sort = [];
			foreach ($model->tableSchema->primaryKey as $key) {
				$sort[$key] = SORT_ASC;
			}
			return $sort;
		} else
			return $defaultValue;
	}

	/**
	 * Количество записей на странице грида
	 *
	 * @param int $default
	 * @return int
	 */
	public function getGridPageSize($default = 20)
	{
		if (isset(Yii::$app->params['gridPageSize'])) {
			return (int)Yii::$app->params['gridPageSize'];
		} else {
			return $default;
		}
	}

	/**
	 * Set success alert.
	 * @param string $message message to be displayed
	 * @param bool $encode if message should be HTML encoded
	 */
	public function setSuccessAlert($message, $encode = true)
	{
		$this->setAlert($message, 'success', $encode);
	}

	/**
	 * Set alert.
	 * @param string $message message to be displayed
	 * @param string $key key can be 'success', 'info', 'warning' or 'danger'
	 * @param bool $encode if message should be HTML encoded
	 */
	public function setAlert($message, $key = 'success', $encode = true)
	{
		if ($encode) {
			$message = Html::encode($message);
		}
		\Yii::$app->getSession()->setFlash($key, $message);
	}

	/**
	 * Set info alert.
	 * @param string $message message to be displayed
	 * @param bool $encode if message should be HTML encoded
	 */
	public function setInfoAlert($message, $encode = true)
	{
		$this->setAlert($message, 'info', $encode);
	}

	/**
	 * Set warning alert.
	 * @param string $message message to be displayed
	 * @param bool $encode if message should be HTML encoded
	 */
	public function setWarningAlert($message, $encode = true)
	{
		$this->setAlert($message, 'warning', $encode);
	}

	/**
	 * Set error alert.
	 * @param string $message message to be displayed
	 * @param bool $encode if message should be HTML encoded
	 */
	public function setErrorAlert($message, $encode = true)
	{
		$this->setAlert($message, 'danger', $encode);
	}

}
