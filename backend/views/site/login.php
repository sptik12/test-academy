<?php

/** @var yii\web\View $this */
/** @var yii\bootstrap5\ActiveForm $form */

/** @var \backend\models\LoginForm $model */

use yii\bootstrap5\ActiveForm;
use yii\bootstrap5\Html;

$this->title = 'Логин';
?>
<div class="site-login">
	<div class="mt-5 offset-lg-3 col-lg-6">
		<h1><?= Html::encode($this->title) ?></h1>
		<?php $form = ActiveForm::begin(['id' => 'login-form']); ?>

		<?= $form->field($model, 'username')->textInput(['autofocus' => true, 'placeholder' => $model->getAttributeLabel('username')])->label(false) ?>

		<?= $form->field($model, 'password')->passwordInput(['placeholder' => $model->getAttributeLabel('password')])->label(false) ?>

		<?= $form->field($model, 'rememberMe')->checkbox() ?>

		<div class="form-group">
			<?= Html::submitButton('Логин', ['class' => 'btn btn-primary btn-block', 'name' => 'login-button']) ?>
		</div>

		<?php ActiveForm::end(); ?>
	</div>
</div>
