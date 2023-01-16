<?php

namespace common\widgets\grid;

 
/**
 * The Kartik ExpandRowColumn
 */
class ExpandRowColumn extends \kartik\grid\ExpandRowColumn
{
	/**
	 * {@inheritdoc}
	 */
	public $header = '';
	
	/**
	 * @inheritdoc
	 */
	public $width = '20px';
	
	/**
	 * @inheritdoc
	 */
	public $detailRowCssClass = "bg-white";
	
	/**
	 * @inheritdoc
	 */
	public $expandOneOnly = true;
	
	/**
	 * @inheritdoc
	 */
	public $vAlign = GridView::ALIGN_TOP;
	
	/**
	 * @inheritdoc
	 */
	public $allowBatchToggle = false;
	
	/**
	 * @inheritdoc
	 */
	public $enableRowClick = true;

	/**
	 * @inheritdoc
	 */
	public $contentOptions = ['style' => 'font-size: 1em;'];

	/**
	 * @inheritdoc
	 */
	public $headerOptions = ['style' => 'font-size: 1em;'];

    /**
     * @var array list of tags in the row on which row click will be disabled.
     */
    public $rowClickExcludedTags = ['a', 'button', 'input', 'span', 'i'];

}
