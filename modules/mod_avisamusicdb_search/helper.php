<?php

/**
 * @package     Joomla.Site
 * @subpackage  mod_avisamusicdb_search
 *
 * @copyright   Copyright (C) 2020 - 2023 AvisaPro. All rights reserved.
 * @license     GNU General Public License version 2 or later.
 */


defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\Filter\OutputFilter;
use Joomla\CMS\Helper\ModuleHelper;
use Joomla\CMS\Layout\FileLayout;
use Joomla\CMS\Router\Route;
use Joomla\Registry\Registry;

// Load Component Helper
require_once JPATH_BASE . '/components/com_avisamusicdb/helpers/helper.php';

class modAvisamusicdbSearchHelper
{

	public static function getAjax()
	{

		$input  = Factory::getApplication()->input;
		$type 	= $input->post->get('type', '', 'STRING');
		$word 	= $input->post->get('query', '', 'STRING');

		if ($word == '') return;

		$results = self::getSearchedItems($word, $type);

		$layout = new FileLayout('results', $basePath = JPATH_ROOT . '/modules/mod_avisamusicdb_search/layouts');
		$html = $layout->render(array('results' => $results, 'type' => $type));

		$output = array(
			'status' => 'true',
			'content' => $html
		);

		echo json_encode($output);
		die;
	}

	private static function getSearchedItems($word, $type)
	{

		// get params
		$module = ModuleHelper::getModule('mod_avisamusicdb_search');
		$registry = new Registry();
		$params = $registry->loadString($module->params);
		// get limit
		$limit = $params->get('limit', 5);

		$db = Factory::getDbo();
		$query = $db->getQuery(true);

		$search = preg_replace('#\xE3\x80\x80#s', " ", trim($word));
		$search_array = explode(" ", $db->escape($search));

		if ($type == 'celebrities') {
			$query->select('id AS avisamusicdb_celebrity_id, title, alias, profile_image');
			$query->from($db->quoteName('#__avisamusicdb_celebrities'));
		} elseif ($type == 'albums') {
			$query->select('id AS avisamusicdb_music_id, title, alias, albums, genres, profile_image');
			$query->from($db->quoteName('#__avisamusicdb_albums'));
			$query->where($db->quoteName('title') . '!=""');
		} elseif ($type == 'trailers') {
			$query->select('id AS avisamusicdb_music_id, title, alias, genres, profile_image');
			$query->from($db->quoteName('#__avisamusicdb_musics'));
			$query->where($db->quoteName('trailer_one') . '!=""');	
		} elseif ($type == 'genres') {
			$genres = self::getMatchesGenres($word);

			if (count($genres) && $genres) {
				$str_tag_ids = implode(' OR ', array_map(function ($entry) {
					return "genres LIKE '%" . $entry->avisamusicdb_genre_id . "%'";
				}, $genres));

				$query->select('id AS avisamusicdb_music_id, title, alias, genres, profile_image');
				$query->from($db->quoteName('#__avisamusicdb_musics'));
				$query->where($str_tag_ids);
			} else {
				return '';
			}
		} else {
			$query->select('id AS avisamusicdb_music_id, title, alias, albums, genres, profile_image');
			$query->from($db->quoteName('#__avisamusicdb_musics'));
		}

		// search string
		if ($type != 'genres') {
			$query->where($db->quoteName('title') . " LIKE '%" . implode("%' OR " . $db->quoteName('title') . " LIKE '%", $search_array) . "%'");
		}

		$query->where($db->quoteName('published') . " = 1");

		if ($type == 'celebrities') {
			$query->order('ordering DESC');
		} else {
			$query->order('release_date DESC');
		}

		$query->setLimit($limit);
		$db->setQuery($query);
		$results = $db->loadObjectList();

		//avisamusicdb_celebrity_id, avisamusicdb_music_id alias from id field
		foreach ($results as &$result) {
			if ($type == 'celebrities') {
				$result->url  = Route::_('index.php?option=com_avisamusicdb&view=celebrity&id=' . $result->avisamusicdb_celebrity_id . ':' . $result->alias . AvisamusicdbHelper::getItemid('celebrities'));
			} else {
				$result->url  = Route::_('index.php?option=com_avisamusicdb&view=music&id=' . $result->avisamusicdb_music_id . ':' . $result->alias . AvisamusicdbHelper::getItemid('musics'));
			}
			$result->title = OutputFilter::ampReplace($result->title);
		}

		return $results;
		
		//avisamusicdb_album_id, avisamusicdb_music_id alias from id field
		foreach ($results as &$result) {
			if ($type == 'albums') {
				$result->url  = Route::_('index.php?option=com_avisamusicdb&view=album&id=' . $result->avisamusicdb_album_id . ':' . $result->alias . AvisamusicdbHelper::getItemid('albums'));
			} else {
				$result->url  = Route::_('index.php?option=com_avisamusicdb&view=music&id=' . $result->avisamusicdb_music_id . ':' . $result->alias . AvisamusicdbHelper::getItemid('musics'));
			}
			$result->title = OutputFilter::ampReplace($result->title);
		}

		return $results;
	}

