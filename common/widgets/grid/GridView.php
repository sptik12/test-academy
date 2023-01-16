<?php

namespace common\widgets\grid;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * Kartik GridView
 */
class GridView extends \kartik\grid\GridView
{
	/**
	 * @var string
	 */
	public $panelType = 'secondary';

	/**
	 * @inheritdoc
	 */
	public $responsive = true;

	/**
	 * @inheritdoc
	 */
	public $condensed = true;

	/**
	 * @inheritdoc
	 */
	public $bordered = false;

	/**
	 * @inheritdoc
	 */
	public $striped = false;

	/**
	 * @inheritdoc
	 */
	public $pjax = true;

	/**
	 * @inheritdoc
	 */
	public $toggleDataContainer = ['class' => 'btn-group ml-2'];

	/**
	 * @inheritdoc
	 */
	public $exportContainer = ['class' => 'btn-group ml-2'];

	/**
	 * @inheritdoc
	 */
	public $panelFooterTemplate = <<< HTML
    <div class="kv-panel-pager">
		 {pager}
	</div>
	<!--div class="float-right">{toggleData}</div-->
	    {footer}
    <div class="clearfix"></div>
HTML;

	/**
	 * @inheritdoc
	 */
	public $toggleDataOptions = [
		'all' => [
			//'class' => 'btn btn-outline-secondary bg-white',
		],
		'page' => [
			//'class' => 'btn btn-outline-secondary bg-white',
		],
	];

	/**
	 * Initializes the datatables widget
	 */
	public function init()
	{
		$this->pager = ArrayHelper::merge([
		//'class' => 'common\widgets\LinkPager',
				'firstPageLabel' => Yii::t('app', 'Первая'),
				'lastPageLabel' => Yii::t('app', 'Последняя'),
				'maxButtonCount' => 12,
			], $this->pager);

		// add left margin to each toolbar item
		if (!empty($this->toolbar) && is_array($this->toolbar)) {
			$toolbar = [];
			foreach ($this->toolbar as $item) {
				if (is_array($item)) {
					$item = ArrayHelper::merge([
						'options' => ['class' => 'btn-group ml-2']
					], $item);
				}
				$toolbar[] = $item;
			}
			$this->toolbar = $toolbar;
		}

		// add panel type
		if (!empty($this->panel) && is_array($this->panel) && !array_key_exists('type', $this->panel)) {
			$this->panel['type'] = $this->panelType;
			if ($this->dataProvider->totalCount == 0) {
				$this->panel['footer'] = false;//hide if no records
			}
		}

		parent::init();
	}
}
