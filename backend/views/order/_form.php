<?php

use common\helpers\Html;
use common\widgets\ActiveForm;
use common\widgets\FormCard;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $model common\models\User */
/* @var $form yii\widgets\ActiveForm */

$form = ActiveForm::begin();

FormCard::begin([
	'icon' => Html::ICON_PRODUCT,
	'footer' => Html::a(Yii::t('app', 'Назад'), $route, ['class' => 'btn btn-default'])
		. Html::submitButton(Yii::t('app', 'Сохранить'), ['class' => 'btn btn-primary ml-2']),
	'footerOptions' => ['class' => 'text-right'],
]);

echo Html::beginTag('div', ['class' => 'form-row']);
	echo Html::beginTag('div', ['class' => 'col-md-6']);
	echo $form->field($model, 'title')->textInput(['maxlength' => true]);
	echo Html::endTag('div');
echo Html::endTag('div');

echo Html::beginTag('div', ['class' => 'form-row']);
echo Html::beginTag('div', ['class' => 'col-md-6']);
echo $form->field($model, 'status')->dropDownList(\common\models\Order::getStatusOptions());
echo Html::endTag('div');
echo Html::endTag('div');

echo Html::beginTag('div', ['class' => 'form-row']);
	echo Html::beginTag('div', ['class' => 'col-md-6']);
	echo $form->field($model, 'first_name')->textInput(['maxlength' => true]);
	echo Html::endTag('div');
echo Html::endTag('div');

echo Html::beginTag('div', ['class' => 'form-row']);
	echo Html::beginTag('div', ['class' => 'col-md-6']);
	echo $form->field($model, 'last_name')->textInput(['maxlength' => true]);
	echo Html::endTag('div');
echo Html::endTag('div');

echo Html::beginTag('div', ['class' => 'form-row']);
	echo Html::beginTag('div', ['class' => 'col-md-6']);
	echo $form->field($model, 'phone')->textInput(['maxlength' => true]);
	echo Html::endTag('div');
echo Html::endTag('div');

echo Html::beginTag('div', ['class' => 'form-row']);
	echo Html::beginTag('div', ['class' => 'col-md-6']);
	echo $form->field($model, 'category_id')->dropDownList(ArrayHelper::map(\common\models\Category::find()->all(), 'id', 'name'), ['prompt' => Yii::t('app', 'Укажите товар')]);
	echo Html::endTag('div');
echo Html::endTag('div');

echo Html::beginTag('div', ['class' => 'form-row']);
	echo Html::beginTag('div', ['class' => 'col-md-6']);
	echo $form->field($model, 'price')->textInput(['maxlength' => true]);
	echo Html::endTag('div');
echo Html::endTag('div');

echo Html::beginTag('div', ['class' => 'form-row']);
	echo Html::beginTag('div', ['class' => 'col-md-6']);
	echo $form->field($model, 'comment')->textarea(['rows' => 3]);
	echo Html::endTag('div');
echo Html::endTag('div');

FormCard::end();

ActiveForm::end();
