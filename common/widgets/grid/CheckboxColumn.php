<?php

namespace common\widgets\grid;

use Yii;
use yii\helpers\Json;
use yii\web\View;
use yii\helpers\ArrayHelper;

/**
 * CheckboxColumn
 */
class CheckboxColumn extends \kartik\grid\CheckboxColumn
{
	/**
	 * @inheritdoc
	 */
	public $width = '20px';

	/**
	 * @inheritdoc
	 */
	public $vAlign = 'top';

	/**
	 * @var bool whether bulk enabled
	 */
	public $bulk = true;

	/**
	 * @var string
	 */
	public $bulkClass = 'bulk';

	/**
	 * @var string
	 */
	public $noSelectionMessage = 'No Items selected';

	/*
	 *  @var boolean wether pjax on bulk enabled
	 */
	public $pjax = true;

	/**
	 * @inheritdoc
	 */
	public function init()
	{
		if ($this->bulk) {
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
			$grid_id = $this->grid->getId();

			$js = "$(document.body).on('click', 'a." . $this->bulkClass . "', function(e) {
				e.preventDefault();
				var keys = $('#" . $grid_id . "').yiiGridView('getSelectedRows');
				if (!keys.length) {
					krajeeDialog.alert(" . Json::encode($this->noSelectionMessage) . ");
				}
				else {
					var url = $(this).attr('href');
					var confirmation = $(this).data('confirm');
					if (confirmation){
						krajeeDialog.confirm(confirmation,
							function(confirmed){
								if (confirmed) { " . ($this->pjax ?
					"$.ajax({
										url: url,
										data: {ids: keys }
									}).done(function(data){
										$('a." . $this->bulkClass . "').attr('disabled', true);
										$.pjax.reload({container:'#" . $grid_id . "-pjax', timeout: false}); 
										if(data){
											krajeeDialog.alert(data);
										}
									});" : "window.location.href = url + ((url.indexOf('?') != -1) ? '&' : '?') + $.param({ids: keys });") . "
								}
							}
						);
					}
					else{" . ($this->pjax ?
					"$.ajax({
							url: url,
							data: {ids: keys }
						}).done(function(data){
							$('a." . $this->bulkClass . "').attr('disabled', true);
							$.pjax.reload({container:'#" . $grid_id . "-pjax', timeout: false}); 
							if(data){
								krajeeDialog.alert(data);
							}
						});" : "window.location.href = url + ((url.indexOf('?') != -1) ? '&' : '?') + $.param({ids: keys });") . "
					}
				}
				return false;
			});
			$(document.body).on('change', '#" . $grid_id . " ." . $this->cssClass . ",#" . $grid_id . " .select-on-check-all', function(e) {
				var keys = $('#" . $grid_id . "').yiiGridView('getSelectedRows');
				if (keys.length) {
					$('a." . $this->bulkClass . "').removeAttr('disabled').removeClass('disabled');
				}
				else{
					$('a." . $this->bulkClass . "').attr('disabled', true).addClass('disabled');
				}
			});
			";

			$this->grid->view->registerJs($js, View::POS_READY, 'common-checkbox-column-' . $grid_id);
		}
	}
}
