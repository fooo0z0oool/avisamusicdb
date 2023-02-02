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
use Joomla\CMS\MVC\Model\ListModel;

/**
 * Methods supporting a list of Music records.
 *
 * @since  1.0.0
 */
class AvisamusicdbModelMusics extends ListModel
{
	/**
	 * Constructor.
	 *
	 * @param   array  $config  An optional associative array of configuration settings.
	 *
	 * @since   1.0.0
	 * @see     JControllerLegacy
	 */
	public function __construct(array $config = array())
	{
		if (empty($config['filter_fields'])) {
			$config['filter_fields'] = [
				'id', 'a.id',
				'title', 'a.title',
				'ordering', 'a.ordering',
				'directors', 'a.directors',
				'actors', 'a.actors',
				'albums', 'a.albums',
				'genres', 'a.genres',
				'release_date', 'a.release_date',
				'created', 'a.created',
				'published', 'a.published'
			];
		}
		parent::__construct($config);
	}

	/**
	 * Method to auto-populate the model state.
	 *
	 * Note. Calling getState in this method will result in recursion.
	 *
	 * @param   string  $ordering   An optional ordering field.
	 * @param   string  $direction  An optional direction (asc|desc).
	 *
	 * @return  void
	 *
	 * @since   1.0.0
	 */
	protected function populateState($ordering = 'a.ordering', $direction = 'asc')
	{
		$app = Factory::getApplication();
		$context = $this->context;

		$search = $this->getUserStateFromRequest($this->context . '.filter.search', 'filter_search');
		$this->setState('filter.search', $search);

		$published = $this->getUserStateFromRequest($this->context . '.filter.published', 'filter_published', '');
		$this->setState('filter.published', $published);

		// $releaseDate = $this->getUserStateFromRequest($this->context . '.filter.release_date', 'filter_release_date', '');
		// $this->setState('filter.release_date', $releaseDate);

		$language = $this->getUserStateFromRequest($this->context . '.filter.language', 'filter_language', '');
		$this->setState('filter.language', $language);

		$formSubmited = $app->input->post->get('form_submited');

		$access = $this->getUserStateFromRequest($this->context . '.filter.access', 'filter_access');

		if ($formSubmited) {
			$access = $app->input->post->get('access');
			$this->setState('filter.access', $access);
		}

		parent::populateState($ordering, $direction);
	}

	/**
	 * Method to get a store id based on model configuration state.
	 *
	 * This is necessary because the model is used by the component and
	 * different modules that might need different sets of data or different
	 * ordering requirements.
	 *
	 * @param   string  \id  A prefix for the store id.
	 *
	 * @return  string  A store id.
	 *
	 * @since   1.0.0
	 */
	protected function getStoreId($id = '')
	{
		// Compile the store id.
		$id .= ':' . $this->getState('filter.search');
		$id .= ':' . serialize($this->getState('filter.access'));
		$id .= ':' . $this->getState('filter.published');
		$id .= ':' . $this->getState('filter.language');

		return parent::getStoreId($id);
	}

	/**
	 * Build an SQL query to load the list data.
	 *
	 * @return  JDatabaseQuery
	 *
	 * @since   1.0.0
	 */
	protected function getListQuery()
	{
		$app 	= Factory::getApplication();
		$state = $this->get('State');
		$db 	= Factory::getDbo();
		$query = $db->getQuery(true);

		$query->select('a.*');
		$query->from($db->quoteName('#__avisamusicdb_musics', 'a'));

		// Join over the users for the checked out user.
		$query->select('uc.name AS editor')
			->join('LEFT', '#__users AS uc ON uc.id=a.checked_out');

		$query->select('ua.name AS author_name')
			->join('LEFT', '#__users AS ua ON ua.id = a.created_by');


		//filter by user query
		$search = $this->getState('filter.search');
		if (!empty($search)) {
			$search = $db->quote('%' . str_replace(' ', '%', $db->escape(trim($search), true) . '%'));
			$query->where('a.title like ' . $search);
		}

		// Filter by status state
		$status = $this->getState('filter.published');
		if (is_numeric($status)) {
			$query->where('a.published = ' . (int) $status);
		} elseif ($status === '') {
			$query->where('(a.published = 0 OR a.published = 1)');
		}

		$orderCol 	= $this->getState('list.ordering', 'a.ordering');
		$orderDirn = $this->getState('list.direction', 'desc');

		$order = $db->escape($orderCol) . ' ' . $db->escape($orderDirn);
		$query->order($order);

		return $query;
	}
}
