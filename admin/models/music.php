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
use Joomla\CMS\Filter\OutputFilter;
use Joomla\CMS\Table\Table;
use Joomla\CMS\MVC\Model\AdminModel;
use Joomla\String\StringHelper;

class AvisamusicdbModelMusic extends AdminModel
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
	public function getTable($name = 'Music', $prefix = 'AvisamusicdbTable', $config = array())
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
		$form = $this->loadForm('com_avisamusicdb.music', 'music', array('control' => 'jform', 'load_data' => $loadData));

		if (empty($form)) {
			return false;
		}

		return $form;
	}

	public function loadFormData()
	{
		$data = Factory::getApplication()->getUserState('com_avisamusicdb.edit.music.data', array());

		if (empty($data)) {
			$data = $this->getItem();
			$data->show_time = AvisamusicdbHelper::repeatableToFormRepeatable($data->show_time, 'show_time');
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

	protected function onBeforeSave(&$data, &$table)
	{
		$actors = $this->input->get('actors', NULL, 'ARRAY');
		$directors = $this->input->get('directors', NULL, 'ARRAY');
		$albums = $this->input->get('albums', NULL, 'ARRAY');
		
		$data['directors'] = json_encode(self::addCelebrities($directors));
		$data['actors'] = json_encode(self::addCelebrities($actors));
		$data['albums'] = json_encode(self::addAlbums($albums));

		return true;
	}
	
	private static function addCelebrities($actors = array())
	{
		$db = Factory::getDbo();
		$newActors = array();
		$plainNewActors = array();
		$allActors = array();
		$i = 0;

		foreach ($actors as $actor) {
			if (strpos($actor, '#new#') !== false) {
				$title = str_replace('#new#', '', $actor);
				$newActors[$i][] = $db->quote($title);
				$newActors[$i][] = $db->quote(OutputFilter::stringURLSafe($title));
				$plainNewActors[] = $title;
				$i++;
			} else {
				$allActors[] = $actor;
			}
		}
				
		// Insert New
		foreach ($newActors as $values) {

			if (!self::checkAlias($values[1])) {
				$db = Factory::getDbo();
				$query = $db->getQuery(true);
				$columns = array('title', 'alias');
				$query
					->insert($db->quoteName('#__avisamusicdb_celebrities'))
					->columns($db->quoteName($columns))
					->values(implode(',', $values));

				$db->setQuery($query);
				$db->execute();

				$allActors[] = $db->insertid();
			}
		}
		return $allActors;

	}
	
	private static function addAlbums($albums = array())
	{
		$db = Factory::getDbo();
		$newAlbums = array();
		$plainNewAlbums = array();
		$allAlbums = array();
		$i = 0;

		foreach ($albums as $album) {
			if (strpos($album, '#new#') !== false) {
				$title = str_replace('#new#', '', $album);
				$newAlbums[$i][] = $db->quote($title);
				$newAlbums[$i][] = $db->quote(OutputFilter::stringURLSafe($title));
				$plainNewAlbums[] = $title;
				$i++;
			} else {
				$allAlbums[] = $album;
			}
		}
				
		// Insert New
		foreach ($newAlbums as $values) {

			if (!self::checkAlias($values[1])) {
				$db = Factory::getDbo();
				$query = $db->getQuery(true);
				$columns = array('title', 'alias');
				$query
					->insert($db->quoteName('#__avisamusicdb_albums'))
					->columns($db->quoteName($columns))
					->values(implode(',', $values));

				$db->setQuery($query);
				$db->execute();

				$allAlbums[] = $db->insertid();
			}
		}
		return $allAlbums;
	}
	
	private static function checkAlias($alias = '')
	{

		$db = Factory::getDbo();
		$query = $db->getQuery(true);
		$query->select($db->quoteName(array('alias')));
		$query->from($db->quoteName('#__avisamusicdb_celebrities'));
		$query->where($db->quoteName('alias') . ' = ' . $alias);
		$db->setQuery($query);
		$results = $db->loadObjectList();

		return count($results);
	}

}
