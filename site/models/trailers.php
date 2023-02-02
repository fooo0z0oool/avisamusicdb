<?php
/**
* @package    	Joomla.Site
* @subpackage 	com_avisamusicdb
* @author 		AvisaPro support@avisapro.ir
* @copyright 	Copyright (c) 2020 - 2023 AvisaPro <https://www.avisapro.ir>. All rights reserved.
* @license     	GNU General Public License version 2 or later; see http://www.gnu.org/licenses/gpl-2.0.html
*/

// No Direct Access
defined ('_JEXEC') or die('Restricted Access');

use Joomla\CMS\Factory;
use Joomla\CMS\MVC\Model\ListModel;
use Joomla\CMS\Language\Multilanguage;

class AvisamusicdbModelTrailers extends ListModel
{
	/**
     * Method to auto-populate the model state.
     *
     * @param string $ordering
     * @param string $direction
     * @return void
     */
	protected function populateState($ordering = null, $direction = null)
	{
		$app = Factory::getApplication('site');
		$this->setState('list.start', $app->input->get('limitstart', 0, 'uint'));
		$this->setState('filter.language', Multilanguage::isEnabled());
	}

	/**
	 * Method to get a JDatabaseQuery object for retrieving the data set from a database.
	 *
	 * @return  JDatabaseQuery   A JDatabaseQuery object to retrieve the data set.
	 *
	 * @since   1.0.0
	 */
	protected function getListQuery()
	{
		// Get Params
		$app = Factory::getApplication();
		$menu   = $app->getMenu()->getActive(); // get the active item
		$params = $menu->getParams();
		$order_by = $params->get('order_by', '');

		$db = $this->getDbo();
		$query = $db->getQuery(true);

		$query->select( array('a.*') );
	    $query->from($db->quoteName('#__avisamusicdb_musics', 'a'));
	    $query->where($db->quoteName('a.published')." = ".$db->quote(1));

	    $query->where($db->quoteName('trailer_one') . '!=""');

	    if ($order_by=='desc') {
			$query->order($db->quoteName('a.release_date') . ' DESC');
		} elseif ($order_by=='asc') {
			$query->order($db->quoteName('a.release_date') . ' ASC');
		} elseif ($order_by== 'featured') {
			$query->where($db->quoteName('a.featured') . ' = 1');
			$query->order($db->quoteName('a.release_date') . ' DESC');
		} else {
			$query->order($db->quoteName('a.ordering') . ' DESC');
		}
	    
	    //Language
		$query->where('a.language in (' . $db->quote(Factory::getLanguage()->getTag()) . ',' . $db->quote('*') . ')');
	    //Access
		$query->where($db->quoteName('a.access')." IN (" . implode( ',', Factory::getUser()->getAuthorisedViewLevels() ) . ")");

	    return $query;
	}
}
