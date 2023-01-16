<?php

use common\helpers\Html;
use common\widgets\grid\GridView;
use common\models\AuthItem;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\UserSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app','Пользователи');

$this->params['breadcrumbs'][] = $this->title;

$columns =
[
	[
		'class' => 'common\widgets\grid\CheckboxColumn',
		'noSelectionMessage' => Yii::t('app', 'Нет отмеченных записей'),
		'visible' => $dataProvider->totalCount && Yii::$app->user->can(AuthItem::PERMISSION_USER_EDIT),
		'width' => '3%'
	],
	[
		'attribute' => 'username',
		'content' => function ($data) {
			return Html::a(Html::encode($data->username), ['/user/view', 'id' => $data->id],
				['data-pjax' => 0, 'title' => Yii::t('yii', 'Просмотреть информацию')]);
		},
	],
	[
		'attribute' => 'full_name',
		'value' => function ($data) {
			return $data->fullName;
		},
	],
	[
		'attribute' => 'auth_item_names',
		'content' => function ($data) {
			return $data->authItemNames;
		},
		'filter' => Html::activeDropDownList($searchModel, 'auth_item_names',
			AuthItem::getOptions(),
			['class' => 'form-control', 'prompt' => Yii::t('app', 'Список ролей')]
		),
	],
	[
		'class' => '\kartik\grid\BooleanColumn',
		'attribute' => 'status',
		'trueLabel' => Yii::t('app', 'Aктивен'),
		'falseLabel' =>  Yii::t('app', 'Отключен'),
	],
	[
		'class' => 'common\widgets\grid\ActionColumn',
		'headerOptions' => ['class' => 'kv-align-bottom kv-align-left'],
		'template' => '{update} {change_password}',
		'buttons' => [
			'change_password' => function ($url, $model) {
				return Html::a(Html::icon(Html::ICON_LOCK, ['class' => 'ml-1']), ['change-password', 'id' => $model->id],
					['title' => Yii::t('app', 'Изменить пароль'), 'data-pjax' => '0']);
			},
		],
		'deleteOptions' => [
			'data-confirm' => Yii::t('app', 'Точно удалить пользователя?'),
		],
		'visible' => Yii::$app->user->can(AuthItem::PERMISSION_USER_EDIT),
	]
];

$toolbar = [];
if (Yii::$app->user->can(AuthItem::PERMISSION_USER_EDIT)) {
	$toolbar[] = [
		'content'=>
			Html::a(Html::icon(Html::ICON_PLUS, ['class' => 'mr-2']) . Yii::t('app', 'Добавить пользователя'),
				['create'], ['data-pjax' => 0, 'class' => 'btn btn-outline-primary', 'title' => Yii::t('app', 'Добавить пользователя')])
	];
	$toolbar[] = [
		'content'=>
			Html::a(Html::icon(Html::ICON_DELETE, ['class' => 'mr-2']) . Yii::t('app', 'Удалить'),
				['bulk-delete'], ['data-pjax' => 0, 'disabled' => 1, 'class' => 'btn btn-outline-danger bulk disabled',
					'title' => Yii::t('app', 'Удалить отмеченных'), 'data-confirm' => 'Точно удалить отмеченных пользователей?'])
	];
}
$toolbar[] = [
	'content' => Html::a(Html::icon(Html::ICON_REDO,['class' => 'mr-2']). Yii::t('app', 'Очистить фильтры'), ['index', 'clear' => 1],
		['class' => 'btn btn-outline-secondary', 'title' => Yii::t('app', 'Очистить фильтры')]),
];
$toolbar[] = '{export}';

echo GridView::widget([
	'id' => 'user-grid',
	'dataProvider' => $dataProvider,
	'filterModel' => $searchModel,
	'panel' => [
		'heading' => Html::icon(Html::ICON_USER),
	],
	'toolbar' => $toolbar,
	'exportContainer' => ['class' => 'visually-hidden'],
	'columns' => $columns,
]);
