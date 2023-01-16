<?php

namespace common\widgets;

/**
 * @inheritdoc
 */
class FormCard extends Card
{
	/**
	 * @inheritdoc
	 */
	public $headerOptions = ['class' => 'bg-dark text-white'];

	/**
	 * @inheritdoc
	 */
	public $bodyOptions = ['style' => 'padding-bottom: 0;'];

	/**
	 * @inheritdoc
	 */
	public $collapse = true;
	
	/**
	 * @var array
	 */
	public $linkOptions = ['class' => 'text-white'];
}
