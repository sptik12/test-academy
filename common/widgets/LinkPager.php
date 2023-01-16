<?php

namespace common\widgets;

use Yii;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

/*
 * Items per Page on Grid footer
 */

class LinkPager extends \yii\bootstrap5\LinkPager
{
	/**
	 * @var string
	 */
	public $pageSizeLabel = 'Записей на странице:';

	/**
	 * @var array
	 */
	public $pageSizeList = [10, 25, 50, 100];

	/**
	 * @var string
	 */
	public $pagerLayout = '<div class="row"><div class="col-md-auto">{pageButtons}</div> <div class="col-md-auto">{pageSizeList}</div></div>';

	/**	 * @inheritdoc
	 */
	public function run(): string
	{
		if ($this->registerLinkTags) {
			$this->registerLinkTags();
		}

		return preg_replace_callback("/{(\\w+)}/", function ($matches) {
			$sub_section_name = $matches[1];
			$sub_section_content = $this->renderSection($sub_section_name);

			return $sub_section_content === false ? $matches[1] : $sub_section_content;
		}, $this->pagerLayout);
	}

	/**
	 *
	 */
	protected function renderSection($name)
	{
		switch ($name) {
			case 'pageButtons':
				// Call inherited renderPageButtons() method
				return $this->renderPageButtons();
			case 'pageSizeList':
				// Render sub section, page size dropDownList
				return $this->renderPageSizeList();
			default:
				return false;
		}
	}

	/**
	 *
	 */
	private function renderPageSizeList()
	{
		$currentPageSize = $this->pagination->getPageSize();

		// Push current pageSize to $this->pageSizeList,
		// unique to avoid duplicating
		if (!in_array($currentPageSize, $this->pageSizeList)) {
			array_unshift($this->pageSizeList, $currentPageSize);
			$this->pageSizeList = array_unique($this->pageSizeList);

			// Sort
			sort($this->pageSizeList, SORT_NUMERIC);
		}

		$min = reset($this->pageSizeList);

		$totalCount = $this->pagination->totalCount;
		if ($totalCount <= $min) {
			return '';
		}

		$buttons = [Html::tag('li', Html::tag('span', Yii::t('app', $this->pageSizeLabel), ['class' => 'page-link']), ['class' => 'page-item ' . $this->firstPageCssClass . ' ' . $this->disabledPageCssClass])];

		foreach ($this->pageSizeList as $value) {
			if ($value == $currentPageSize) {
				$buttons[] = Html::tag('li', Html::tag('span', $value, $this->linkOptions), ['class' => 'page-item ' . $this->activePageCssClass]);
			} else {
				$buttons[] = Html::tag('li', Html::a($value, $this->pagination->createUrl(0, $value), $this->linkOptions), $this->linkContainerOptions);
			}
		}

		return Html::tag('ul', implode("\n", $buttons), $this->listOptions);
	}
}
