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
use Joomla\CMS\Filesystem\File;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\MVC\Model\ListModel;

class AvisamusicdbModelMusics extends ListModel
{

	/**
	 * Method to get a JDatabaseQuery object for retrieving the data set from a database.
	 *
	 * @return  JDatabaseQuery   A JDatabaseQuery object to retrieve the data set.
	 *
	 * @since   1.0.0
	 */
	protected function getListQuery()
	{
		if (Factory::getApplication()->isClient('site')) {
			// Get Params
			$app = Factory::getApplication();
			$active_menu   = $app->getMenu()->getActive(); // get the active item

			if ($active_menu) {
				$params   	= $active_menu->getParams(); // get the active item
				$genreid 	= $params->get('genreid', '');
				$country 	= $params->get('country', '');
				$order_by 	= $params->get('order_by', '');
			}

			$now = HTMLHelper::_('date', Factory::getDate(), 'Y-m-d');
			$db = Factory::getDbo();
			$alphaindex = $app->input->get('alphaindex', '', 'WORD');
			$yearindex = $app->input->get('yearindex', '', 'INT');
		}

		$query = $db->getQuery(true);
		$query->select(array('a.*'));
		$query->from($db->quoteName('#__avisamusicdb_musics', 'a'));
		$query->where($db->quoteName('a.published') . " = " . $db->quote(1));

		if (Factory::getApplication()->isClient('site')) {
			//Language & access
			$query->where($db->quoteName('a.language') . ' IN (' . $db->quote(Factory::getLanguage()->getTag()) . ',' . $db->quote('*') . ')');
			$query->where($db->quoteName('a.access') . ' IN (' . 	implode(',', $db->quote(Factory::getUser()->getAuthorisedViewLevels())) . ')');

			if ($alphaindex) {
				$query->where($db->quoteName('a.title') . " LIKE " . $db->quote(strtolower($alphaindex) . '%'));
			}
			if ($yearindex) {
				$query->where('YEAR(' . $db->quoteName('a.release_date') . ')  = ' . $db->quote($yearindex));
			}

			if ($active_menu) {
				if ($genreid) {
					$query->where($db->quoteName('a.genres') . " LIKE " . $db->quote('%' . $genreid . '%'));
				}
				if ($country) {
					$query->where($db->quoteName('a.country') . " = " . $db->quote($country));
				}
				if ($order_by == 'desc') {
					$query->order($db->quoteName('a.release_date') . ' DESC');
				} elseif ($order_by == 'asc') {
					$query->order($db->quoteName('a.release_date') . ' ASC');
				} elseif ($order_by == 'latest_released') {
					$query->where($db->quoteName('a.release_date') . ' <= ' . $db->quote($now));
					$query->order($db->quoteName('a.release_date') . ' DESC');
				} elseif ($order_by == 'featured') {
					$query->where($db->quoteName('a.featured') . ' = 1');
					$query->order($db->quoteName('a.release_date') . ' DESC');
				} elseif ($order_by == 'coming') {
					$query->where($db->quoteName('a.release_date') . ' > ' . $db->quote($now));
					$query->order($db->quoteName('a.created') . ' ASC');
				} else {
					$query->order($db->quoteName('a.ordering') . ' DESC');
				}
			}
		}

		return $query;
	}


	/**
	 * Get music year list 
	 *
	 * @return mixed
	 */
	public static function getMusicsYear()
	{

		// Get Params
		$app = Factory::getApplication();
		$params   = $app->getMenu()->getActive()->getParams(); // get the active item
		$order_by = $params->get('order_by', '');
		$now = HTMLHelper::_('date', Factory::getDate(), 'Y-m-d');

		$db = Factory::getDbo();
		$query = $db->getQuery(true);
		$query->select('DISTINCT YEAR( release_date ) AS year');
		$query->from($db->quoteName('#__avisamusicdb_musics', 'a'));
		$query->where($db->quoteName('published') . " = " . $db->quote('1'));

		if ($order_by == 'latest_released') {
			$query->where($db->quoteName('a.release_date') . ' <= ' . $db->quote($now));
		} elseif ($order_by == 'coming') {
			$query->where($db->quoteName('a.release_date') . ' >= ' . $db->quote($now));
		}

		$query->group($db->quoteName('year'));
		$query->order('a.release_date DESC');
		$db->setQuery($query);
		return $db->loadObjectList();
	}

	/**
	 * Get musics by year
	 *
	 * @param integer $year
	 * @return mixed
	 */
	public static function getMusicsByYear($year = 0)
	{
		$db = Factory::getDbo();
		$query = $db->getQuery(true);
		$query->select('DISTINCT YEAR( release_date ) AS year');
		$query->from($db->quoteName('#__avisamusicdb_musics'));
		$query->where($db->quoteName('published') . " = " . $db->quote('1'));
		$query->group($db->quoteName('year'));
		$query->order('release_date DESC');
		$db->setQuery($query);
		return $db->loadObjectList();
	}

