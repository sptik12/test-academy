<?php

namespace backend\components\adminlte;

use yii\web\AssetBundle;

/**
 * AdminLte AssetBundle
 */
class AdminLteAsset extends AssetBundle
{
	public $sourcePath = '@vendor/mgatner/adminlte4/dist';

	public $css = [
		'css/adminlte.css',
	];
	public $js = [
		'js/adminlte.js'
	];
	public $depends = [
		'rmrevin\yii\fontawesome\AssetBundle',
		'yii\web\YiiAsset',
		'yii\bootstrap5\BootstrapAsset',
		'yii\bootstrap5\BootstrapPluginAsset',
	];
}
