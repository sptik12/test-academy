<?php

use common\helpers\Html;
use common\widgets\ActiveForm;
use common\widgets\FormCard;

/* @var $this yii\web\View */
/* @var $model common\models\User */

$this->title = Yii::t('app', 'Сменить пароль');

$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Профиль'), 'url' => ['view']];
$this->params['breadcrumbs'][] = $this->title;

$form = ActiveForm::begin(['type' => ActiveForm::TYPE_HORIZONTAL]);

FormCard::begin([
	'icon' => Html::ICON_EDIT,
	'footer' => Html::a(Yii::t('app', 'Назад'), ['view'], ['class' => 'btn btn-default']).
	 		Html::submitButton(Yii::t('app', 'Сохранить'), ['class' => 'btn btn-primary mr-2']),
]);

echo $form->field($model, 'password')->passwordInput(['maxlength' => true, 'autocomplete' => 'off']);

echo $form->field($model, 'password_repeat')->passwordInput(['maxlength' => true, 'autocomplete' => 'off']);

FormCard::end();

ActiveForm::end();
