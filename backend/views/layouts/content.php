<?php

use yii\bootstrap5\Breadcrumbs;
use backend\components\adminlte\widgets\Alert;

?>
<div class="content-wrapper">
	<div class="content-header">
		<div class="container-fluid">
			<div class="row mb-2">
				<div class="col-sm-6">
					<?php if (isset($this->blocks['content-header'])) { ?>
						<h1><?= $this->blocks['content-header']; ?></h1>
					<?php } else { ?>
						<h1>
							<?php
							if ($this->title !== null) {
								echo \yii\helpers\Html::encode($this->title);
							} else {
								echo \yii\helpers\Inflector::camel2words(
									\yii\helpers\Inflector::id2camel($this->context->module->id)
								);
								echo ($this->context->module->id !== \Yii::$app->id) ? '<small>Module</small>' : '';
							} ?>
						</h1>
					<?php } ?>
				</div>

				<div class="col-sm-6">
					<?= Breadcrumbs::widget([
						'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
						'options' => [
							'class' => 'float-sm-end'
						]
					]); ?>
				</div>
			</div>
		</div>
	</div>

	<div class="content">
		<div class="container-fluid">
			<div class="row">
				<?= Alert::widget(); ?>
				<div class="col-12">
					<div class="card">
						<div class="card-body">
							<?= $content ?>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<footer class="main-footer">
	<div class="float-end d-none d-sm-inline">
	</div>
	<strong>Copyright &copy; <?php echo date("Y"); ?> <a href="http://www.test.com">Test</a>.</strong>
	&nbsp;<?php echo Yii::t('app', 'All rights reserved.') ?>
</footer>
