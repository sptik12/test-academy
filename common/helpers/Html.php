<?php

/**
 * @package yii2-helpers
 * @version 1.3.9
 */

namespace common\helpers;

use Yii;
use yii\helpers\ArrayHelper;
use yii\helpers\FileHelper;


/**
 * Html provides a set of static methods for generating commonly used HTML tags and extends [[kartikHtml]]
 * with additional bootstrap styled components and markup.
 *
 * Nearly all of the methods in this class allow setting additional html attributes for the html
 * tags they generate. You can specify for example. 'class', 'style'  or 'id' for an html element
 * using the `$options` parameter. See the documentation of the [[tag()]] method for more details.
 *
 * @see http://getbootstrap.com/css
 * @see http://getbootstrap.com/components
 * @since 2.0
 */
class Html extends \kartik\helpers\Html
{
	/**
	 * @var string the icon name
	 */
	const ICON_VIEW = 'eye';
	const ICON_EVENT_LOG = 'edit';
	const ICON_USER_EDIT = 'user-edit';
	const ICON_EDIT = 'pencil-alt';
	const ICON_DOWNLOAD = 'download';
	const ICON_EMAIL = 'envelope';
	const ICON_LIST = 'list';
	const ICON_USER = 'user';
	const ICON_LOG = 'book';
	const ICON_PRODUCT = 'cubes';
	const ICON_SIGN_OUT = 'sign-out';
	const ICON_SETTINGS = 'cog';
	const ICON_GENERATE = 'plus';
	const ICON_DASHBOARD = 'chart-bar';
	const ICON_PLUS = 'plus';
	const ICON_REDO = 'times-circle';
	const ICON_UPDATE = 'pencil-alt';
	const ICON_DELETE = 'trash-alt';
	const ICON_LOCK = 'lock';
	const ICON_BACK = 'arrow-left';
	const ICON_DOWN = 'long-arrow-alt-down';
	const ICON_UP = 'long-arrow-alt-up';
	const ICON_CREATE = 'arrow-circle-right';
	const ICON_STATUS_OK = 'thumbs-up';
	const ICON_STATUS_FALSE = 'thumbs-down';
	const ICON_RESET = 'ban';

	/**
	 * Generates a bootstrap icon markup.
	 *
	 * Example:
	 *
	 * ~~~
	 * echo Html::icon('pencil');
	 * echo Html::icon('trash', ['style' => 'color: red; font-size: 2em']);
	 * echo Html::icon('plus', ['class' => 'text-success']);
	 * ~~~
	 *
	 * @see https://fontawesome.com/
	 *
	 * @param string $icon the bootstrap icon name without prefix (e.g. 'plus', 'pencil', 'trash')
	 * @param array $options HTML attributes / options for the icon container
	 * @param string $prefix the css class prefix - defaults to 'fas fa-'
	 * @param string $tag the icon container tag (usually 'span' or 'i') - defaults to 'i'
	 *
	 * @return string
	 */
	public static function icon($icon, $options = [], $prefix = 'fas fa-', $tag = 'i')
	{
		return parent::icon($icon, $options, $prefix, $tag);
	}

	/**
	 *
	 * @return string
	 */
	public static function noData($message = null)
	{
		if (!$message) {
			$message = Yii::t('app', 'Данных нет');
		}

		return Html::tag('div', $message, ['class' => "alert alert-danger"]);
	}

	/**
	 *
	 */
	public static function downloadFile($file, $file_name, $delete = false)
	{
		if (file_exists($file)) {
			header('Content-Description: File Transfer');
			header('Content-Type: application/octet-stream');
			header('Content-Disposition: attachment; filename="' . $file_name . '"');
			header('Content-Transfer-Encoding: binary');
			header('Expires: 0');
			header('Cache-Control: must-revalidate');
			header('Pragma: public');
			header('Content-Length: ' . filesize($file));
			ob_clean();
			flush();
			readfile($file);
			if ($delete) {
				if (is_bool($delete)) {
					unlink($file);
				} else {
					FileHelper::removeDirectory($delete);
				}
			}
			exit;
		}
	}
}
