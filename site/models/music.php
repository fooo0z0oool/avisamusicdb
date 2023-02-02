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
use Joomla\CMS\Router\Route;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Filesystem\File;
use Joomla\CMS\MVC\Model\ItemModel;
use Joomla\CMS\Language\Multilanguage;
use Joomla\CMS\Component\ComponentHelper;

class AvisamusicdbModelMusic extends ItemModel
{

	protected $_context = 'com_avisamusicdb.music';

	/**
	 * Method to auto-populate the model state.
	 *
	 * @param string $ordering
	 * @param string $direction
	 * @return void
	 *
	 * @since 1.0.0
	 */
	protected function populateState($ordering = null, $direction = null)
	{
		$app = Factory::getApplication('site');
		$itemId = $app->input->getInt('id');
		$this->setState('music.id', $itemId);
		$this->setState('filter.language', Multilanguage::isEnabled());
	}

	/**
	 * Get the single row data.
	 *
	 * @return  object
	 *
	 * @since   1.0.0
	 */
	public function getItem($itemId = null)
	{
		$itemId = (!empty($itemId)) ? $itemId : (int)$this->getState('music.id');
		$db = Factory::getDbo();
		$query = $db->getQuery(true);
		$query->select(array('a.*'));
		$query->from($db->quoteName('#__avisamusicdb_musics', 'a'));
		$query->where($db->quoteName('a.id') . " = " . $db->quote($itemId) . "");
		$db->setQuery($query);
		$item = $db->loadObject();

		if ($item->id) {
			return $item;
		} else {
			throw new \Exception(Text::_('COM_AVISAMUSICDB_ERROR_CELEBRITY_NOT_FOUND'), 404);
		}
	}

