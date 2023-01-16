<?php

use common\helpers\Html;
use common\widgets\ActiveForm;
use common\widgets\FormCard;

/* @var $this yii\web\View */
/* @var $model common\models\Order */

$this->title = Yii::t('app', 'Изменить статус заявки');

$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Заявки'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Изменить статус');

$form = ActiveForm::begin(['type' => ActiveForm::TYPE_HORIZONTAL]);

FormCard::begin([
	'icon' => Html::ICON_EDIT,
	'footer' => Html::a(Yii::t('app', 'Назад'), $route, ['class' => 'btn btn-default'])
		. Html::submitButton(Yii::t('app', 'Сохранить'), ['class' => 'btn btn-primary ml-2'])
]);

echo $form->field($model, 'status')->dropDownList(\common\models\Order::getStatusOptions());

FormCard::end();

ActiveForm::end();
