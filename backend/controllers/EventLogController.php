<?php

namespace backend\controllers;

use Yii;
use yii\web\NotFoundHttpException;
use yii\filters\AccessControl;

use common\helpers\Html;
use common\helpers\Format;
use common\models\AuthItem;
use common\models\EventLog;
use common\models\EventLogChange;
use backend\models\EventLogSearch;
use backend\models\EventLogChangeSearch;

/**
 * EventLogController implements the CRUD actions for EventLog model.
 */
class EventLogController extends BaseController
{
	/**
	 * {@inheritdoc}
	 */
	public function behaviors()
	{
		return [
			'access' => [
				'class' => AccessControl::className(),
				'rules' => [
					[
						'actions' => ['index', 'view'],
						'allow' => true,
						'roles' => array_values(EventLog::$permissions),
					],
					[
						'actions' => ['delete-bulk', 'delete-period'],
						'allow' => true,
						'roles' => [AuthItem::PERMISSION_EVENT_LOG],
					],
				],
			],
		];
	}

	/**
	 * Lists all EventLog models.
	 * @return mixed
	 */
	public function actionIndex()
	{
		$searchModel = new EventLogSearch();
		$dataProvider = $searchModel->search($this->queryParams);
		$dataProvider->pagination->pageSize = $this->gridPageSize;
		$dataProvider->sort->defaultOrder = $this->getSortDefaultOrder($searchModel, ['created_at' => SORT_DESC]);

		$params = [
			'searchModel' => $searchModel,
			'dataProvider' => $dataProvider,
		];

		return Yii::$app->request->isAjax ? $this->renderPartial('index', $params) : $this->render('index', $params);
	}

	/**
	 * Displays a single EventLog model.
	 * @return mixed
	 * @throws NotFoundHttpException if the model cannot be found
	 */
	public function actionView()
	{
		if (isset($_POST['expandRowKey'])) {
			$searchModel = new EventLogChangeSearch();
			$searchModel->event_log_id = $_POST['expandRowKey'];

			$dataProvider = $searchModel->search([]);
			$dataProvider->pagination = false;
			$dataProvider->sort = false;

			return $this->renderPartial('_view', ['searchModel' => $searchModel, 'dataProvider' => $dataProvider]);
		} else {
			return Html::noData();
		}
	}

	/**
	 * Deletes an existing EventLog models.
	 * If deletion is successful, the browser will be redirected to the 'index' page.
	 * @param array $ids
	 * @return mixed
	 * @throws NotFoundHttpException if the model cannot be found
	 */
	public function actionDeleteBulk(array $ids)
	{
		foreach ($ids as $id) {
			$this->findModel($id)->delete();
		}
		if (!Yii::$app->request->isAjax) {
			$this->successAlert = Yii::t('app', 'Записи лога были удалены');
			return $this->redirect(['index']);
		}
	}

	/**
	 * Deletes an existing EventLog models.
	 * If deletion is successful, the browser will be redirected to the 'index' page.
	 * @param int $start_date
	 * @param int $end_date
	 * @param int|null $record_id
	 * @param int|null $action_id
	 * @param string|null $model_name
	 * @param string|int $title
	 * @param int|null $owner_id
	 * @return mixed
	 * @throws NotFoundHttpException if the model cannot be found
	 */
	public function actionDeletePeriod($start_date, $end_date, $record_id = null, $action_id = null, $model_name = null, $title = null, $owner_id = null)
	{
		$ids = EventLog::find()->select('id')
			->andFilterWhere(['between', 'created_at', Format::getStartDate($start_date), Format::getFinishDate($end_date)])
			->andFilterWhere([
				'action_id' => $action_id,
				'owner_id' => $owner_id,
				'model_name' => $model_name,
				'record_id' => $record_id,
			])->andFilterWhere(['like', 'title', $title])->column();

		if ($ids) {
			EventLogChange::deleteAll(['event_log_id' => $ids]);
			EventLog::deleteAll(['id' => $ids]);
		}

		if (!Yii::$app->request->isAjax) {
			$this->successAlert = Yii::t('app', 'Записи лога были удалены');
			return $this->redirect(['index']);
		}
	}

	/**
	 * Finds the EventLog model based on its primary key value.
	 * If the model is not found, a 404 HTTP exception will be thrown.
	 * @param integer $id
	 * @return EventLog the loaded model
	 * @throws NotFoundHttpException if the model cannot be found
	 */
	protected function findModel($id)
	{
		if (($model = EventLog::findOne($id)) !== null) {
			return $model;
		}

		throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
	}
}
