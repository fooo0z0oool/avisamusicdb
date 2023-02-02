<?php
/**
 * @package     Avisa  Music Database
 *
 * @copyright   Copyright (C) 2020 - 2023 AvisaPro. All rights reserved.
 * @license     GNU General Public License version 2 or later.
 */

defined('_JEXEC') or die('Restricted Access');

use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Layout\LayoutHelper;
use Joomla\CMS\Component\ComponentHelper;
use Joomla\CMS\MVC\Controller\FormController;

class AvisamusicdbControllerReview extends FormController {

	public function __construct($config = array())
	{
		parent::__construct($config);
	}

	public function getModel($name = 'Review', $prefix = 'AvisamusicdbModel', $config = array())
	{
		return parent::getModel($name, $prefix, $config);
	}
	
	public function add_review()
	{
		$model = $this->getModel();
		$user = Factory::getUser();
		$input = Factory::getApplication()->input;
		$output = array();

		if(!$user->id) {
			$output['status'] = false;
			$output['content'] = Text::_('COM_AVISAMUSICDB_LOGIN_TO_REVIEW');
			echo json_encode($output);
			die();
		}

		$music_id 			= $input->post->get('music_id', 0, 'INT');
		$review 			= $input->post->get('review', NULL, 'STRING');
		$rating 			= $input->post->get('rating', 0, 'INT');
		$existing_review_id = $input->post->get('review_id', 0, 'INT');
		
		$output['status'] = false;

		if($rating && $music_id) {

			if($existing_review_id) {
				$model->updateReview($existing_review_id, $review, $rating);
				$review_output = $model->getReview($existing_review_id);
				$output['update'] = true;
				
			} else {
				$review_id = $model->storeReview($music_id, $review, $rating);
				$review_output = $model->getReview($review_id);
				$output['update'] = false;
			}

			$output['content'] = LayoutHelper::render('review.review', array( 'review'=>$review_output));
			$output['ratings'] = $model->getRatings($music_id);;
			$output['status'] = true;
		}

		echo json_encode($output);
		die();
	}

	public function reviews()
	{

		$params = ComponentHelper::getParams('com_avisamusicdb');
		$model = $this->getModel();
		$user = Factory::getUser();
		$input = Factory::getApplication()->input;
		$start 	= $input->post->get('start', 0, 'INT');
		$limit 	= $params->get('review_limit', 12);
		$musicModel = $this->getModel('Musics', 'AvisamusicdbModel');

		$music_id 		= $input->post->get('music_id', 0, 'INT');
		$reviews 		= $musicModel->getReviews($music_id);
		$total 			= $musicModel->getTotalReviews($music_id);

		$output = array();
		$output['status']  = false;
		$output['content'] = '';

		// Load More
		if($total > ($limit + $start)) {
			$output['loadmore'] 	= true;
		} else {
			$output['loadmore'] 	= false;	
		}

		foreach ($reviews as $key => $review) {
			$output['status']   = true;
			$output['content'] .= LayoutHelper::render('review.review', array( 'review'=>$review));
		}

		echo json_encode($output);
		die();
	}
}