	/**
	 * Get matched genres by word
	 *
	 * @param string $word
	 * @return mixed
	 */
	private static function getMatchesGenres($word)
	{
		$db = Factory::getDbo();
		$query = $db->getQuery(true);

		$search = preg_replace('#\xE3\x80\x80#s', " ", trim($word));
		$search_array = explode(" ", $db->escape($search));

		$query->select('id AS avisamusicdb_genre_id, title, alias');
		$query->from($db->quoteName('#__avisamusicdb_genres'));
		$query->where($db->quoteName('title') . " LIKE '%" . implode("%' OR " . $db->quoteName('title') . " LIKE '%", $search_array) . "%'");
		$query->where($db->quoteName('published') . " = 1");
		$query->setLimit(10);
		$db->setQuery($query);
		$results = $db->loadObjectList();

		return $results;
	}
		private static function getMatchesAlbums($word)
	{
		$db = Factory::getDbo();
		$query = $db->getQuery(true);

		$search = preg_replace('#\xE3\x80\x80#s', " ", trim($word));
		$search_array = explode(" ", $db->escape($search));

		$query->select('id AS avisamusicdb_album_id, title, alias');
		$query->from($db->quoteName('#__avisamusicdb_albums'));
		$query->where($db->quoteName('title') . " LIKE '%" . implode("%' OR " . $db->quoteName('title') . " LIKE '%", $search_array) . "%'");
		$query->where($db->quoteName('published') . " = 1");
		$query->setLimit(10);
		$db->setQuery($query);
		$results = $db->loadObjectList();

		return $results;
	}

	/**
	 * Get rating by music id
	 *
	 * @param int $music_id
	 * @return mixed
	 */
	public static function getRatings($music_id)
	{
		$db = Factory::getDbo();
		$query = $db->getQuery(true);
		$query->select(array('COUNT(a.rating) AS count', 'SUM(a.rating) AS total'));
		$query->from($db->quoteName('#__avisamusicdb_reviews', 'a'));
		$query->where($db->quoteName('a.musicid') . ' = ' . $db->quote($music_id));
		$query->where($db->quoteName('a.published') . " = " . $db->quote('1'));
		$db->setQuery($query);

		return $db->loadObject();
	}

	/**
	 * Get genre list by ids
	 *
	 * @param string $genre_ids
	 * @return mixed
	 */
	public static function getGenries($genre_ids)
	{
		if ($genre_ids == '') return null;
		$db = Factory::getDbo();
		$genre_ids = implode(',', $db->quote(json_decode($genre_ids)));
		$query = $db->getQuery(true);
		$query->select(array('a.*'));
		$query->from($db->quoteName('#__avisamusicdb_genres', 'a'));
		$query->where($db->quoteName('a.id') . " IN (" . $genre_ids . ")");
		$db->setQuery($query);
		$results = $db->loadObjectList();

		return $results;
	}
	
	public static function getAlbums($genre_ids)
	{
		if ($genre_ids == '') return null;
		$db = Factory::getDbo();
		$genre_ids = implode(',', $db->quote(json_decode($album_ids)));
		$query = $db->getQuery(true);
		$query->select(array('a.*'));
		$query->from($db->quoteName('#__avisamusicdb_albums', 'a'));
		$query->where($db->quoteName('a.id') . " IN (" . $album_ids . ")");
		$db->setQuery($query);
		$results = $db->loadObjectList();

		return $results;
	}
}
