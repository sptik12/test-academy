<?php

use common\helpers\Html;
use common\widgets\DetailView;
use common\widgets\ViewCard;
use common\widgets\btnGroup;

/* @var $this yii\web\View */
/* @var $model common\models\User */

$this->title = $model->fullName;

$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Пользователи'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

echo btnGroup::widget([
	'buttons' => [
		Html::a(Html::icon(Html::ICON_USER, ['class' => 'mr-2']) . Yii::t('app', 'Пользователи'), ['index'], ['class' => 'btn btn-outline-secondary']),
		($count = $model->getEventLogsCount()) ? Html::a(Html::icon(Html::ICON_EVENT_LOG, ['class' => 'mr-2']) . Yii::t('app', 'Лог изменений ({count})', ['count' => $count]), $model->getLogRoute(), ['class' => 'btn btn-outline-secondary',]) : '',
		Html::a(Html::icon(Html::ICON_UPDATE, ['class' => 'mr-2']) . Yii::t('app', 'Редактировать'), ['update', 'id' => $model->id], ['class' => 'btn btn-outline-primary']),
		Html::a(Html::icon(Html::ICON_LOCK, ['class' => 'mr-2']) . Yii::t('app', 'Изменить пароль'), ['change-password', 'id' => $model->id], ['class' => 'btn btn-outline-dark']),
		Html::a(Html::icon(Html::ICON_DELETE, ['class' => 'mr-2']) . Yii::t('app', 'Удалить'), ['delete', 'id' => $model->id], [
			'class' => 'btn btn-outline-danger',
			'data' => [
				'confirm' => Yii::t('app', 'Точно удалить пользователя?'),
				'method' => 'post',
			],
		]),
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
		[
			'attribute' => 'auth_item_names',
			'value' => $model->authItemNames,
		],
		[
			'attribute' => 'status',
			'format' => 'boolean',
		],
		'created_at:datetime',
		'updated_at:datetime',
	],
]);

ViewCard::end();

