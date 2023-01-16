<?php

namespace common\widgets\grid;

use Yii;
use yii\web\View;
use yii\helpers\ArrayHelper;

/**
 * The Kartik ActionColumn
 */
class ActionColumn extends \kartik\grid\ActionColumn
{
	/**
	 * @inheritdoc
	 */
	 public $header = '';
	 
	/**
	 * @inheritdoc
	 */
	 public $vAlign = 'top';
	/**
	 * @var string
	 */
	public $deleteClass = 'pjax-grid-column';
	
	/**
	 * @var bool wether pjax on delete enabled
	 */
	public $pjax = false;

	/**
	 * @inheritdoc
	 */
	public function init()
	{
		
		$this->contentOptions = ArrayHelper::merge([
			'class' => 'text-nowrap',
		], $this->viewOptions);
		
		$this->viewOptions = ArrayHelper::merge([
			'data-toggle'=>'tooltip',
		], $this->viewOptions);

		$this->updateOptions = ArrayHelper::merge([
			'data-toggle'=>'tooltip'
		], $this->updateOptions);

		$this->deleteOptions = ArrayHelper::merge([
			'data-toggle'=>'tooltip',
			'class' => $this->deleteClass,
			'data-pjax' => '0',
		], $this->deleteOptions);
		
		if ($this->pjax) {
			$this->registerJs();
		}
		
		parent::init();
	}

	/**
	 * Register Javascript
	 */
	public function registerJs()
	{
		if (!Yii::$app->request->isAjax) {

			$js = "$(document.body).on('click', '#" . $this->grid->getId() . " a." . $this->deleteClass . "', function(e) {
				e.preventDefault();
				var url = $(this).attr('href');
				var confirmation = $(this).data('confirm');
				if (confirmation){
					
					krajeeDialog.confirm(confirmation,
						function(confirmed){
							if (confirmed) {
								$.post(url, function(data) {
									var pjaxId = $(e.target).closest('[data-pjax-container]').attr('id');
									$.pjax.reload({container:'#' + pjaxId});
									if(data){
										krajeeDialog.alert(data);
									}
								});
							}
						}
					);
				}
				else{
					$.post(url, function(data) {
						var pjaxId = $(e.target).closest('[data-pjax-container]').attr('id');
						$.pjax.reload({container:'#' + pjaxId});
						if(data){
							krajeeDialog.alert(data);
						}
					});
				}
				return false;
			});";
			
			$this->grid->view->registerJs($js, View::POS_READY, 'common-action-column-'. $this->grid->getId());
		}
	}

}
