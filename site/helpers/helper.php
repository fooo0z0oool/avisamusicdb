<?php

/**
 * @package    	Joomla.Site
 * @subpackage 	com_avisamusicdb
 * @author 		AvisaPro support@avisapro.ir
 * @copyright 	Copyright (c) 2020 - 2023 AvisaPro https://www.avisapro.ir
 * @license     GNU General Public License version 2 or later; see http://www.gnu.org/licenses/gpl-2.0.html
 */

// No Direct Access
defined('_JEXEC') or die('Restricted Access');

use Joomla\CMS\Factory;
use Joomla\CMS\Language\Multilanguage;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Uri\Uri;

class AvisamusicdbHelper
{

	/**
	 * convert repeatable field data to form-repeatable data
	 *
	 * @param string $data
	 * @param string $fieldName
	 * @return string return a JSON string
	 * 
	 * @since 2.0.0
	 */
	public static function repeatableToFormRepeatable($data, $fieldName)
	{
		if ($data == null || $data == '') return $data;

		$rows = is_string($data) ? json_decode($data, true) : $data;
		$firstRow = current($rows);

		// check data is already in form repeatable format
		// form-repeatable data has params key
		if (isset($firstRow['params'])) {
			return $data;
		}

		$totalRow = 0;
		$convertedData = [];
		$fields = [];

		foreach ($rows as $key => $val) {
			array_push($fields, $key);
		}

		if (is_array($rows)) {
			$totalRow = count($firstRow);
		}

		for ($i = 0; $i < $totalRow; $i++) {
			$fieldsData = [];
			foreach ($fields as $f) {
				$fieldsData[$f] = $rows[$f][$i];
			}
			$convertedData[$fieldName . $i] = [
				'params' => $fieldsData
			];
		}

		return json_encode($convertedData);
	}

	/**
	 * Get item id 
	 *
	 * @param string $view
	 * @return int|bool
	 */
	public static function getItemid($view = 'musics')
	{
		$db = Factory::getDbo();
		$query = $db->getQuery(true);
		$query->select($db->quoteName(array('id')));
		$query->from($db->quoteName('#__menu'));
		$query->where($db->quoteName('link') . ' LIKE ' . $db->quote('%option=com_avisamusicdb&view=' . $view . '%'));
		$query->where($db->quoteName('client_id') . ' = ' . $db->quote('0'));
		$query->where($db->quoteName('published') . ' = ' . $db->quote('1'));
		if (Multilanguage::isEnabled()) {
			$lang = Factory::getLanguage()->getTag();
			$query->where('language IN ("*","' . $lang . '"');
		}
		$db->setQuery($query);
		$result = $db->loadResult();

		if ($result != null) {
			return '&Itemid=' . $result;
		}

		return false;
	}

	/**
	 * Get avisa image id
	 *
	 * @param int $image_id
	 * @return object
	 */
	public static function getAvisaImage($image_id)
	{

		$db = Factory::getDbo();
		$query = $db->getQuery(true);
		$query->select(array('a.*'));
		$query->from($db->quoteName('#__avisamusicdb_media', 'a'));
		$query->where($db->quoteName('a.avisamusicdb_medium_id') . " = " . $image_id . "");
		$db->setQuery($query);
		$result = $db->loadObject();

		return $result;
	}


	/**
	 * time to humen readable time string
	 *
	 * @param string $time
	 * @return string
	 */
	public static function timeago($time)
	{
		$periods = array("SECOND", "MINUTE", "HOUR", "DAY", "WEEK", "MONTH", "YEAR", "DECADE");
		$lengths = array("60", "60", "24", "7", "4.35", "12", "10");

		$difference     = strtotime(Factory::getDate('now')) - strtotime($time);
		$tense         = "ago";

		for ($j = 0; $difference >= $lengths[$j] && $j < count($lengths) - 1; $j++) {
			$difference /= $lengths[$j];
		}

		$difference = round($difference);

		if ($difference == 0) $difference = 1;

		if ($difference != 1) {
			$periods[$j] .= "S";
		}

		return $difference . ' ' . Text::_('COM_AVISAMUSICDB_TIMEAGO_' . $periods[$j]) . ' ' . Text::_('COM_AVISAMUSICDB_TIMEAGO_AGO');
	}

	/**
	 * Item meta
	 *
	 * @param array $meta
	 * @return void
	 */
	public static function itemMeta($meta = array())
	{
		$config 	= Factory::getConfig();
		$app 		= Factory::getApplication();
		$doc 		= Factory::getDocument();
		$menus   	= $app->getMenu();
		$menu 		= $menus->getActive();
		$params 	= $menu->getParams();
		$title 		= '';

		//Title
		if (isset($meta['title']) && $meta['title']) {
			$title = $meta['title'];
		} else {
			if ($menu) {
				if ($params->get('page_title', '')) {
					$title = $params->get('page_title');
				} else {
					$title = $menu->title;
				}
			}
		}

		//Include Site title
		$sitetitle = $title;
		if ($config->get('sitename_pagetitles') == 2) {
			$sitetitle = $title . ' | ' . $config->get('sitename');
		} elseif ($config->get('sitename_pagetitles') === 1) {
			$sitetitle = $config->get('sitename') . ' | ' . $title;
		}

		$doc->setTitle($sitetitle);
		$doc->addCustomTag('<meta content="' . $title . '" property="og:title" />');

		//Keywords
		if (isset($meta['keywords']) && $meta['keywords']) {
			$keywords = $meta['keywords'];
			$doc->setMetadata('keywords', $keywords);
		} else {
			if ($menu) {
				if ($params->get('menu-meta_keywords')) {
					$keywords = $params->get('menu-meta_keywords');
					$doc->setMetadata('keywords', $keywords);
				}
			}
		}

		//Metadescription
		if (isset($meta['metadesc']) && $meta['metadesc']) {
			$metadesc = $meta['metadesc'];
			$doc->setDescription($metadesc);
			$doc->addCustomTag('<meta content="' . $metadesc . '" property="og:description" />');
		} else {
			if ($menu) {
				if ($params->get('menu-meta_description')) {
					$metadesc = $params->get('menu-meta_description');
					$doc->setDescription($params->get('menu-meta_description'));
					$doc->addCustomTag('<meta content="' . $metadesc . '" property="og:description" />');
				}
			}
		}

		//Robots
		if ($menu) {
			if ($params->get('robots')) {
				$doc->setMetadata('robots', $params->get('robots'));
			}
		}

		//Open Graph
		foreach ($doc->_links as $k => $array) {
			if ($array['relation'] == 'canonical') {
				unset($doc->_links[$k]);
			}
		} // Remove Joomla canonical

		$doc->addCustomTag('<meta content="website" property="og:type"/>');
		$doc->addCustomTag('<link href="' . Uri::current() . '" rel="canonical" />');
		$doc->addCustomTag('<meta content="' . Uri::current() . '" property="og:url" />');

		if (isset($meta['image']) && $meta['image']) {
			$doc->addCustomTag('<meta content="' . $meta['image'] . '" property="og:image" />');
		}
	}

	/**
	 * pluralize amount text
	 *
	 * @param int $amount
	 * @param string $singular
	 * @param string $plural
	 * @return string
	 */
	public static function pluralize($amount, $singular, $plural)
	{
		$amount = (int)$amount;

		if ($amount <= 1) {
			return Text::_($singular);
		}

		return Text::_($plural);
	}
}
