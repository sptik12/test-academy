<?php

use common\helpers\Html;
use common\widgets\grid\GridView;
use common\models\AuthItem;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\OrderSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app','Заявки');
$this->params['breadcrumbs'][] = $this->title;

$columns =
[
	[
		'class' => 'common\widgets\grid\CheckboxColumn',
		'noSelectionMessage' => Yii::t('app', 'Нет отмеченных записей'),
		'visible' => $dataProvider->totalCount && Yii::$app->user->can(AuthItem::PERMISSION_ORDERS),
		'width' => '3%'
	],
	[
		'attribute' => 'title',
		'content' => function ($data) {
			return Html::a(Html::encode($data->title), ['/order/view', 'id' => $data->id],
				['data-pjax' => 0, 'title' => Yii::t('yii', 'Просмотреть информацию')]);
		},
	],
	[
		'attribute' => 'full_name',
		'value' => function ($data) {
			return $data->fullName;
		},
	],
	'phone',
	[
		'attribute' => 'status',
		'value' => function ($data) {
			return $data->statusName;
		},
		'filter' => Html::activeDropDownList($searchModel, 'status', \common\models\Order::getStatusOptions(),
			['class' => 'form-control', 'prompt' => Yii::t('app', 'Все')])
	],
	[
		'class' => 'common\widgets\grid\ActionColumn',
		'headerOptions' => ['class' => 'kv-align-bottom kv-align-left'],
		'template' => '{update} {change_status}',
		'buttons' => [
			'change_status' => function ($url, $model) {
				return Html::a(Html::icon(Html::ICON_CREATE, ['class' => 'ml-1']),
					['change-status', 'id' => $model->id], ['title' => Yii::t('app', 'Изменить статус'), 'data-pjax' => '0']);
			},
		],
		'deleteOptions' => [
			'data-confirm' => Yii::t('app', 'Точно удалить заявку?'),
		],
		'visible' => Yii::$app->user->can(AuthItem::PERMISSION_ORDERS),
	]
];

$toolbar = [];
if (Yii::$app->user->can(AuthItem::PERMISSION_ORDERS)) {
	$toolbar[] = [
		'content'=>
			Html::a(Html::icon(Html::ICON_PLUS, ['class' => 'mr-2']) . Yii::t('app', 'Добавить заявку'), ['create'],
				['data-pjax' => 0, 'class' => 'btn btn-outline-primary', 'title' => Yii::t('app', 'Добавить заявку')])
	];
	$toolbar[] = [
		'content'=>
			Html::a(Html::icon(Html::ICON_DELETE, ['class' => 'mr-2']) . Yii::t('app', 'Удалить'), ['bulk-delete'],
				['data-pjax' => 0, 'disabled' => 1, 'class' => 'btn btn-outline-danger bulk disabled', 'title' => Yii::t('app', 'Удалить отмеченных'),
					'data-confirm' => 'Точно удалить отмеченные заявки?'])
	];
}
$toolbar[] = [
	'content' => Html::a(Html::icon(Html::ICON_REDO,['class' => 'mr-2']). Yii::t('app', 'Очистить фильтры'), ['index', 'clear' => 1],
		['class' => 'btn btn-outline-secondary', 'title' => Yii::t('app', 'Очистить фильтры')]),
];
$toolbar[] = '{export}';

echo GridView::widget([
	'id' => 'order-grid',
	'dataProvider' => $dataProvider,
	'filterModel' => $searchModel,
	'panel' => [
		'heading' => Html::icon(Html::ICON_PRODUCT),
	],
	'toolbar' => $toolbar,
//	'exportContainer' => ['class' => 'visually-hidden'],
	'columns' => $columns,
]);
