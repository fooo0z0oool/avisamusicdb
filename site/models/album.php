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
use Joomla\CMS\Language\Text;
use Joomla\CMS\MVC\Model\ItemModel;
use Joomla\CMS\Language\Multilanguage;

class AvisamusicdbModelAlbum extends ItemModel
{

	protected $_context = 'com_avisamusicdb.album';

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
		$this->setState('album.id', $itemId);
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
		$itemId = (!empty($pk)) ? $pk : (int) $this->getState('album.id');

		$db = Factory::getDbo();
		$query = $db->getQuery(true);
		$query->select(array('a.*'));
		$query->from($db->quoteName('#__avisamusicdb_albums', 'a'));
		$query->where($db->quoteName('a.id') . " = " . $db->quote($itemId) . "");
		$db->setQuery($query);
		$item = $db->loadObject();

		if ($item->id) {
			return $item;
		} else {
			throw new \Exception(Text::_('COM_AVISAMUSICDB_ERROR_ALBUM_NOT_FOUND'), 404);
		}
	}

	/**
	 * update hit counter in database field
	 *
	 * @param integer $pk
	 * @return void
	 */
	public function hit($pk = 0)
	{
		$pk = (!empty($pk)) ? $pk : (int) $this->getState('album.id');
		$db = $this->getDbo();

		$db->setQuery(
			'UPDATE #__avisamusicdb_albums' .
				' SET hits = hits + 1' .
				' WHERE id = ' . (int) $pk
		);
	}


	/**
	 * Get music list by Album ID
	 *
	 * @param int $celebrity_id
	 * @param integer $limit
	 * @return mixed
	 */
	public static function getAlbumMusicsbyId($album_id, $limit = 4)
	{

		$db = Factory::getDbo();
		$query = $db->getQuery(true);
		$query->select(array('a.*'));
		$query->from($db->quoteName('#__avisamusicdb_musics', 'a'));
		$query->where($db->quoteName('a.albums') . " LIKE " . $db->quote('%"' . $album_id . '"%'));
		$query->where($db->quoteName('a.published') . " = " . $db->quote('1'));
		$query->where($db->quoteName('a.trailer_one') . '!=""');
		$query->order($db->quoteName('a.created') . ' DESC');
		$query->setLimit($limit);
		$db->setQuery($query);
		$results = $db->loadObjectList();

		return $results;
	}
}
