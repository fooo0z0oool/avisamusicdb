<?php
/**
 * @package     Avisa  Music Database
 *
 * @copyright   Copyright (C) 2020 - 2023 AvisaPro. All rights reserved.
 * @license     GNU General Public License version 2 or later.
 */


defined('_JEXEC') or die('Restricted Access');

use Joomla\CMS\Factory;
use Joomla\CMS\MVC\Model\ListModel;

class AvisamusicdbModelReview extends ListModel
{

	public function __construct($config = array())
    {
		parent::__construct($config);
	}

	public function storeReview($music_id = 0, $review = '', $rating = 1) {

		$db = Factory::getDbo();
		$query = $db->getQuery(true);
		$columns = array('musicid', 'review', 'rating', 'created_by', 'created', 'enabled');
		$values = array($db->quote($music_id), $db->quote($review), $db->quote($rating), Factory::getUser()->id, $db->quote(Factory::getDate()), 1);
		$query
		    ->insert($db->quoteName('#__avisamusicdb_reviews'))
		    ->columns($db->quoteName($columns))
		    ->values(implode(',', $values));
		 
		$db->setQuery($query);
		$db->execute();

		return $db->insertid();
	}

	public function updateReview($review_id, $review = '', $rating = 1) {

		$db = Factory::getDbo();
		$query = $db->getQuery(true);

		$fields = array(
			$db->quoteName('review') . ' = ' . $db->quote($review),
			$db->quoteName('rating') . ' = ' . $db->quote($rating),
			);

		$conditions = array(
			$db->quoteName('id') . ' = ' . $db->quote($review_id),
			$db->quoteName('created_by') . ' = ' . $db->quote(Factory::getUser()->id),
			);
		$query->update($db->quoteName('#__avisamusicdb_reviews'))->set($fields)->where($conditions);
		$db->setQuery($query);
		$db->execute();
	}

	public function getReview($review_id = 0) {
		$db = Factory::getDbo();
		$query = $db->getQuery(true);
		$query->select( array('a.*', 'b.email', 'b.name') );
	    $query->from($db->quoteName('#__avisamusicdb_reviews', 'a'));
	    $query->join('LEFT', $db->quoteName('#__users', 'b') . ' ON (' . $db->quoteName('a.created_by') . ' = ' . $db->quoteName('b.id') . ')');
	    $query->where($db->quoteName('a.id') . ' = ' . $db->quote($review_id));
	    $query->order($db->quoteName('a.created') . ' DESC');
	    
	    $db->setQuery($query);

	    $review = $db->loadObject();

	    if(!empty($review))
        {
	    	$review->gravatar = md5($review->email);
	    	$review->created_date = AvisamusicdbHelper::timeago($review->created);

	    	return $review;
	    }

	    return false;
	}

	public function getRatings($music_id) {
		$db = Factory::getDbo();
		$query = $db->getQuery(true);
		$query->select( array('COUNT(a.rating) AS count', 'SUM(a.rating) AS total') );
	    $query->from($db->quoteName('#__avisamusicdb_reviews', 'a'));
	    $query->where($db->quoteName('a.musicid') . ' = ' . $db->quote($music_id));
	    $db->setQuery($query);
		
		return $db->loadObject();
	}
}