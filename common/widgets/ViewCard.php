<?php

namespace common\widgets;

/**
 * @inheritdoc
 */
class ViewCard extends Card
{
	/**
	 * @inheritdoc
	 */
	public $headerOptions = ['class' => 'bg-secondary text-white'];

	/**
	 * @inheritdoc
	 */
	public $bodyOptions = ['style' => 'padding: 0;'];

	/**
	 * @inheritdoc
	 */
	public $collapse = true;
	
	/**
	 * @var array
	 */
	public $linkOptions = ['class' => 'text-white'];
}