	/**
	 * Get music by id
	 *
	 * @param int $music_id
	 * @return mixed
	 */
	public static function getMusicById($music_id)
	{
		$db = Factory::getDbo();
		$query = $db->getQuery(true);
		$query->select(array('a.id', 'a.title', 'a.alias', 'a.music_story', 'a.release_date', 'a.genres'));
		$query->from($db->quoteName('#__avisamusicdb_musics', 'a'));
		$query->where($db->quoteName('a.id') . " = " . $db->quote($music_id));
		$db->setQuery($query);
		$result = $db->loadObject();

		return $result;
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
	
		/**
	 * Get album list by ids
	 *
	 * @param string $album_ids
	 * @return mixed
	 */
	public static function getAlbums($album_ids)
	{
		if ($album_ids == '') return null;

		$db = Factory::getDbo();
		$album_ids = implode(',', $db->quote(json_decode($album_ids)));
		$query = $db->getQuery(true);
		$query->select(array('a.*'));
		$query->from($db->quoteName('#__avisamusicdb_albums', 'a'));
		$query->where($db->quoteName('a.id') . " IN (" . $album_ids . ")");
		$db->setQuery($query);
		$results = $db->loadObjectList();

		return $results;
	}
	

	/**
	 * Generate trailers by music id
	 *
	 * @param int $music_id
	 * @return mixed
	 */
	public static function GenerateTrailers($music_id)
	{
		$db = Factory::getDbo();
		$query = $db->getQuery(true);
		$query->select(array('a.*'));
		$query->from($db->quoteName('#__avisamusicdb_musics', 'a'));
		$query->where($db->quoteName('a.id') . " = " . $music_id . "");
		$db->setQuery($query);

		$item = $db->loadObject();

		$trailer_urls   = array($item->trailer_one, $item->trailer_two, $item->trailer_three, $item->trailer_four, $item->trailer_five, $item->trailer_six, $item->trailer_seven, $item->trailer_eight, $item->trailer_nine, $item->trailer_ten);

		$trailer_title = array($item->trailer_one_title, $item->trailer_two_title, $item->trailer_three_title, $item->trailer_four_title, $item->trailer_five_title, $item->trailer_six_title, $item->trailer_seven_title, $item->trailer_eight_title, $item->trailer_nine_title, $item->trailer_ten_title);

		$trailer_thumbs = array($item->t_thumb_one, $item->t_thumb_two, $item->t_thumb_three, $item->t_thumb_four, $item->t_thumb_five, $item->t_thumb_six, $item->t_thumb_seven, $item->t_thumb_eight, $item->t_thumb_nine, $item->t_thumb_ten);


		if (isset($trailer_urls)) {
			$urls 			= $trailer_urls;
			$title 			= $trailer_title;
			$tmb_large 	= $trailer_thumbs;

			// Trailers URLS
			$turls = array();
			$ukey 		   = 0;
			foreach ($urls as $id => $url) {
				if ($url) {

					if (isset($trailer_title[$id]) && $trailer_title[$id]) {
						$trailer_title[$id] = $trailer_title[$id];
					} else {
						$trailer_title[$id] = $item->title;
					}

					$turls[$ukey] = array(
						'url' 			=> $urls[$id],
						'trailer_title' => $trailer_title[$id],
						'tmb_large' 	=> $tmb_large[$id],
					);
					$ukey++;
				}
			}

			// trailers urls
			$item->turls = array();
			foreach ($turls as $key => $turl) {
				$vurl = parse_url($turl['url']);
				switch ($vurl['host']) {
					case 'youtu.be':
						$id = trim($vurl['path'], '/');
						$src = '//www.youtube.com/embed/' . $id;
						$host = 'youtube';
						break;

					case 'www.youtube.com':
					case 'youtube.com':
						parse_str($vurl['query'], $query);
						$id = $query['v'];
						$src = '//www.youtube.com/embed/' . $id;
						$host = 'youtube';
						break;

					case 'vimeo.com':
					case 'www.vimeo.com':
						$id = trim($vurl['path'], '/');
						$src = "//player.vimeo.com/video/{$id}";
						$host = 'vimeo';
						break;

					case 'dailymotion.com':
					case 'www.dailymotion.com':
						$id = trim(strtok(basename($vurl['path']), '_'));
						$src = "//dailymotion.com/embed/video/{$id}";
						$host = 'dailymotion';
						break;

					default:
						$id = isset($vurl['path']) ? trim($vurl['path'], '/') : '';
						$src = $turl['url'];
						$host = 'self';
						break;
				} // END:: switch case

				$thumb_baseurl = basename($turl['tmb_large']);
				//generate thumb url
				if (isset($thumb_baseurl) && $thumb_baseurl) {
					$trailer_thumb = dirname($turl['tmb_large']) .  '/_avisamedia_thumbs' . '/' . File::stripExt($thumb_baseurl) .  '.' . File::getExt($thumb_baseurl);
				}

				$item->turls[$key] = array(
					'id' 			=> $id,
					'src'			=> $src,
					'host'			=> $host,
					'title'			=> $turl['trailer_title'],
					'genres'		=> self::getGenries($item->genres),
					'music_id'		=> $item->id,
					'tmb_large'		=> $turl['tmb_large'],
					'tmb_small'		=> $trailer_thumb,
				);
			} // END:: foreach turl
		} // END:: isset has url decode


		return $item->turls;
	}
}
