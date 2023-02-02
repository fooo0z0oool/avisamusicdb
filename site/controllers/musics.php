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
use Joomla\CMS\Layout\LayoutHelper;
use Joomla\CMS\MVC\Controller\FormController;

class AvisamusicdbControllerMusics extends FormController
{
	/**
	 * Constructor
	 *
	 * @param array 
	 */
	public function __construct($config = array())
	{
		parent::__construct($config);
	}

	public function getModel($name = 'Musics', $prefix = 'AvisamusicdbModel', $config = array())
	{
		return parent::getModel($name = 'Musics', $prefix = 'AvisamusicdbModel', $config = array());
	}

	/**
	 * Method to display a view.
	 *
	 * @param boolean $cachable
	 * @param boolean $urlparams
	 * @param string $tpl
	 * @return void
	 */
	public function display($cachable = false, $urlparams = false, $tpl = null)
	{
		$cachable = true;
		if (!is_array($urlparams)) {
			$urlparams = [];
		}
		$additionalParams = array(
			'catid' => 'INT',
			'id' => 'INT',
			'cid' => 'ARRAY',
			'year' => 'INT',
			'month' => 'INT',
			'alphaindex' => 'STRING',
			'yearindex' => 'STRING',
			'limit' => 'UINT',
			'limitstart' => 'UINT',
			'showall' => 'INT',
			'return' => 'BASE64',
			'filter' => 'STRING',
			'filter_order' => 'CMD',
			'filter_order_Dir' => 'CMD',
			'filter-search' => 'STRING',
			'print' => 'BOOLEAN',
			'lang' => 'CMD',
			'Itemid' => 'INT'
		);

		$urlparams = array_merge($additionalParams, $urlparams);
		parent::display($cachable, $urlparams, $tpl);
	}

	/**
	 * Get trailers
	 *
	 * @return string JSON string
	 */
	public function trailers()
	{
		$model = $this->getModel();
		$input = Factory::getApplication()->input;
		$id = $input->get('id', 0, 'INT');;

		if (!$id) {
			$output['status'] = false;
			echo json_encode($output);
			die();
		}

		$trailers    = $model->GenerateTrailers($id);
		$musics_info = $model->getMusicById($id);

		$output = array();

		if (count($trailers)) {
			$output['status'] = true;
			$output['content'] = LayoutHelper::render('music.trailer', array('trailers' => $trailers, 'music_info' => $musics_info));
		} else {
			$output['status'] = false;
		}

		echo json_encode($output);
		die();
	}
}
