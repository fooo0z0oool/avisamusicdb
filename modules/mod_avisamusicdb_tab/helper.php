<?php

/**
 * @package     Joomla.Site
 * @subpackage  mod_avisamusicdb_tab
 *
 * @copyright   Copyright (C) 2020 - 2023 AvisaPro. All rights reserved.
 * @license     GNU General Public License version 2 or later.
 */


defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\MVC\Model\BaseDatabaseModel;
use Joomla\CMS\Router\Route;

$com_path = JPATH_SITE . '/components/com_avisamusicdb/';
BaseDatabaseModel::addIncludePath($com_path . 'models', 'AvisamusicdbModel');
class ModAvisamusicdbTabHelper
{

	/**
	 * Get music list
	 *
	 * @param string $order_by
	 * @param integer $limit
	 * @return mixed
	 */
	public static function getMusics($order_by = '', $limit = 5)
	{
		$model = BaseDatabaseModel::getInstance('Musics', 'AvisamusicdbModel', array('ignore_request' => true));

		$now = HTMLHelper::_('date', Factory::getDate(), 'Y-m-d');

		$db = Factory::getDbo();
		$query = $db->getQuery(true);

		$query->select(array('a.id', 'a.title', 'a.alias', 'a.profile_image', 'a.genres', 'a.trailer_one', 'a.t_thumb_one', 'COUNT(b.rating) AS ratings_count', 'SUM(b.rating) AS ratings_sum'));
		$query->from($db->quoteName('#__avisamusicdb_musics', 'a'));
		$query->join('LEFT', $db->quoteName('#__avisamusicdb_reviews', 'b') . ' ON (' . $db->quoteName('a.id') . ' = ' . $db->quoteName('b.musicid') . ')');

		if ($order_by == 'top') {
			$query->where($db->quoteName('b.published') . ' = 1');
			$query->group($db->quoteName('b.musicid'));
		} else {
			$query->where($db->quoteName('a.published') . ' = 1');
			$query->group($db->quoteName('a.id'));
		}

		if ($order_by == 'coming') {
			$query->where($db->quoteName('a.release_date') . ' > ' . $db->quote($now));
			$query->order($db->quoteName('a.release_date') . ' ASC');
		} elseif ($order_by == 'latest') {
			$query->where($db->quoteName('a.release_date') . ' <= ' . $db->quote($now));
			$query->order($db->quoteName('a.release_date') . ' DESC');
		} elseif ($order_by == 'top') {
			$query->order('ratings_sum DESC');
		} elseif ($order_by == 'featured') {
			$query->where($db->quoteName('a.featured') . ' = 1');
			$query->order($db->quoteName('a.created') . ' DESC');
		} elseif ($order_by == 'ltrailers') {
			$query->where($db->quoteName('a.trailer_one') . '!=""');
			$query->order($db->quoteName('a.ordering') . ' DESC');
		} else {
			$query->order($db->quoteName('a.ordering') . ' DESC');
		}

		$query->setLimit($limit);
		$db->setQuery($query);
		$items = $db->loadObjectList();


		foreach ($items as &$item) {
			$item->genres 		= $model::getGenries($item->genres);
			$item->url 			= Route::_('index.php?option=com_avisamusicdb&view=music&id=' . $item->id . ':' . $item->alias . AvisamusicdbHelper::getItemid('musics'));
			$item->turls 		= $model::GenerateTrailers($item->id);
			$item->ratings 		= self::getRatings($item->id);
		}

		return $items;
	}

	/**
	 * Get ratings by music id
	 *
	 * @param int $music_id
	 * @return mixed
	 */
	private static function getRatings($music_id)
	{
		$db = Factory::getDbo();
		$query = $db->getQuery(true);
		$query->select(array('COUNT(a.rating) AS count', 'SUM(a.rating) AS total'));
		$query->from($db->quoteName('#__avisamusicdb_reviews', 'a'));
		$query->where($db->quoteName('a.musicid') . ' = ' . $db->quote($music_id));
		$db->setQuery($query);

		return $db->loadObject();
	}
}
