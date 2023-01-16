<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace common\widgets;

use Yii;

/*
 * Kartik DetailView extension
 */

class DetailView extends \kartik\detail\DetailView
{

	/**
	 * @inheritdoc
	 */
	public $enableEditMode = false;

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
	public $labelColOptions = ['style' => 'width: 25%'];

}
