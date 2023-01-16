<?php
$params = array_merge(
	require(__DIR__ . '/../../common/config/params.php'),
	require(__DIR__ . '/../../common/config/params-local.php'),
	require(__DIR__ . '/params.php'),
	require(__DIR__ . '/params-local.php')
);

return [
	'vendorPath' => '/app/vendor',
	'id' => 'app-backend',
	'basePath' => dirname(__DIR__),
	'name' => 'Административная Панель',
	'controllerNamespace' => 'backend\controllers',
	'bootstrap' => ['log'],
	'modules' => [
		'gridview' => [
			'class' => '\kartik\grid\Module'
		],
	],
	'components' => [
		'request' => [
			'enableCsrfValidation' => false,
			'cookieValidationKey' => 'q3Djek37_XItAfREFEiOCQO0LEkYOlQS',
		],
		'user' => [
			'identityClass' => 'common\models\User',
			'enableAutoLogin' => true,
		],
		'authManager' => [
			'class' => 'yii\rbac\DbManager'
		],
		'log' => [
			'traceLevel' => YII_DEBUG ? 3 : 0,
			'targets' => [
				[
					'class' => 'yii\log\FileTarget',
					'levels' => ['error', 'warning'],
				],

			],
		],
		'errorHandler' => [
			'errorAction' => 'site/error',
		],

		'urlManager' => [
			'enablePrettyUrl' => true,
			'enableStrictParsing' => false,
			'showScriptName' => false,
			'suffix' => '.html',
			'rules' => [
				// Default rules
				'<controller:\w+>/<id:\d+>' => '<controller>/view',
				'<controller:\w+>/<action:\w+>/<id:\d+>' => '<controller>/<action>',
				'<controller:\w+>/<action:\w+>' => '<controller>/<action>',
			],
		],
		'formatter' => [
			'class' => 'yii\i18n\Formatter',
			'nullDisplay' => '',
		],
	],
	'params' => $params,
];
