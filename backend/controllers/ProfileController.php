<?php

namespace backend\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\filters\AjaxFilter;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use yii\helpers\ArrayHelper;

use common\models\User;
use backend\models\ProfileForm;

/**
 * ProfileController implements the CRUD actions for User model.
 */
class ProfileController extends BaseController
{
	/**
	 * @inheritdoc
	 */
	public $defaultAction = 'view';

	/**
	 * @inheritdoc
	 */
	public function behaviors()
	{
		return [
			'access' => [
				'class' => AccessControl::className(),
				'rules' => [
					[
						'allow' => true,
						'roles' => ['@'],
					],
				],
			],
		];
	}

	/**
	 * Displays a single Users model.
	 * @return mixed
	 * @throws NotFoundHttpException if the model cannot be found
	 */
	public function actionView()
	{
		return $this->render('view', [
			'model' => $this->findModel(),
		]);
	}

	/**
	 * Finds the Users model for logged user.
	 * If the model is not found, a 404 HTTP exception will be thrown.
	 * @return Users the loaded model
	 * @throws NotFoundHttpException if the model cannot be found
	 */
	protected function findModel()
	{
		if (($model = User::find()->where(['id' => Yii::$app->user->id])->one()) !== null) {
			return $model;
		}

		throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
	}

	/**
	 * Updates an current Users model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @return mixed
	 * @throws NotFoundHttpException if the model cannot be found
	 */
	public function actionUpdate()
	{
		$model = $this->findModel();
		$model->scenario = 'profile';

		if ($model->load(Yii::$app->request->post())) {
			$model->username = $model->email;
			if ($model->save()) {
				$this->successAlert = Yii::t('app', 'Профиль успешно обновлен');
				return $this->redirect(['view']);
			}
		}

		return $this->render('update', [
			'model' => $model,
		]);
	}

	/**
	 * Updates an existing User model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @return mixed
	 */
	public function actionChangePassword()
	{
		$model = $this->findModel();
		$model->scenario = 'change-password';

		if ($model->load(Yii::$app->request->post())) {
			if ($model->validate()) {
				$model->setPasswordHash($model->password);
				if ($model->save(false)) {
					$this->successAlert = Yii::t('app', 'Пароль обновлен');
					return $this->redirect(['view']);
				}
			}
		} else {
			$model->password = '';
		}

		return $this->render('change-password', [
			'model' => $model,
		]);
	}
}
