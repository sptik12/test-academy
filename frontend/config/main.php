<?php
$params = array_merge(
	require __DIR__ . '/../../common/config/params.php',
	require __DIR__ . '/../../common/config/params-local.php',
	require __DIR__ . '/params.php',
	require __DIR__ . '/params-local.php'
);

return [
	'vendorPath' => '/app/vendor',
	'id' => 'app-frontend',
	'basePath' => dirname(__DIR__),
	'bootstrap' => ['log'],
	'controllerNamespace' => 'frontend\controllers',
	'components' => [
		'request' => [
			'enableCsrfValidation' => false,
			'cookieValidationKey' => 'qcDjek37_XItAfREFEiOCQO0LEkYOlQA',
		],
		'user' => [
			'identityClass' => 'common\models\User',
			'enableAutoLogin' => true,
            'identityCookie' => ['name' => '_identity-frontend'],
		],
        'session' => [
            // this is the name of the session cookie used for login on the frontend
            'name' => 'advanced-frontend',
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
