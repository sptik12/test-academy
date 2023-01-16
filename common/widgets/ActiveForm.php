<?php

namespace common\widgets;

use Yii;
use yii\helpers\Json;

/**
 * ActiveForm
 */
class ActiveForm extends \kartik\widgets\ActiveForm
{
	/**
	 * @var bool whether to alert to unsaved changes
	 */
	public $livePageWarning = false;

	/**
	 * @inheritdoc
	 */
	public $formConfig = ['labelSpan' => 3];


	/**
	 * @throws \Exception
	 */
	public function init()
	{
		parent::init();

		if ($this->livePageWarning) {
			$this->getView()->registerJs("
				$('#" . $this->getId() . "').change(function(e){
					$(window).bind('beforeunload', function() { 
						return " . Json::encode(Yii::t('app', 'Данные были изменены. Вы точно хотите покинуть форму без их сохранения?')) . ";
					});
				});
				$('#" . $this->getId() . "').submit(function() {
					$(window).unbind('beforeunload');
				});
			");
		}
	}
}