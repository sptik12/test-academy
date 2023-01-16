<?php

use common\helpers\Html;
use common\widgets\grid\GridView;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use kartik\widgets\DatePicker;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\EventLogSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Лог событий');

$this->params['breadcrumbs'][] = $this->title;

$columns = [];

$columns[] = [
	'class' => 'common\widgets\grid\ExpandRowColumn',
	//'header' => $this->render('/filter/_button', ['route' => '/event-log']),
	'headerOptions' => ['class' => 'kv-align-bottom kv-align-left'],
	'value' => function ($model, $key, $index, $column) {
		return (count($model->eventLogChanges)) ? GridView::ROW_COLLAPSED : false;
	},
	'detailUrl' => Url::to(['view']),
];

if (Yii::$app->user->can('event-log')) {
	$columns[] = [
		'class' => 'common\widgets\grid\CheckboxColumn',
		'noSelectionMessage' => Yii::t('app', 'Нет выбраных записей'),
		'visible' => $dataProvider->totalCount,
	];
}

$columns[] = [
	'attribute' => 'created_at',
	'format' => 'datetime',
	'filterType' => GridView::FILTER_DATE_RANGE,
	'filterWidgetOptions' => ([
		'convertFormat' => true,
		'options' => ['autocomplete' => 'off'],
		'pluginOptions' => [
			'separator' => $searchModel::RANGE_SEPARATOR,
			'locale' => [
				'format' => 'Y-m-d',
			],
		],
	]),
	'filterOptions' => ['style' => 'width: 200px;'],
];

$columns[] = [
	'attribute' => 'owner_id',
	'value' => function ($data) {
		return $data->userName;
	},
	'filter' => Html::activeDropDownList(
		$searchModel,
		'owner_id',
		$searchModel->userItems,
		['class' => 'form-control', 'prompt' => Yii::t('app', 'Все')]
	),
];


$columns[] = [
	'attribute' => 'model_name',
	'value' => function ($data) {
		return $data->itemName;
	},
	'filter' => Html::activeDropDownList(
		$searchModel,
		'model_name',
		$searchModel->getPermittedModelItems(),
		['class' => 'form-control', 'prompt' => Yii::t('app', 'Все')]
	),
];

$columns[] = [
	'attribute' => 'record_id',
	'content' => function ($data) {
		return ($route = $data->recordRoute) ? Html::a($data->record_id, $route, ['title' => Yii::t('app', 'Просмотр'), 'data-pjax' => 0]) : $data->record_id;
	},
	'contentOptions' => ['class' => 'kv-align-center'],
];

$columns[] = [
	'attribute' => 'title',
	'content' => function ($data) {
		return ($route = $data->recordRoute) ? Html::a(Html::encode($data->title), $route, ['title' => Yii::t('app', 'Просмотр'), 'data-pjax' => 0]) : Html::encode($data->title);
	},
];

$columns[] = [
	'attribute' => 'action_id',
	'value' => function ($data) {
		return $data->actionName;
	},
	'filter' => Html::activeDropDownList(
		$searchModel,
		'action_id',
		$searchModel::$actions,
		['class' => 'form-control', 'prompt' => Yii::t('app', 'Все')]
	),
];

$toolbar = [];
if (Yii::$app->user->can('event-log')) {
	if ($searchModel->start_date && $searchModel->end_date && $dataProvider->getTotalCount()) {
		$toolbar[] = [
			'content' => Html::a(Html::icon(Html::ICON_DELETE) . ' '
				. Yii::t('app', 'Удалить записи за период: {start_date} - {end_date}',
					['start_date' => Yii::$app->formatter->asDate($searchModel->start_date), 'end_date' => Yii::$app->formatter->asDate($searchModel->end_date)]),
					 ArrayHelper::merge(['delete-period', 'start_date' => $searchModel->start_date, 'end_date' => $searchModel->end_date], $searchModel->getAttributes()),
				[
				'data-pjax' => 0,
				'class' => 'btn btn-outline-danger',
				'title' => Yii::t('app', 'Удалить записи'),
				'data-confirm' => Yii::t('app', 'Точно удалить записи?'),
				]),
		];
	}
	$toolbar[] = [
		'content' => Html::a(Html::icon(Html::ICON_DELETE) . ' ' . Yii::t('app', 'Удалить'), ['delete-bulk'],
			['data-pjax' => 0, 'disabled' => 1, 'class' => 'btn btn-outline-danger bulk disabled',
				'title' => Yii::t('app', 'Delete Selected Users'), 'data-confirm' => 'Точно удалить отмеченные записи?']),
	];
}
$toolbar[] = [
		'content' => Html::a(Html::icon(Html::ICON_REDO,['class' => 'mr-2']). Yii::t('app', 'Очистить фильтры'), ['index', 'clear' => 1],
			['class' => 'btn btn-outline-secondary', 'title' => Yii::t('app', 'Очистить фильтры')]),
];
$toolbar[] = '{export}';


echo GridView::widget([
	'id' => 'log-grid',
	'pjax' => true,
	'dataProvider' => $dataProvider,
	'filterModel' => $searchModel,
	'panel' => [
		'heading' => Html::icon(Html::ICON_EVENT_LOG),
	],
	'toolbar' => $toolbar,
	'exportContainer' => ['class' => 'visually-hidden'],
	'columns' => $columns,
]);
