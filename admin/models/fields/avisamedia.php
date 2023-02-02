<?php

/**
 * @package    	Joomla.Administrator
 * @subpackage 	com_avisamusicdb
 * @author 		AvisaPro support@avisapro.ir
 * @copyright 	Copyright (c) 2020 - 2023 AvisaPro https://www.avisapro.ir
 * @license     GNU General Public License version 2 or later; see http://www.gnu.org/licenses/gpl-2.0.html
 */

defined('_JEXEC') or die('Restricted access!');

use Joomla\CMS\Form\FormField;
use Joomla\CMS\Factory;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Uri\Uri;

class JFormFieldAvisamedia extends FormField
{
	protected $type = 'Avisamedia';

	protected function getInput()
	{

		$lang = Factory::getLanguage();
		$lang->load('com_avisamusicdb', JPATH_ADMINISTRATOR, $lang->getName(), true);

		Text::script('AVISAMEDIA_MANAGER');
		Text::script('AVISAMEDIA_MANAGER_UPLOAD_FILE');
		Text::script('AVISAMEDIA_MANAGER_CLOSE');
		Text::script('AVISAMEDIA_MANAGER_INSERT');
		Text::script('AVISAMEDIA_MANAGER_SEARCH');
		Text::script('AVISAMEDIA_MANAGER_CANCEL');
		Text::script('AVISAMEDIA_MANAGER_DELETE');
		Text::script('AVISAMEDIA_MANAGER_CONFIRM_DELETE');
		Text::script('AVISAMEDIA_MANAGER_LOAD_MORE');
		Text::script('AVISAMEDIA_MANAGER_UNSUPPORTED_FORMAT');
		Text::script('AVISAMEDIA_MANAGER_BROWSE_MEDIA');
		Text::script('AVISAMEDIA_MANAGER_BROWSE_FOLDERS');

		HTMLHelper::_('jquery.framework');

		$doc = Factory::getDocument();
		$doc->addStylesheet(Uri::base(true) . '/components/com_avisamusicdb/assets/css/font-awesome.min.css');
		$doc->addStylesheet(Uri::base(true) . '/components/com_avisamusicdb/assets/css/avisamedia.css');
		$doc->addScript(Uri::base(true) . '/components/com_avisamusicdb/assets/js/avisamedia.js');

		// Custom Thumbnail size
		$thumbsize = '300x225';
		if ($this->getAttribute('thumbsize') != NULL) {
			$thumbsize	= $this->getAttribute('thumbsize');
		}

		if ($this->value) {
			$html = '<img class="avisa-media-preview" src="' . Uri::root(true) . '/' . $this->value . '" alt="" />';
		} else {
			$html  = '<img class="avisa-media-preview no-image" alt="">';
		}

		$html .= '<input class="avisa-media-input" type="hidden" name="' . $this->name . '" id="' . $this->id . '" value="' . $this->value . '">';
		$html .= '<a href="#" class="btn btn-primary avisa-btn-media-manager" data-id="' . $this->id . '" data-thumbsize="' . strtolower($thumbsize) . '"><i class="fa fa-picture-o"></i> ' . Text::_('AVISAMEDIA_MANAGER_SELECT') . '</a> <a href="#" class="btn btn-danger btn-clear-image"><i class="fa fa-times"></i></a>';

		return $html;
	}

	public function getRepeatable()
	{

		$path = $this->value;

		if ($path && !(is_numeric($path))) {
			$thumb = dirname($path) . '/_avisamedia_thumbs/' . basename($path);

			if (file_exists(JPATH_ROOT . '/' . $thumb)) {
				$image = '<img src="' . Uri::root(true) . '/' . $thumb . '">';
			} else {
				$image = '<img src="' . Uri::root(true) . '/' . $path . '">';
			}

			return $image;
		}

		return '';
	}
}
