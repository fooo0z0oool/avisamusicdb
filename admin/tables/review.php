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

/**
 * Review table
 *
 * @since  1.0.0
 */
class AvisamusicdbTableReview extends Table
{
	/**
	 * Constructor
	 *
	 * @param   JDatabaseDriver  $db  Database connector object
	 */
	public function __construct(&$db)
	{
		parent::__construct('#__avisamusicdb_reviews', 'id', $db);
	}

	/**
	 * Overloaded bind function
	 *
	 * @param   mixed  $array   An associative array or object to bind to the JTable instance.
	 * @param   mixed  $ignore  An optional array or space separated list of properties to ignore while binding.
	 *
	 * @return  boolean  True on success
	 */
	public function bind($src, $ignore = array())
	{
		return parent::bind($src, $ignore);
	}

	/**
	 * Method to store a row
	 *
	 * @param   boolean  $updateNulls  True to update fields even if they are null.
	 *
	 * @return  boolean  True on success, false on failure.
	 */
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


		return parent::store($updateNulls);
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

		$app->enqueueMessage('');

		return true;
	}
}
