<?php

namespace backend\controllers;


use Yii;
use common\models\User;
use common\models\AuthItem;
use backend\models\UserSearch;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\web\NotFoundHttpException;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\filters\AjaxFilter;

/**
 * UserController implements the CRUD actions for User model.
 */
class UserController extends BaseController
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
						'actions' => ['create','update', 'delete', 'bulk-delete','change-password'],
						'allow' => true,
						'roles' => [AuthItem::PERMISSION_USER_EDIT],
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
	 * Lists all User models.
	 * @return mixed
	 */
	public function actionIndex()
	{
		$searchModel = new UserSearch();
		$dataProvider = $searchModel->search($this->queryParams);
		$dataProvider->pagination->pageSize = $this->gridPageSize;
		$dataProvider->sort->defaultOrder = $this->getSortDefaultOrder($searchModel, ['full_name' => SORT_ASC]);

		Url::remember(['index'], 'user_form');

		$params = [
			'searchModel' => $searchModel,
			'dataProvider' => $dataProvider,
		];

		return Yii::$app->request->isAjax ? $this->renderPartial('index', $params) : $this->render('index', $params);
	}

	/**
	 * Displays a single User model.
	 * @param integer $id
	 * @return mixed
	 * @throws NotFoundHttpException if the model cannot be found
	 */
	public function actionView($id)
	{
		Url::remember(['view', 'id' => $id], 'user_form');

		return $this->render('view', [
			'model' => $this->findModel($id),
		]);
	}

	/**
	 * Finds the User model based on its primary key value.
	 * If the model is not found, a 404 HTTP exception will be thrown.
	 * @param integer $id
	 * @return User the loaded model
	 * @throws NotFoundHttpException if the model cannot be found
	 */
	protected function findModel($id)
	{
		if (($model = User::findOne($id)) !== null) {
			return $model;
		}

		throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
	}

	/**
	 * Creates a new User model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 * @return mixed
	 */
	public function actionCreate()
	{
		$model = new User();
		$model->scenario = 'create';

		$route = ($url = Url::previous('user_form')) ? $url : ['index'];

		if ($model->load(Yii::$app->request->post())) {
			if ($model->validate()) {
				$model->setPasswordHash($model->password);
				$model->generateAuthKey();
				$model->username = $model->email;
				if ($model->save(false)) {
					$model->updateRoles();
					$this->successAlert = Yii::t('app', 'Пользователь создан');
					return $this->redirect(['view', 'id' => $model->id]);
				}
			}
		} else {
			$model->status = User::STATUS_ACTIVE;
		}

		return $this->render('create', [
			'model' => $model,
			'route' => $route,
		]);
	}

	/**
	 * Updates an existing User model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id
	 * @return mixed
	 * @throws NotFoundHttpException if the model cannot be found
	 */
	public function actionUpdate($id)
	{
		$model = $this->findModel($id);
		$model->scenario = 'update';

		$route = ($url = Url::previous('user_form')) ? $url : ['view', 'id' => $model->id];

		if ($model->load(Yii::$app->request->post())) {
			$isSelfError = false;
			if ($model->id == Yii::$app->user->id) {
				if ($model->status == User::STATUS_INACTIVE) {
					$this->errorAlert = Yii::t('app', 'Вы пытаетесь отключить себя от системы');
					$isSelfError = true;
				}
				if (!in_array(AuthItem::ROLE_ADMIN, $model->auth_item_names)) {
					$this->errorAlert = Yii::t('app', 'Вы пытаетесь понизить свою роль');
					$isSelfError = true;
				}
			}
			if (!$isSelfError) {
				if ($model->validate()) {
					$model->username = $model->email;
					if ($model->save()) {
						$model->updateRoles();
						$this->successAlert = Yii::t('app', 'Редактирование выполнено');
						return $this->redirect($route);
					}
				}
			}
		} else {
			$model->auth_item_names = ArrayHelper::getColumn($model->authAssignments, 'item_name');
		}

		return $this->render('update', [
			'model' => $model,
			'route' => $route,
		]);
	}

	/**
	 * Смена пароля
	 * @param integer $id
	 * @return mixed
	 */
	public function actionChangePassword($id)
	{
		$model = $this->findModel($id);
		$model->scenario = 'change-password';

		$route = ($url = Url::previous('user_form')) ? $url : ['view', 'id' => $model->id];

		if ($model->load(Yii::$app->request->post())) {
			if ($model->validate()) {
				$model->setPasswordHash($model->password);
				if ($model->save(false)) {
					$this->successAlert = Yii::t('app', 'Пароль был успешно изменен');
					return $this->redirect($route);
				}
			}
		}

		return $this->render('change-password', [
			'model' => $model,
			'route' => $route,
		]);
	}

	/**
	 * Deletes an existing User model.
	 * If deletion is successful, the browser will be redirected to the 'index' page.
	 * @param integer $id
	 * @return mixed
	 * @throws NotFoundHttpException if the model cannot be found
	 */
	public function actionDelete($id)
	{
		if ($id != Yii::$app->user->getId()) {
			$this->findModel($id)->delete();
			if (!Yii::$app->request->isAjax) {
				return $this->redirect(['index']);
			}
		}
	}

	/**
	 * Deletes an existing User models
	 * @param array $ids
	 * @param booleam $msg - whether to display confirmation message
	 * @return mixed
	 */
	public function actionBulkDelete(array $ids, $msg = false)
	{
		$models = User::find()->andWhere(['id' => $ids])->all();
		$countsDeleted = 0;
		foreach ($models as $model) {
			if ($model->id != Yii::$app->user->getId()) {
				$model->delete();
				$countsDeleted++;
			}
		}
		if ($msg) {
			if ($countsDeleted) {
				return ($countsDeleted > 1) ? Yii::t('app', '{count} пользователей удалены', ['count' => $countsDeleted]) : Yii::t('app', 'Пользователь удален');
			}
		}
	}

}
