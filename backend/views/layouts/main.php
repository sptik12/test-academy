<?php

use yii\helpers\Html;

/** @var \yii\web\View $this */
/** @var string $content */

$this->registerCssFile('https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700');
backend\assets\AppAsset::register($this);
?>
<?php $this->beginPage() ?>
		<!DOCTYPE html>
		<html lang="<?= Yii::$app->language ?>">
			<head>
				<meta charset="<?= Yii::$app->charset ?>" />
				<meta name="viewport" content="width=device-width, initial-scale=1">
				<?= Html::csrfMetaTags() ?>
				<title><?= Html::encode($this->title) ?></title>
				<?php $this->head() ?>
			</head>
			<body class="layout-fixed admin">
			<?php $this->beginBody() ?>
					<div class="wrapper">
						<?= $this->render(
							'header.php',
							[]
						) ?>
						<?= $this->render(
							'left.php',
							[]
						)
						?>
						<?= $this->render(
							'content.php',
							['content' => $content]
						) ?>
					</div>
			<?php $this->endBody() ?>
			</body>
		</html>
<?php $this->endPage() ?>
