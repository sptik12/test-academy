<?php

namespace console\controllers;

use Yii;
use yii\console\Controller;

class RbacController extends Controller
{
	public function actionInit()
	{
		$auth = Yii::$app->authManager;

		$admin = $auth->createRole('admin');
		$admin->description = 'Администратор';
		$auth->add($admin);

		$manager = $auth->createRole('manager');
		$manager->description = 'Менеджер';
		$auth->add($manager);

		// add "users" permission
		$usersView = $auth->createPermission('users-view');
		$usersView->description = 'Просмотр пользователей';
		$auth->add($usersView);

		// add "users" permission
		$usersEdit = $auth->createPermission('users-edit');
		$usersEdit->description = 'Управление пользователями';
		$auth->add($usersEdit);

		// add "orders" permission
		$orders = $auth->createPermission('orders');
		$orders->description = 'Управление заявками';
		$auth->add($orders);

		// add "event-log" permission
		$eventLog = $auth->createPermission('event-log');
		$eventLog->description = 'Просмотр лога событий';
		$auth->add($eventLog);

		$auth->addChild($admin, $usersView);
		$auth->addChild($admin, $usersEdit);
		$auth->addChild($admin, $orders);
		$auth->addChild($admin, $eventLog);

		$auth->addChild($manager, $usersView);
		$auth->addChild($manager, $orders);

	}


}
