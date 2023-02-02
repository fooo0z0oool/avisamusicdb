<?php

/**
 * @package    	Joomla.Site
 * @subpackage 	com_avisamusicdb
 * @author 		AvisaPro support@avisapro.ir
 * @copyright 	Copyright (c) 2020 - 2023 AvisaPro <https://www.avisapro.ir>. All rights reserved.
 * @license     GNU General Public License version 2 or later; see http://www.gnu.org/licenses/gpl-2.0.html
 */

// No Direct Access
defined('_JEXEC') or die('Restricted Access');

use Joomla\CMS\Factory;
use Joomla\CMS\MVC\Model\ListModel;
use Joomla\CMS\Language\Multilanguage;


class AvisamusicdbModelSearchresults extends ListModel
{
	/**
	 * Method to auto-populate the model state.
	 *
	 * @param string $ordering
	 * @param string $direction
	 * @return void
	 */
	protected function populateState($ordering = null, $direction = null)
	{
		$app = Factory::getApplication('site');
		$this->setState('list.start', $app->input->get('limitstart', 0, 'uint'));
		$this->setState('filter.language', Multilanguage::isEnabled());
	}

	/**
	 * Method to get a JDatabaseQuery object for retrieving the data set from a database.
	 *
	 * @return  JDatabaseQuery   A JDatabaseQuery object to retrieve the data set.
	 *
	 * @since   1.0.0
	 */
	protected function getListQuery()
	{
		// Get Params
		$app 	= Factory::getApplication();
		$inputs = $app->input;
		$db 	= $this->getDbo();

		//$menu_genre 	= $params->get('genre');
		$input 			= Factory::getApplication()->input;
		$searchword 	= $inputs->get('searchword', '', 'STRING');
		$searchtype 	= $inputs->get('type', '', 'WORD');
		$search 		= preg_replace('#\xE3\x80\x80#s', " ", trim($searchword));
		$search_array 	= explode(" ", $db->escape($search));

		$query = $db->getQuery(true);

		if ($searchtype == 'celebrities') {
			$query->select(array('a.id AS avisamusicdb_celebrity_id', 'a.title', 'a.alias', 'a.profile_image'));
			$query->from($db->quoteName('#__avisamusicdb_celebrities', 'a'));
			$query->order($db->quoteName('a.ordering') . ' DESC');
		} elseif ($searchtype == 'genres') {
			$genres = self::getMatchesGenres($searchword);
			$str_tag_ids = implode(' OR ', array_map(function ($entry) {
				return "a.genres LIKE '%" . $entry->id . "%'";
			}, $genres));

			$str_tag_ids = (isset($str_tag_ids) && $str_tag_ids) ? $str_tag_ids : 0;
			$query->select(array('a.id AS avisamusicdb_music_id', 'a.title', 'a.genres', 'a.alias', 'a.profile_image', 'a.release_date'));
			$query->from($db->quoteName('#__avisamusicdb_musics', 'a'));
			$query->where($str_tag_ids);
			$query->order($db->quoteName('a.release_date') . ' DESC');
		} elseif ($searchtype == 'trailers') {
			$query->select(array('a.id AS avisamusicdb_music_id', 'a.title', 'a.genres', 'a.alias', 'a.profile_image', 'a.release_date'));
			$query->from($db->quoteName('#__avisamusicdb_musics', 'a'));
			$query->order($db->quoteName('a.release_date') . ' DESC');
		} else {
			$query->select(array('a.id AS avisamusicdb_music_id', 'a.title', 'a.genres', 'a.alias', 'a.profile_image', 'a.release_date'));
			$query->from($db->quoteName('#__avisamusicdb_musics', 'a'));
			$query->order($db->quoteName('a.release_date') . ' DESC');
		}

		if ($searchtype != 'genres' && $searchword) {
			$query->where($db->quoteName('title') . " LIKE '%" . implode("%' OR " . $db->quoteName('title') . " LIKE '%", $search_array) . "%'");
		}

		$query->where($db->quoteName('a.published') . " = " . $db->quote(1));
		//Language & access
		$query->where($db->quoteName('a.language') . ' IN (' . $db->quote(Factory::getLanguage()->getTag()) . ',' . $db->quote('*') . ')');
		$query->where($db->quoteName('a.access') . ' IN (' . 	implode(',', $db->quote(Factory::getUser()->getAuthorisedViewLevels())) . ')');


		return $query;
	}

	/**
	 * Get matches genres
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
		$query->select($db->quoteName(array('id', 'title', 'alias')));
		$query->from($db->quoteName('#__avisamusicdb_genres'));
		$query->where($db->quoteName('title') . " LIKE '%" . implode("%' OR " . $db->quoteName('title') . " LIKE '%", $search_array) . "%'");
		$query->where($db->quoteName('published') . " = 1");
		$db->setQuery($query);
		$results = $db->loadObjectList();

		return $results;
	}
}
