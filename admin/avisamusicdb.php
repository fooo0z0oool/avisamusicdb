<?php

/**
 * @package    	Joomla.Administrator
 * @subpackage 	com_avisamusicdb
 * @author 		  AvisaPro support@avisapro.ir
 * @copyright 	Copyright (c) 2020 - 2023 AvisaPro https://www.avisapro.ir
 * @license     GNU General Public License version 2 or later; see http://www.gnu.org/licenses/gpl-2.0.html
 */

// No Direct Access
defined('_JEXEC') or die('Restricted Access');

use Joomla\CMS\Language\Text;
use Joomla\CMS\Factory;
use Joomla\CMS\Uri\Uri;
use Joomla\CMS\MVC\Controller\BaseController;
use Joomla\CMS\Access\Exception\NotAllowed;
use Joomla\CMS\HTML\HTMLHelper;

HTMLHelper::_('jquery.framework');

$doc = Factory::getDocument();
$doc->addStylesheet(Uri::root(true) . '/administrator/components/com_avisamusicdb/assets/css/avisamusicdb.css');
$doc->addStylesheet(Uri::root(true) . '/components/com_avisamusicdb/assets/css/avisamusicdb-font.css');
$doc->addScript(Uri::root(true) . '/administrator/components/com_avisamusicdb/assets/js/avisamusicdb.js');


if (!Factory::getUser()->authorise('core.manage', 'com_avisamusicdb')) {
  throw new NotAllowed(Text::_('JERROR_ALERTNOAUTHOR'), 403);
}

if (file_exists(JPATH_COMPONENT . '/helpers/avisamusicdb.php')) {
  JLoader::register('AvisamusicdbHelper', JPATH_COMPONENT . '/helpers/avisamusicdb.php');
}

// Execute the task.
$controller = BaseController::getInstance('Avisamusicdb');
$controller->execute(Factory::getApplication()->input->get('task'));
$controller->redirect();
