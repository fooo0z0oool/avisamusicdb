<?php

/**
 * @package     Joomla.Site
 * @subpackage  mod_avisamusicdb_albums
 *
 * @copyright   Copyright (C) 2020 - 2023 AvisaPro. All rights reserved.
 * @license     GNU General Public License version 2 or later.
 */

// no direct access
defined('_JEXEC') or die('Restricted access!');

use Joomla\CMS\Factory;
use Joomla\CMS\Router\Route;

class modAvisamusicdbAlbumsHelper
{

	/**
	 * get list of album
	 *
	 * @param mixed $params
	 * @return mixed
	 */
	public static function getAlbums($params)
	{
		$order_by = $params->get('order_by');

		$db = Factory::getDbo();
		$query = $db->getQuery(true);
		$query->select(array('id', 'title', 'alias', 'actor', 'profile_image', 'featured'));
		$query->from($db->quoteName('#__avisamusicdb_albums'));
		$query->where($db->quoteName('published') . ' = 1');

		if ($order_by == 'featured') {
			$query->order($db->quoteName('featured') . ' DESC');
		} elseif ($order_by == 'asc') {
			$query->order($db->quoteName('created') . ' ASC');
		} elseif ($order_by == 'hits') {
			$query->order($db->quoteName('hits') . ' DESC');
		} else {
			$query->order($db->quoteName('ordering') . ' DESC');
		}

		$query->setLimit($params->get('limit', 6));
		$db->setQuery($query);
		$items = $db->loadObjectList();

		foreach ($items as $item) {
			$item->url 	= Route::_('index.php?option=com_avisamusicdb&view=album&id=' . $item->id . ':' . $item->alias . AvisamusicdbHelper::getItemid('albums'));
		}

		return $items;
	}
}
