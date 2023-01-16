<?php

namespace common\widgets;

/**
 */

use common\helpers\Html;

/**
 * Renders a card.
 * Widget can be used either way:
 * @example
 * ```
 * <?php
 *     echo Card::widget([
 *        'headerOptions' => ['class' => 'bg-secondary'],
 *        'title' => 'Top card',
 *        'body' => $aBunchOfHtml,
 *     ]);
 *
 *      Card::begin([
 *        'containerOptions' => ['class' => 'border border-secondary'],
 *        'headerOptions' => ['class' => 'bg-dark text-white'],
 *        'title' => 'Top card',
 *     ]);
 * ?>
 * <p class="card-text">
 * <?= CardlWidget::end();
 */
class Card extends \kartik\widgets\Widget
{

	/**
	 * @var string icon name before title
	 */
	public $icon;

	/**
	 * @var string
	 */
	public $title;

	/**
	 * @var string
	 */
	public $addOn;

	/**
	 * @var string
	 */
	public $body = '';

	/**
	 * @var string
	 */
	public $footer;

	/**
	 * @var array
	 */
	public $containerOptions;

	/**
	 * @var array
	 */
	public $iconOptions = ['class' => 'mr-3'];

	/**
	 * @var array
	 */
	public $titleOptions;

	/**
	 * @var bool if collapse enabled
	 */
	public $collapse = true;

	/**
	 * @var bool if show collapsed area by default
	 */
	public $show = true;

	/**
	 * @var array
	 */
	public $headerOptions = ['class' => 'bg-light'];

	/**
	 * @var string
	 */
	public $header;

	/**
	 * @var string
	 */
	public $bodyTag = 'div';

	/**
	 * @var string
	 */
	public $bodyClass = 'card-body';

	/**
	 * @var array
	 */
	public $bodyOptions;

	/**
	 * @var array
	 */
	public $footerOptions;

	/**
	 * @var array
	 */
	public $collapsedOptions;

	/**
	 * @var array
	 */
	public $linkOptions;

	/**
	 * @inheritdoc
	 */
	public function init()
	{
		parent::init();
		if (empty($this->containerOptions)) {
			Html::addCssClass($this->containerOptions, 'card-default');
		}
		Html::addCssClass($this->containerOptions, 'card');
		Html::addCssClass($this->headerOptions, 'card-header');
		Html::addCssClass($this->titleOptions, 'card-title');
		Html::addCssClass($this->bodyOptions, $this->bodyClass);
		Html::addCssClass($this->footerOptions, 'card-footer');

		if ($this->collapse) {
			if (!isset($this->collapsedOptions['id'])) {
				$this->collapsedOptions['id'] = $this->getId();
			}
			$this->linkOptions['href'] = '#' . $this->collapsedOptions['id'];
			$this->linkOptions['data-toggle'] = 'collapse';
			$this->linkOptions['role'] = 'button';
			$this->linkOptions['aria-controls'] = $this->collapsedOptions['id'];
			$this->linkOptions['aria-expanded'] = ($this->show) ? 'true' : 'false';

			Html::addCssClass($this->collapsedOptions, 'collapse');
			if ($this->show) {
				Html::addCssClass($this->collapsedOptions, 'show');
			} else {
				Html::addCssClass($this->linkOptions, 'collapsed');
			}
		}
		/** start capturing output buffer */
		ob_start();
	}

	/**
	 * @inheritdoc
	 */
	public function run()
	{
		$out = Html::beginTag('div', $this->containerOptions);
		/** Heading & title, if useHeader set */
		if (!empty($this->title) || $this->icon || !empty($this->header)) {
			$out .= Html::beginTag('div', $this->headerOptions);
			$out .= Html::beginTag('h5', $this->titleOptions);
			if ($this->collapse) {
				$out .= Html::beginTag('a', $this->linkOptions);
			}
			if ($this->icon) {
				$out .= Html::icon($this->icon, $this->iconOptions);
			}
			$out .= $this->title;
			if ($this->collapse) {
				$out .= Html::endTag('a');
			}
			if ($this->addOn) {
				$out .= $this->addOn;
			}
			$out .= Html::endTag('h5');

			if (!empty($this->header)){
				$out .= $this->header;
			}

			$out .= Html::endTag('div');
		}

		if ($this->collapse) {
			//start collapsed area
			$out .= Html::beginTag('div', $this->collapsedOptions);
		}

		/** Body with optional title */
		$out .= Html::beginTag($this->bodyTag, $this->bodyOptions);

		/** using both body and the buffer, our widget can be used both ways  */
		$out .= $this->body;
		$out .= ob_get_clean();
		$out .= Html::endTag($this->bodyTag);

		/** footer */
		if (isset($this->footer)) {
			$out .= Html::beginTag('div', $this->footerOptions);
			$out .= $this->footer;
			$out .= Html::endTag('div');
		}
		if ($this->collapse) {
			//end collapsed
			$out .= Html::endTag('div');
		}
		$out .= Html::endTag('div');
		return $out;
	}
}
