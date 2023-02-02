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

use Joomla\CMS\MVC\Controller\FormController;

class AvisamusicdbControllerCelebrities extends FormController
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
}
