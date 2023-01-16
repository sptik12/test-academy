<?php
return [
	'components' => [
		'db' => [
			'class' => 'yii\db\Connection',
			'dsn' => 'mysql:host=db;dbname=test',
			'username' => 'dev',
			'password' => 'dev',
			'charset' => 'utf8',
		],
	],
];
