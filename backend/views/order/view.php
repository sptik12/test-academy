<?php

use common\helpers\Html;
use common\widgets\DetailView;
use common\widgets\ViewCard;
use common\widgets\btnGroup;

/* @var $this yii\web\View */
/* @var $model common\models\Order */

$this->title = $model->title;

$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Заявки'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

echo btnGroup::widget([
	'buttons' => [
		Html::a(Html::icon(Html::ICON_USER, ['class' => 'mr-2']) . Yii::t('app', 'Заявки'), ['index'], ['class' => 'btn btn-outline-secondary']),
		($count = $model->getEventLogsCount()) ? Html::a(Html::icon(Html::ICON_EVENT_LOG, ['class' => 'mr-2']) . Yii::t('app', 'Лог изменений ({count})', ['count' => $count]), $model->getLogRoute(), ['class' => 'btn btn-outline-secondary',]) : '',
		Html::a(Html::icon(Html::ICON_UPDATE, ['class' => 'mr-2']) . Yii::t('app', 'Редактировать'), ['update', 'id' => $model->id], ['class' => 'btn btn-outline-primary']),
		Html::a(Html::icon(Html::ICON_LOCK, ['class' => 'mr-2']) . Yii::t('app', 'Изменить статус'), ['change-password', 'id' => $model->id], ['class' => 'btn btn-outline-dark']),
		Html::a(Html::icon(Html::ICON_DELETE, ['class' => 'mr-2']) . Yii::t('app', 'Удалить'), ['delete', 'id' => $model->id], [
			'class' => 'btn btn-outline-danger',
			'data' => [
				'confirm' => Yii::t('app', 'Точно удалить заявку?'),
				'method' => 'post',
			],
		]),
	]
]);

ViewCard::begin([
	'icon' => Html::ICON_PRODUCT,
]);

echo DetailView::widget([
	'model' => $model,
	'attributes' => [
		'title',
		[
			'attribute' => 'fullName',
		],
		'phone',
		[
			'attribute' => 'category_id',
			'value' => \yii\helpers\ArrayHelper::getValue($model->category, 'name')
		],
		'price',
		[
			'attribute' => 'status',
			'value' => $model->getStatusName()
		],
		'comment',
		'created_at:datetime',
		'updated_at:datetime',
	],
]);

ViewCard::end();

