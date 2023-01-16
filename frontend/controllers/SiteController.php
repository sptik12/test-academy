<?php

namespace frontend\controllers;

use common\models\Order;
use common\models\User;
use Yii;
use yii\web\Controller;
use yii\filters\AccessControl;

/**
 * Site controller
 */
class SiteController extends Controller
{
	/**
	 * {@inheritdoc}
	 */
	public function behaviors()
	{
		return [
			'access' => [
				'class' => AccessControl::class,
				'only' => ['index'],
				'rules' => [
					[
						'actions' => ['index', 'create-order'],
						'allow' => true,
						'roles' => ['?'],
					],
				],
			],
		];
	}

	/**
	 * {@inheritdoc}
	 */
	public function actions()
	{
		return [
			'error' => [
				'class' => \yii\web\ErrorAction::class,
			],
		];
	}

	/**
	 * Displays homepage.
	 *
	 * @return mixed
	 */
	public function actionIndex()
	{
		return $this->render('index');
	}

	/**
	 * Displays order page.
	 *
	 * @return mixed
	 */
	public function actionCreateOrder()
	{
		$model = new Order();
		$model->scenario = 'create';
		if ($model->load(Yii::$app->request->post())) {
			$model->status = Order::ORDER_STATUS_CONSIDERED;
			if ($model->validate()) {
				if ($model->save(false)) {
					Yii::$app->session->setFlash('success', 'Заявка успешно создана');
					return $this->redirect(['index']);
				}
			}
		}
		return $this->render('order', [
			'model' => $model,
		]);
	}

}
