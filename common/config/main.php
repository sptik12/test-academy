<?php
return [
	'language' => 'ru-RU',
	'aliases' => [
		'@bower' => '@vendor/bower-asset',
		'@npm' => '@vendor/npm-asset',
	],
	'components' => [
		'db' => [
			'class' => 'yii\db\Connection',
			'charset' => 'utf8',
		],
		'cache' => [
			'class' => 'yii\caching\FileCache',
		],
		'image' => [
			'class' => 'yii\image\ImageDriver',
			'driver' => 'GD',  //GD or Imagick
		],
		'authManager' => [
			'class' => 'yii\rbac\DbManager',
			'defaultRoles' => ['registered'],
			'cache' => 'cache' //enable caching
		],
		'i18n' => [
			'translations' => [
				'app' => [
					'class' => 'yii\i18n\PhpMessageSource',
					'basePath' => '@common/messages',
					'sourceLanguage' => 'ru-RU',
					'forceTranslation' => true,
				],
			],
		],
		'formatter' => [
			'dateFormat' => 'dd.MM.y',
			'timeFormat' => 'H:mm',
			'datetimeFormat' => 'dd.MM.y H:mm',
			//	'defaultTimeZone' => 'Europe/Moscow',
			'nullDisplay' => '&nbsp;',
			'decimalSeparator' => ',',
			'thousandSeparator' => ' ',
			'locale' => 'ru-RU'
		],
	],
	'modules' => [
	]

];
