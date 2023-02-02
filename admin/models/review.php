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

use Joomla\CMS\Factory;
use Joomla\CMS\Table\Table;
use Joomla\CMS\MVC\Model\AdminModel;
use Joomla\String\StringHelper;

/**
 * Review model.
 *
 * @since  1.0.0
 */
class AvisamusicdbModelReview extends AdminModel
{
	protected $text_prefix = 'COM_AVISAMUSICDB';

	/**
	 * Returns a JTable object, always creating it.
	 *
	 * @param string $type
	 * @param string $prefix
	 * @param array $config
	 * @return JTable
	 */
	public function getTable($name = 'Review', $prefix = 'AvisamusicdbTable', $config = array())
	{
		return Table::getInstance($name, $prefix, $config);
	}

	/**
	 * Method to get the record form.
	 *
	 * @param array $data
	 * @param boolean $loadData
	 * @return JForm
	 */
	public function getForm($data = array(), $loadData = true)
	{
		$app = Factory::getApplication();
		$form = $this->loadForm('com_avisamusicdb.review', 'review', array('control' => 'jform', 'load_data' => $loadData));

		if (empty($form)) {
			return false;
		}

		return $form;
	}

	/**
	 * Method to get the data that should be injected in the form.
	 *
	 * @return mixed
	 */
	public function loadFormData()
	{
		$data = Factory::getApplication()
			->getUserState('com_avisamusicdb.edit.review.data', array());

		if (empty($data)) {
			$data = $this->getItem();
		}

		return $data;
	}

	protected function canDelete($record)
	{
		if (!empty($record->id)) {
			if ($record->published != -2) {
				return;
			}

			$user = Factory::getUser();

			return parent::canDelete($record);
		}
	}

	protected function canEditState($record)
	{
		return parent::canEditState($record);
	}

	public function getItem($pk = null)
	{
		return parent::getItem($pk);
	}

	private function generateNewTitleLocally($alias, $title)
	{
		// Alter the title & alias
		$table = $this->getTable();

		while ($table->load(array('alias' => $alias))) {
			$title = StringHelper::increment($title);
			$alias = StringHelper::increment($alias, 'dash');
		}

		return array($title, $alias);
	}

	public function save($data)
	{
		$input = Factory::getApplication()->input;
		$task 	= $input->get('task');

		if ($task === 'save2copy') {
			$originalTable = clone $this->getTable();
			$originalTable->load($input->getInt('id'));

			if ($data['title'] == $originalTable->title) {
				list($title, $alias) = $this->generateNewTitleLocally($data['alias'], $data['title']);
				$data['title'] = $title;
				$data['alias'] = $alias;
			} else {
				if ($data['alias'] === $originalTable->alias) {
					$data['alias'] = '';
				}
			}

			$data['published'] = 0;
		}
		if (parent::save($data))
			return true;
		return false;
	}
}
