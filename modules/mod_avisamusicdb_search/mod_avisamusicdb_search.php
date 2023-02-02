<?php

/**
 * @package     Joomla.Site
 * @subpackage	mod_avisamusicdb_search
 * @copyright	Copyright (C) 2020 - 2023 AvisaPro. All rights reserved.
 * @license		GNU General Public License version 2 or later; 
 */


// no direct access
defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\Helper\ModuleHelper;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Uri\Uri;

// Load the method jquery script.
HTMLHelper::_('jquery.framework');

require_once __DIR__ . '/helper.php';

$doc = Factory::getDocument();
$doc->addStylesheet(Uri::root(true) . '/modules/' . $module->module . '/assets/css/style.css');
$doc->addScript(Uri::base(true) . '/modules/' . $module->module . '/assets/js/avisamusicdb-search.js');
$doc->addStylesheet(Uri::root(true) . '/components/com_avisamusicdb/assets/css/avisamusicdb-font.css');

require ModuleHelper::getLayoutPath('mod_avisamusicdb_search', $params->get('layout'));
