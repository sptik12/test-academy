<?php

use common\helpers\Html;
use common\widgets\ActiveForm;
use common\models\AuthItem;
use common\widgets\FormCard;
use common\widgets\CheckboxX;

/* @var $this yii\web\View */
/* @var $model common\models\User */
/* @var $form yii\widgets\ActiveForm */

$form = ActiveForm::begin();

FormCard::begin([
	'icon' => Html::ICON_USER_EDIT,
	'footer' => Html::a(Yii::t('app', 'Назад'), $route, ['class' => 'btn btn-default'])
		. Html::submitButton(Yii::t('app', 'Сохранить'), ['class' => 'btn btn-primary ml-2']),
	'footerOptions' => ['class' => 'text-right'],
]);

echo Html::beginTag('div', ['class' => 'form-row']);
	echo Html::beginTag('div', ['class' => 'col-md-6']);
	echo $form->field($model, 'email')->textInput(['maxlength' => true])->label($model->getAttributeLabel('username'));
	echo Html::endTag('div');
echo Html::endTag('div');

if ($model->isNewRecord) {
	echo Html::beginTag('div', ['class' => 'form-row']);
		echo Html::beginTag('div', ['class' => 'col-md-6']);
		echo $form->field($model, 'password')->passwordInput(['maxlength' => true, 'autocomplete' => 'off']);
		echo Html::endTag('div');
		echo Html::beginTag('div', ['class' => 'col-md-6']);
		echo $form->field($model, 'password_repeat')->passwordInput(['maxlength' => true, 'autocomplete' => 'off']);
		echo Html::endTag('div');
	echo Html::endTag('div');
}

echo Html::beginTag('div', ['class' => 'form-row']);
	echo Html::beginTag('div', ['class' => 'col-md-6']);
	echo $form->field($model, 'first_name')->textInput(['maxlength' => true]);
	echo Html::endTag('div');
	echo Html::beginTag('div', ['class' => 'col-md-6']);
	echo $form->field($model, 'last_name')->textInput(['maxlength' => true]);
	echo Html::endTag('div');
echo Html::endTag('div');

echo Html::beginTag('div', ['class' => 'form-row']);
	echo Html::beginTag('div', ['class' => 'col-md-6']);
	//echo $form->field($model, 'auth_item_names')->checkboxList(AuthItem::getOptions(), []);
	echo $form->field($model, 'auth_item_names')->dropDownList(AuthItem::getOptions(), ['multiple' => true]);
	echo Html::endTag('div');
echo Html::endTag('div');

echo Html::beginTag('div', ['class' => 'form-row']);
	echo Html::beginTag('div', ['class' => 'col-md-6']);
	echo $form->field($model, 'status', ['showLabels' => false])->widget(CheckboxX::className(), ['autoLabel' => true] );
	echo Html::endTag('div');
echo Html::endTag('div');

FormCard::end();

ActiveForm::end();
