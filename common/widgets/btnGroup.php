<?php

namespace common\widgets;

/**
 */

use common\helpers\Html;

/**
*/
class btnGroup extends \kartik\widgets\Widget
{
	/**
	 * @var array
	 */
	public $buttons = [];

	/**
	 * @var array
	 */
	public $containerOptions = ['class' => 'text-right mb-3'];

	/**
	 * @var array
	 */
	public $groupOptions = ['style' => 'background-color:white;'];

	/**
	 * @var string
	 */
	public $groupClass = 'btn-group';

	/**
	 * @inheritdoc
	 */
	public function init()
	{
		parent::init();
		
		$this->groupOptions['role'] = 'group';
		Html::addCssClass($this->groupOptions, $this->groupClass);
		
		/** start capturing output buffer */
		ob_start();
	}

	/**
	 * @inheritdoc
	 */
	public function run()
	{
		$out = Html::beginTag('div', $this->containerOptions);
		$out .= Html::beginTag('div', $this->groupOptions);
		
		/** using both buttons and the buffer, our widget can be used both ways  */
		$buttons = implode (' ', $this->buttons) . ob_get_clean();
		
		$out .= $buttons;
		
		$out .= Html::endTag('div');
		$out .= Html::endTag('div');

		return (trim($buttons)) ? $out : '';
	}

}
