<?php

namespace common\widgets;

use Yii;
use yii\helpers\ArrayHelper;
use common\helpers\Html;
use yii\web\View;

/**
 * CheckboxX
 */
class CheckboxX extends \kartik\checkbox\CheckboxX
{

	/**
	 * @inheritdoc
	 */
	public function init()
	{
		$pluginOptions = ['threeState' => false, 'iconChecked' => Html::icon('check')];

		$this->pluginOptions = ArrayHelper::merge($pluginOptions, $this->pluginOptions);

		parent::init();
	}

	/**
	 * @inheritdoc
	 */
	public function registerAssets()
	{
		parent::registerAssets();
		$this->getView()->registerJs("jQuery('.cbx').on( 'click keyup', '.cbx-icon', function(e) { e.stopPropagation(); $(this).closest('.cbx-container').find('input').trigger('click'); } );", View::POS_READY, 'common-checkboxx-' . $this->id);
	}
}