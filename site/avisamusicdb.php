<?php

/**
 * @package    	Joomla.Administrator
 * @subpackage 	com_avisamusicdb
 * @author 		AvisaPro support@avisapro.ir
 * @copyright 	Copyright (c) 2020 - 2023 AvisaPro https://www.avisapro.ir
 * @license     GNU General Public License version 2 or later; see http://www.gnu.org/licenses/gpl-2.0.html
 */

// No Direct Access
defined('_JEXEC') or die('Restricted Access');

use Joomla\CMS\Factory;
use Joomla\CMS\Uri\Uri;
use Joomla\CMS\Language\Text;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\MVC\Controller\BaseController;

//helper & model
$com_helper = JPATH_BASE . '/components/com_avisamusicdb/helpers/helper.php';

if (file_exists($com_helper)) {
	require_once($com_helper);
} else {
	echo '<p class="alert alert-warning">' . Text::_('COM_AVISAMUSICDB_COMPONENT_NOT_INSTALLED_OR_MISSING_FILE') . '</p>';
	return;
}

HTMLHelper::_('jquery.framework');
$doc = Factory::getDocument();

// Include CSS files
$doc->addStylesheet(Uri::root(true) . '/components/com_avisamusicdb/assets/css/avisamusicdb-common.css');
$doc->addStylesheet(Uri::root(true) . '/components/com_avisamusicdb/assets/css/avisamusicdb-font.css');
$doc->addStylesheet(Uri::root(true) . '/components/com_avisamusicdb/assets/css/avisamusicdb.css');


$controller = BaseController::getInstance('Avisamusicdb');
$input 	= Factory::getApplication()->input;
$view = $input->getCmd('view', 'default');
$input->set('view', $view);
$controller->execute($input->getCmd('task'));
$controller->redirect();
