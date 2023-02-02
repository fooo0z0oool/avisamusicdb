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

class AvisamusicdbModelCelebrities extends ListModel
{

	/**
	 * Method to get a JDatabaseQuery object for retrieving the data set from a database.
	 *
	 * @return  JDatabaseQuery   A JDatabaseQuery object to retrieve the data set.
	 *
	 * @since   1.0.0
	 */
	public function getListQuery($overrideLimits = false)
	{
		if (Factory::getApplication()->isClient('site')) {
			// Get Params
			$app 			= Factory::getApplication();
			$params   		= $app->getMenu()->getActive()->getParams(); // get the active item
			$order_by 		= $params->get('order_by', '');
			$celebrity_type = $params->get('celebrities_type', '');
			$gender 		= $params->get('gender', '');
		}

		$inputs = Factory::getApplication()->input;
		$alphaindex = $inputs->get('alphaindex', '', 'WORD');

		$db = $this->getDbo();
		$query = $db->getQuery(true);


		$query->select(array('a.*'));
		$query->from($db->quoteName('#__avisamusicdb_celebrities', 'a'));

		if (Factory::getApplication()->isClient('site')) {

			if ($alphaindex) {
				$query->where($db->quoteName('a.title') . " LIKE " . $db->quote(strtolower($alphaindex) . '%'));
			}

			$query->where($db->quoteName('a.published') . " = " . $db->quote('1'));

			if ($order_by == 'desc') {
				$query->order($db->quoteName('a.created') . ' DESC');
			} elseif ($order_by == 'asc') {
				$query->order($db->quoteName('a.created') . ' ASC');
			} elseif ($order_by == 'featured') {
				$query->where($db->quoteName('a.featured') . ' = 1');
				$query->order($db->quoteName('a.ordering') . ' DESC');
			} else {
				$query->order($db->quoteName('a.ordering') . ' DESC');
			}

			if ($celebrity_type == 'actors') {
				$query->where('a.celebrity_type in (' . $db->quote('actor') . ',' . $db->quote('both') . ')');
			} elseif ($celebrity_type == 'directors') {
				$query->where('a.celebrity_type in (' . $db->quote('director') . ',' . $db->quote('both') . ')');
			}

			if ($gender == 'male') {
				$query->where($db->quoteName('a.gender') . ' = ' . $db->quote('male'));
			} elseif ($gender == 'female') {
				$query->where($db->quoteName('a.gender') . ' = ' . $db->quote('female'));
			} elseif ($gender == 'others') {
				$query->where($db->quoteName('a.gender') . ' = ' . $db->quote('others'));
			}

			//Language
			$query->where('a.language in (' . $db->quote(Factory::getLanguage()->getTag()) . ',' . $db->quote('*') . ')');
			//Access
			$query->where($db->quoteName('a.access') . " IN (" . implode(',', Factory::getUser()->getAuthorisedViewLevels()) . ")");
		} else {
			$query->order($db->quoteName('a.ordering') . ' DESC');
		}

		return $query;
	}

	/**
	 * get total celebrity
	 *
	 * @return mixed
	 */
	public static function getCountCelebrities()
	{
		$db = Factory::getDbo();
		$query = $db->getQuery(true);
		$query->select(array('COUNT(a.id) as total_celebrities'));
		$query->from($db->quoteName('#__avisamusicdb_celebrities', 'a'));
		$query->where($db->quoteName('a.published') . " = " . $db->quote('1'));
		$db->setQuery($query);
		return $db->loadObject();
	}
}
