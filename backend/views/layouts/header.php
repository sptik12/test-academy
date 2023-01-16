<?php

use yii\helpers\Html;

$title = (Yii::$app->user->isGuest) ? 'Гость' : Html::encode(Yii::$app->user->identity->username);
?>

<nav class="main-header navbar navbar-expand navbar-light">
	<div class="container-fluid">
		<!-- Left navbar links -->
		<ul class="navbar-nav">
			<li class="nav-item">
				<a class="nav-link" data-lte-toggle="sidebar-full" href="#" role="button"><i class="fas fa-bars" title="Меню"></i></a>
			</li>
		</ul>

		<!-- Right navbar links -->
		<ul class="navbar-nav ms-auto">
			<li class="nav-item dropdown user-menu">
				<a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
					<img src="/img/user2-160x160.jpg" class="user-image img-circle shadow" alt="<?=$title ?>">
					<span class="d-none d-md-inline"><?=$title ?></span>
				</a>
				<ul class="dropdown-menu dropdown-menu-lg dropdown-menu-end">
					<!-- User image -->
					<li class="user-header bg-primary">
						<img src="/img/user2-160x160.jpg" class="img-circle shadow" alt="<?=Yii::t('app','Фото') ?>">
						<p>
							<?= $title ?>
						</p>
					</li>
					<!-- Menu Footer-->
					<?php if (!Yii::$app->user->isGuest) { ?>
					<li class="user-footer">
						<?= Html::a(
							Yii::t('app', 'Профиль'),
							['/profile'],
							['class' => 'btn btn-primary btn-flat']
						) ?>
						<?= Html::a(
							'Выйти',
							['/site/logout'],
							['data-method' => 'post', 'class' => 'btn btn-default btn-flat float-end']
						) ?>
					</li>
					<?php } ?>
				</ul>
			</li>
		</ul>
	</div>
</nav>
