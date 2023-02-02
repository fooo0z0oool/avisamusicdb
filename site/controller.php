<?php

/**
 * @package    	Joomla.Site
 * @subpackage 	com_avisamusicdb
 * @author 		  AvisaPro support@avisapro.ir
 * @copyright 	Copyright (c) 2020 - 2023 AvisaPro https://www.avisapro.ir
 * @license     GNU General Public License version 2 or later; see http://www.gnu.org/licenses/gpl-2.0.html
 */

// No Direct Access
defined('_JEXEC') or die('Restricted Access');

use Joomla\CMS\MVC\Controller\BaseController;

class AvisamusicdbController extends BaseController
{
  public function display($cachable = false, $urlparams = false)
  {
    parent::display($cachable, $urlparams);
    return $this;
  }
}