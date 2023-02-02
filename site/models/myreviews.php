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
use Joomla\CMS\Router\Route;

class AvisamusicdbModelMyreviews extends ListModel
{
	/**
	 * Method to get a JDatabaseQuery object for retrieving the data set from a database.
	 *
	 * @return  JDatabaseQuery   A JDatabaseQuery object to retrieve the data set.
	 *
	 * @since   1.0.0
	 */
	protected function getListQuery()
	{
		$db = $this->getDbo();
		$query = $db->getQuery(true);

		$query->select( array('a.*') );
	    $query->from($db->quoteName('#__avisamusicdb_reviews', 'a'));
	    $query->where($db->quoteName('a.published')." = ".$db->quote(1));

	    $query->order($db->quoteName('a.created') . ' DESC');

	    return $query;
	}

	/**
	 * Get reviews by user id
	 *
	 * @param int $user_id
	 * @return mixed
	 */
	public function getMyReviews($user_id) {

		$db = Factory::getDbo();
		$query = $db->getQuery(true);
		$query->select( array('a.*', 'b.id', 'b.title', 'b.alias') );
    	$query->from($db->quoteName('#__avisamusicdb_reviews', 'a'));
    	$query->join('LEFT', $db->quoteName('#__avisamusicdb_musics', 'b') . ' ON (' . $db->quoteName('a.musicid') . ' = ' . $db->quoteName('b.id') . ')');
	    $query->where($db->quoteName('a.created_by') . ' = ' . $db->quote($user_id));
	    $query->where($db->quoteName('a.published')." = ".$db->quote('1'));
	    $db->setQuery($query);

	    $reviews = $db->loadObjectList();

	    foreach ($reviews as $review) {
	    	$review->url = Route::_('index.php?option=com_avisamusicdb&view=music&id=' . $review->musicid . ':' . $review->alias . AvisamusicdbHelper::getItemid('musics'));
	    }

	    return $reviews;
	}
}