	/**
	 * Get celebrity list by multiple id
	 *
	 * @param string $celebrity_ids
	 * @return string
	 */
	public static function getCelebrities($celebrity_ids)
	{
		if ($celebrity_ids == '') return null;

		$db = Factory::getDbo();
		$celebrity_ids = implode(',', $db->quote(json_decode($celebrity_ids)));
		$query = $db->getQuery(true);
		$query->select(array('a.*'));
		$query->from($db->quoteName('#__avisamusicdb_celebrities', 'a'));
		$query->where($db->quoteName('a.id') . " IN (" . $celebrity_ids . ")");
		$db->setQuery($query);
		$celebrities = $db->loadObjectList();
		$celebrity_output = '';
		
		foreach ($celebrities as $key => $celebrity) {
			$celebrity_url 		= Route::_('index.php?option=com_avisamusicdb&view=celebrity&id=' . $celebrity->id . ':' . $celebrity->alias . AvisamusicdbHelper::getItemid('celebrities'));
			$celebrity_output .= '<a href="' . $celebrity_url . '">' . $celebrity->title . '</a>' . ', ';
		}

		return rtrim(trim($celebrity_output), ',');
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

	//Get Related Items
	/**
	 * Get realted musics
	 *
	 * @param array $genres
	 * @param integer $id
	 * @return list
	 */
	public function getRelated($genres, $id = 0)
	{
		$db = Factory::getDbo();
		$str_tag_ids = implode(' OR ', array_map(function ($entry) {
			return "`a`.`genres` LIKE '%" . $entry->id . "%'";
		}, $genres));

		$query = $db->getQuery(true);
		$query->select(array('a.*'));
		$query->from($db->quoteName('#__avisamusicdb_musics', 'a'));
		$query->where($str_tag_ids);
		$query->where($db->quoteName('a.id') . " != " . $db->quote($id));
		$query->where($db->quoteName('a.published') . " = " . $db->quote('1'));
		$query->order($db->quoteName('a.created') . ' DESC');
		$query->setLimit(3);
		$db->setQuery($query);

		return $db->loadObjectList();
	}

	/**
	 * Get total review by music id
	 *
	 * @param int $music_id
	 * @return int
	 */
	public function getTotalReviews($music_id)
	{
		$input = Factory::getApplication()->input;
		$start 	= $input->post->get('start', 0, 'INT');
		$limit 	= 1;
		$db = Factory::getDbo();
		$query = $db->getQuery(true);
		$query->select(array('COUNT(a.id)'));
		$query->from($db->quoteName('#__avisamusicdb_reviews', 'a'));
		$query->join('INNER', $db->quoteName('#__users', 'b') . ' ON (' . $db->quoteName('a.created_by') . ' = ' . $db->quoteName('b.id') . ')');
		$query->where($db->quoteName('a.musicid') . ' = ' . $db->quote($music_id));
		$query->where($db->quoteName('a.published') . " = " . $db->quote('1'));
		$db->setQuery($query);

		return $db->loadResult();
	}

	/**
	 * Get review list by music id
	 *
	 * @param int $music_id
	 * @return list
	 */
	public function getReviews($music_id)
	{
		$params = ComponentHelper::getParams('com_avisamusicdb');
		$input = Factory::getApplication()->input;
		$start 	= $input->post->get('start', 0, 'INT');
		$limit 	= $params->get('review_limit', 12);
		$db = Factory::getDbo();
		$query = $db->getQuery(true);
		$query->select(array('a.*', 'b.email', 'b.name'));
		$query->from($db->quoteName('#__avisamusicdb_reviews', 'a'));
		$query->join('INNER', $db->quoteName('#__users', 'b') . ' ON (' . $db->quoteName('a.created_by') . ' = ' . $db->quoteName('b.id') . ')');
		$query->where($db->quoteName('a.musicid') . ' = ' . $db->quote($music_id));
		$query->order($db->quoteName('a.created') . ' DESC');
		$query->setLimit($limit, $start);
		$db->setQuery($query);
		$reviews = $db->loadObjectList();

		return $reviews;
	}


	/**
	 * Get my reviews by music id
	 *
	 * @param int $music_id
	 * @return list
	 */
	public function getMyReview($music_id)
	{

		$user = Factory::getUser();

		if ($user->id) {
			$db = Factory::getDbo();
			$query = $db->getQuery(true);
			$query->select(array('a.*', 'b.email', 'b.name'));
			$query->from($db->quoteName('#__avisamusicdb_reviews', 'a'));
			$query->join('LEFT', $db->quoteName('#__users', 'b') . ' ON (' . $db->quoteName('a.created_by') . ' = ' . $db->quoteName('b.id') . ')');
			$query->where($db->quoteName('a.musicid') . ' = ' . $db->quote($music_id));
			$query->where($db->quoteName('a.created_by') . ' = ' . $db->quote($user->id));
			$query->where($db->quoteName('a.published') . " = " . $db->quote('1'));
			$db->setQuery($query);
			$review = $db->loadObject();

			if (isset($review)) {
				$review->gravatar = md5($review->email);
				$review->created_date = AvisamusicdbHelper::timeago($review->created);
				return $review;
			}
			return false;
		}

		return false;
	}

	/**
	 * Get rating by music id
	 *
	 * @param int $music_id
	 * @return mixed
	 */
	public function getRatings($music_id)
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
	 * update hit counter in database field
	 *
	 * @param integer $pk
	 * @return void
	 */
	public function hit($pk = 0)
	{
		$pk = (!empty($pk)) ? $pk : (int) $this->getState('music.id');
		$db = $this->getDbo();

		$db->setQuery(
			'UPDATE #__avisamusicdb_musics' .
				' SET hits = hits + 1' .
				' WHERE id = ' . (int) $pk
		);
	}

	/**
	 * check date is valid
	 *
	 * @param string $date
	 * @return bool
	 */
	function validateDate($date)
	{
		$d = DateTime::createFromFormat('Y-m-d', $date);
		return $d && $d->format('Y-m-d') == $date;
	}
}
