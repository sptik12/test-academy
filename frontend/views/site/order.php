<?php

/** @var yii\web\View $this */
/** @var yii\bootstrap5\ActiveForm $form */

/** @var \common\models\Order $model */

use yii\bootstrap5\Html;
use yii\bootstrap5\ActiveForm;
use yii\helpers\ArrayHelper;

$this->title = 'Создать заявку';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-contact">
	<h1><?= Html::encode($this->title) ?></h1>

	<div class="row">
		<div class="col-lg-5">
			<?php $form = ActiveForm::begin(['id' => 'order-form']); ?>

			<?= $form->field($model, 'title')->textInput(['autofocus' => true]) ?>

			<?= $form->field($model, 'first_name')->textInput(['maxlength' => true]) ?>

			<?= $form->field($model, 'last_name')->textInput(['maxlength' => true]) ?>

			<?= $form->field($model, 'phone')->textInput(['maxlength' => true]) ?>

			<?= $form->field($model, 'category_id')->dropDownList(ArrayHelper::map(\common\models\Category::find()->all(), 'id', 'name'), ['prompt' => Yii::t('app', 'Укажите товар')]) ?>

			<?= $form->field($model, 'price') ?>

			<?= $form->field($model, 'comment')->textarea(['rows' => 3]) ?>

			<div class="form-group">
				<?= Html::submitButton(Yii::t('app','Создать заявку'), ['class' => 'btn btn-primary', 'name' => 'contact-button']) ?>
			</div>

			<?php ActiveForm::end(); ?>
		</div>
	</div>

</div>
