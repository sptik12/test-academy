<?php

use common\helpers\Html;
use common\widgets\ActiveForm;
use common\widgets\FormCard;

/* @var $this yii\web\View */
/* @var $model common\models\User */

$this->title = Yii::t('app', 'Изменить пароль');

$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Пользователи'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->fullName, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Изменить пароль');

$form = ActiveForm::begin(['type' => ActiveForm::TYPE_HORIZONTAL]);

FormCard::begin([
	'icon' => Html::ICON_EDIT,
	'footer' => Html::a(Yii::t('app', 'Назад'), $route, ['class' => 'btn btn-default'])
		. Html::submitButton(Yii::t('app', 'Сохранить'), ['class' => 'btn btn-primary ml-2'])
]);

echo $form->field($model, 'password')->passwordInput(['maxlength' => true, 'autocomplete' => 'off']);
echo $form->field($model, 'password_repeat')->passwordInput(['maxlength' => true, 'autocomplete' => 'off']);

FormCard::end();

ActiveForm::end();
