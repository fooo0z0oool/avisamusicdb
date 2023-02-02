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

class AvisamusicdbControllerMyreviews extends FormController
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
}
