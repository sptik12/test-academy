<?php

use common\helpers\Html;
use common\widgets\DetailView;
use common\widgets\ViewCard;
use common\widgets\btnGroup;

/* @var $this yii\web\View */
/* @var $model common\models\Users */

$this->title = Yii::t('app', 'Профиль');

$this->params['breadcrumbs'][] = $this->title;

echo btnGroup::widget([
	'buttons' => [
		Html::a(Html::icon(Html::ICON_UPDATE, ['class' => 'mr-2']) . Yii::t('app', 'Редактировать'), ['update'], ['class' => 'btn btn-outline-primary']),
		Html::a(Html::icon(Html::ICON_LOCK, ['class' => 'mr-2']) . Yii::t('app', 'Сменить Пароль'), ['change-password'], ['class' => 'btn btn-outline-dark']),
	]
]);

ViewCard::begin([
	'icon' => Html::ICON_USER,
]);

echo DetailView::widget([
	'model' => $model,
	'attributes' => [
		'username',
		[
			'attribute' => 'fullName',
		],
		'email:email',
		[
			'attribute' => 'auth_item_names',
			'value' => $model->authItemNames,
		],
	],
]);

ViewCard::end();
