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

class AvisamusicdbTableCelebrity extends Table
{
	public function __construct(&$db)
	{
		parent::__construct('#__avisamusicdb_celebrities', 'id', $db);
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

		$table = Table::getInstance('Celebrity', 'AvisamusicdbTable');

		if ($table->load(['alias' => $this->alias]) && ($table->id != $this->id || $this->id == 0)) {
			$app->enqueueMessage(Text::_('COM_AVISAMUSICDB_ERROR_UNIQUE_ALIAS'), 'error');
			return false;
		}

		return parent::store($updateNulls);
	}

	public function check()
	{
		if (trim($this->title) == '') {
			throw new UnexpectedValueException(sprintf('The title is empty'));
		}

		$this->profile_image = AvisamusicdbHelper::formatImageUrl($this->profile_image);
		$this->cover_image = AvisamusicdbHelper::formatImageUrl($this->cover_image);

		$this->handleAlias();

		return true;
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
		$k = $this->_tbl_key;
		ArrayHelper::toInteger($pks);
		$publilshed = (int) $published;
		$app  = Factory::getApplication();

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
}
