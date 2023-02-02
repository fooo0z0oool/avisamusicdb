<?php

/**
 * @package    	Joomla.Administrator
 * @subpackage 	com_avisamusicdb
 * @author 		AvisaPro support@avisapro.ir
 * @copyright 	Copyright (c) 2020 - 2023 AvisaPro https://www.avisapro.ir
 * @license     GNU General Public License version 2 or later; see http://www.gnu.org/licenses/gpl-2.0.html
 */

// No Direct Access
defined('_JEXEC') or die('Restricted Access');

use Joomla\CMS\Date\Date;
use Joomla\CMS\Factory;
use Joomla\CMS\Table\Table;
use Joomla\CMS\Language\Text;
use Joomla\Utilities\ArrayHelper;
use Joomla\CMS\Application\ApplicationHelper;

class AvisamusicdbTableMusic extends Table
{
	public function __construct(&$db)
	{
		parent::__construct('#__avisamusicdb_musics', 'id', $db);
	}

	public function bind($src, $ignore = array())
	{
		return parent::bind($src, $ignore);
	}

	public function store($updateNulls = false)
	{
		$user = Factory::getUser();
		$app  = Factory::getApplication();
		$date = new Date('now', $app->getCfg('offset'));

		if ($this->id) {
			$this->modified = (string)$date;
			$this->modified_by = $user->id;
		}

		if (empty($this->created)) {
			$this->created = (string)$date;
		}

		if (empty($this->created_by)) {
			$this->created_by = $user->id;
		}

		$table = Table::getInstance('Music', 'AvisamusicdbTable');

		$this->handleAlias();

		if ($table->load(['alias' => $this->alias]) && ($table->id != $this->id || $this->id == 0)) {
			$app->enqueueMessage(Text::_('COM_AVISAMUSICDB_ERROR_UNIQUE_ALIAS'), 'error');
			return false;
		}

		return parent::store($updateNulls);
	}



	private function handleAlias()
	{
		if (empty($this->alias)) {
			$this->alias = $this->title;
		}

		$this->alias = ApplicationHelper::stringURLSafe($this->alias, $this->language);

		if (trim(str_replace('-', '', $this->alias)) === '') {
			$this->alias = Factory::getDate()->format('Y-m-d-H-i-s');
		}
	}

	public function publish($pks = null, $published = 1, $userId = 0)
	{
		$app  = Factory::getApplication();
		$k = $this->_tbl_key;
		ArrayHelper::toInteger($pks);
		$publilshed = (int) $published;

		if (empty($pks)) {
			if ($this->$k) {
				$pks = array($this->$k);
			} else {
				$app->enqueueMessage(Text::_('JLIB_DATABASE_ERROR_NO_ROWS_SELECTED'), 'error');
				return false;
			}
		}

		$where = $k . '=' . implode(' OR ' . $k . ' = ', $pks);
		$query = $this->_db->getQuery(true)
			->update($this->_db->quoteName($this->_tbl))
			->set($this->_db->quoteName('published') . ' = ' . $published)
			->where($where);

		$this->_db->setQuery($query);

		try {
			$this->_db->execute();
		} catch (\RuntimeException $e) {
			$app->enqueueMessage($e->getMessage(), 'error');
			return false;
		}

		if (in_array($this->$k, $pks)) {
			$this->published = $published;
		}

		$app->enqueueMessage('', 'error');

		return true;
	}

	public function check()
	{

		$result = true;

		//actors
		if (is_array($this->actors)) {
			if (!empty($this->actors)) {
				$this->actors = json_encode($this->actors);
			}
		}
		if (is_null($this->actors) || empty($this->actors)) {
			$this->actors = '';
		}

		//directors
		if (is_array($this->directors)) {
			if (!empty($this->directors)) {
				$this->directors = json_encode($this->directors);
			}
		}
		if (is_null($this->directors) || empty($this->directors)) {
			$this->directors = '';
		}
		
		//albums
		if (is_array($this->albums)) {
			if (!empty($this->albums)) {
				$this->albums = json_encode($this->albums);
			}
		}
		if (is_null($this->albums) || empty($this->albums)) {
			$this->albums = '';
		}
		
		//genres
		if (is_array($this->genres)) {
			if (!empty($this->genres)) {
				$this->genres = json_encode($this->genres);
			}
		}
		if (is_null($this->genres) || empty($this->genres)) {
			$this->genres = '';
		}

		// show_time
		if (is_array($this->show_time)) {
			if (!empty($this->show_time)) {
				$this->show_time = json_encode($this->show_time);
			}
		}
		if (is_null($this->show_time) || empty($this->show_time)) {
			$this->show_time = '';
		}

		$this->profile_image = AvisamusicdbHelper::formatImageUrl($this->profile_image);
		$this->cover_image = AvisamusicdbHelper::formatImageUrl($this->cover_image);

		return $result;
	}

	public function onAfterLoad(&$result)
	{

		// actors
		if (!is_array($this->actors)) {
			if (!empty($this->actors)) {
				$this->actors = json_decode($this->actors, true);
			}
		}

		if (is_null($this->actors) || empty($this->actors)) {
			$this->actors = array();
		}

		// directors
		if (!is_array($this->directors)) {
			if (!empty($this->directors)) {
				$this->directors = json_decode($this->directors, true);
			}
		}

		if (is_null($this->directors) || empty($this->directors)) {
			$this->directors = array();
		}
		
		// albums
		if (!is_array($this->albums)) {
			if (!empty($this->albums)) {
				$this->albums = json_decode($this->albums, true);
			}
		}

		if (is_null($this->albums) || empty($this->albums)) {
			$this->albums = array();
		}
		

		// genres
		if (!is_array($this->genres)) {
			if (!empty($this->genres)) {
				$this->genres = json_decode($this->genres, true);
			}
		}

		if (is_null($this->genres) || empty($this->genres)) {
			$this->genres = array();
		}

		return $this->onAfterLoad($result);
	}
}
