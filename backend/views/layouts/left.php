<?php

use common\helpers\Html;
use backend\components\adminlte\widgets\Menu;
use common\models\AuthItem;

/** @var \yii\web\View $this */
?>

<aside class="main-sidebar sidebar-bg-dark sidebar-color-primary shadow">

	<div class="brand-container">
		<?= \yii\helpers\Html::a('<img class="brand-image opacity-80 shadow" src="/img/logo32x32.png" alt="Administrative Panel"><span class="brand-text fw-light">Administrative Panel</span>', Yii::$app->homeUrl, ['class' => 'brand-link']) ?>
	</div>

	<div class="sidebar">

		<?php
		Menu::$iconClassType = 'far fa';
		?>
		<nav class="mt-2">
			<?= Menu::widget(
				[
					'options' => ['class' => 'nav nav-pills nav-sidebar flex-column', 'data-lte-toggle' => 'treeview', "role" => "menu", "data-accordion" => "false"],
					'items' => [
						['label' => Yii::t('app', 'Пользователи'), 'icon' => Html::ICON_USER, 'url' => ['/user/index'], 'active' => Yii::$app->controller->id == 'user', 'visible' => Yii::$app->user->can(AuthItem::PERMISSION_USER_VIEW)],
						['label' => Yii::t('app', 'Заявки'), 'icon' => Html::ICON_PRODUCT, 'url' => ['/order/index'], 'active' => Yii::$app->controller->id == 'order', 'visible' => Yii::$app->user->can(AuthItem::PERMISSION_ORDERS)],
						['label' => Yii::t('app', 'Лог событий'), 'icon' => Html::ICON_EVENT_LOG, 'url' => ['/event-log'], 'active' => Yii::$app->controller->id == 'event-log', 'visible' => Yii::$app->user->can(AuthItem::PERMISSION_EVENT_LOG)]
					],
				]
			) ?>
		</nav>

	</div>

</aside>
