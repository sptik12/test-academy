<?php

namespace backend\controllers;


use Yii;
use common\models\Order;
use common\models\AuthItem;
use backend\models\OrderSearch;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\web\NotFoundHttpException;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\filters\AjaxFilter;

/**
 * OrderController implements the CRUD actions for Order model.
 */
class OrderController extends BaseController
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
						'roles' => [AuthItem::ROLE_ADMIN, AuthItem::ROLE_MANAGER],
					],
					[
						'actions' => ['create','update', 'delete', 'bulk-delete', 'change-status'],
						'allow' => true,
						'roles' => [AuthItem::PERMISSION_ORDERS],
					],
				],
			],
			[
				'class' => AjaxFilter::className(),
				'only' => ['bulk-delete']
			],
			'verbs' => [
				'class' => VerbFilter::className(),
				'actions' => [
					'delete' => ['POST'],
				],
			],
		];
	}

	/**
	 * Lists all Order models.
	 * @return mixed
	 */
	public function actionIndex()
	{
		$searchModel = new OrderSearch();
		$dataProvider = $searchModel->search($this->queryParams);
		$dataProvider->pagination->pageSize = $this->gridPageSize;
		$dataProvider->sort->defaultOrder = $this->getSortDefaultOrder($searchModel, ['created_at' => SORT_DESC]);

		Url::remember(['index'], 'order_form');

		$params = [
			'searchModel' => $searchModel,
			'dataProvider' => $dataProvider,
		];

		return Yii::$app->request->isAjax ? $this->renderPartial('index', $params) : $this->render('index', $params);
	}

	/**
	 * Displays a single Order model.
	 * @param integer $id
	 * @return mixed
	 * @throws NotFoundHttpException if the model cannot be found
	 */
	public function actionView($id)
	{
		Url::remember(['view', 'id' => $id], 'order_form');

		return $this->render('view', [
			'model' => $this->findModel($id),
		]);
	}

	/**
	 * Finds the Order model based on its primary key value.
	 * If the model is not found, a 404 HTTP exception will be thrown.
	 * @param integer $id
	 * @return Order the loaded model
	 * @throws NotFoundHttpException if the model cannot be found
	 */
	protected function findModel($id)
	{
		if (($model = Order::findOne($id)) !== null) {
			return $model;
		}

		throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
	}

	/**
	 * Creates a new Order model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 * @return mixed
	 */
	public function actionCreate()
	{
		$model = new Order();
		$model->scenario = 'create';

		$route = ($url = Url::previous('order_form')) ? $url : ['index'];

		if ($model->load(Yii::$app->request->post())) {
			if ($model->validate()) {
				if ($model->save(false)) {
					$this->successAlert = Yii::t('app', 'Заявка создана');
					return $this->redirect(['view', 'id' => $model->id]);
				}
			}
		} else {
			$model->status = Order::ORDER_STATUS_ACCEPTED;
		}

		return $this->render('create', [
			'model' => $model,
			'route' => $route,
		]);
	}

	/**
	 * Updates an existing Order model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id
	 * @return mixed
	 * @throws NotFoundHttpException if the model cannot be found
	 */
	public function actionUpdate($id)
	{
		$model = $this->findModel($id);
		$model->scenario = 'update';

		$route = ($url = Url::previous('order_form')) ? $url : ['view', 'id' => $model->id];

		if ($model->load(Yii::$app->request->post())) {
			if ($model->validate()) {
				if ($model->save()) {
						$this->successAlert = Yii::t('app', 'Редактирование выполнено');
						return $this->redirect($route);
				}
			}
		}

		return $this->render('update', [
			'model' => $model,
			'route' => $route,
		]);
	}

	/**
	 * Смена статуса заявки
	 * @param integer $id
	 * @return mixed
	 */
	public function actionChangeStatus($id)
	{
		$model = $this->findModel($id);
		$model->scenario = 'change-status';

		$route = ($url = Url::previous('order_form')) ? $url : ['view', 'id' => $model->id];

		if ($model->load(Yii::$app->request->post())) {
			if ($model->validate()) {
				if ($model->save(false)) {
					$this->successAlert = Yii::t('app', 'Статус заявки был успешно изменен');
					return $this->redirect($route);
				}
			}
		}

		return $this->render('change-status', [
			'model' => $model,
			'route' => $route,
		]);
	}

	/**
	 * Deletes an existing Order model.
	 * If deletion is successful, the browser will be redirected to the 'index' page.
	 * @param integer $id
	 * @return mixed
	 * @throws NotFoundHttpException if the model cannot be found
	 */
	public function actionDelete($id)
	{
		$this->findModel($id)->delete();
		if (!Yii::$app->request->isAjax) {
			return $this->redirect(['index']);
		}
	}

	/**
	 * Deletes an existing Order models
	 * @param array $ids
	 * @param booleam $msg - whether to display confirmation message
	 * @return mixed
	 */
	public function actionBulkDelete(array $ids, $msg = false)
	{
		$models = Order::find()->andWhere(['id' => $ids])->all();
		$countsDeleted = 0;
		foreach ($models as $model) {
			$model->delete();
			$countsDeleted++;
		}
		if ($msg) {
			if ($countsDeleted) {
				return ($countsDeleted > 1) ? Yii::t('app', 'Заявки удалены') : Yii::t('app', 'Заявка удалена');
			}
		}
	}

}
