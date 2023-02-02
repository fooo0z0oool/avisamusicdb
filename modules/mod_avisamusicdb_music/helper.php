<?php

/**
 * @package     Joomla.Site
 * @subpackage  mod_avisamusicdb_music
 *
 * @copyright   Copyright (C) 2020 - 2023 AvisaPro. All rights reserved.
 * @license     GNU General Public License version 2 or later.
 */


// no direct access
defined('_JEXEC') or die('Restricted access!');

use Joomla\CMS\Factory;
use Joomla\CMS\Router\Route;
use Joomla\CMS\MVC\Model\BaseDatabaseModel;

$com_path = JPATH_SITE . '/components/com_avisamusicdb/';
BaseDatabaseModel::addIncludePath($com_path . 'models', 'AvisamusicdbModel');

class ModAvisamusicdbMusicHelper
{
	/**
	 * get musics
	 *
	 * @param object $params
	 * @return mixed
	 */
	public static function getMusics($params)
	{
		$music_model =  BaseDatabaseModel::getInstance('Music', 'AvisamusicdbModel', array('ignore_request' => true));
		// Get param options
		$order_by = $params->get('order_by');

		$db = Factory::getDbo();
		$query = $db->getQuery(true);
		$query->select(array('id', 'title', 'alias', 'profile_image', 'actors','albums', 'genres', 'trailer_one', 't_thumb_one'));
		$query->from($db->quoteName('#__avisamusicdb_musics'));
		$query->where($db->quoteName('published') . ' = 1');

		if ($order_by == 'asc') {
			$query->order($db->quoteName('release_date') . ' ASC');
		} elseif ($order_by == 'featured') {
			$query->where($db->quoteName('featured') . ' = 1');
			$query->order($db->quoteName('release_date') . ' DESC');
		} else {
			$query->order($db->quoteName('release_date') . ' DESC');
		}


		$query->setLimit($params->get('limit', 6));
		$db->setQuery($query);
		$items = $db->loadObjectList();

		foreach ($items as &$item) {
			$item->genres 		= $music_model->getGenries($item->genres);
			$item->actors 		= $music_model->getCelebrities($item->actors);
			$item->albums 		= $music_model->getAlbums($item->actors);
			$item->url 			= Route::_('index.php?option=com_avisamusicdb&view=music&id=' . $item->id . ':' . $item->alias . AvisamusicdbHelper::getItemid('musics'));
			$item->turls 		= $music_model->GenerateTrailers($item->id);
			$item->ratings 		= $music_model->getRatings($item->id);
		}

		return $items;
	}
}
