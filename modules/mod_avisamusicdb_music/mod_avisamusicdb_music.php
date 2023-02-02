<?php

/**
 * @package     Joomla.Site
 * @subpackage  mod_avisamusicdb_music
 *
 * @copyright   Copyright (C) 2020 - 2023 AvisaPro. All rights reserved.
 * @license     GNU General Public License version 2 or later.
 */


// no direct access
defined('_JEXEC') or die('Restricted access!');

use Joomla\CMS\Factory;
use Joomla\CMS\Uri\Uri;
use Joomla\CMS\Version;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Helper\ModuleHelper;

require_once __DIR__ . '/helper.php';
require_once JPATH_BASE . '/components/com_avisamusicdb/models/music.php';
require_once JPATH_BASE . '/components/com_avisamusicdb/helpers/helper.php';

// Load the method jquery script.
HTMLHelper::_('jquery.framework');

$doc = Factory::getDocument();
if (Version::MAJOR_VERSION < 4) {
    $doc->addStylesheet(Uri::root(true) . '/modules/' . $module->module . '/assets/css/owl.carousel.css');
    $doc->addStylesheet(Uri::root(true) . '/modules/' . $module->module . '/assets/css/owl.theme.css');
    $doc->addScript(Uri::root(true) . '/modules/' . $module->module . '/assets/js/owl.carousel.min.js');
} else {
    //joomla version 4
    $doc->addStylesheet(Uri::root(true) . '/modules/' . $module->module . '/assets/css/owl.carousel-2.3.4.min.css');
    $doc->addStylesheet(Uri::root(true) . '/modules/' . $module->module . '/assets/css/owl.theme.default-2.3.4.min.css');
    $doc->addScript(Uri::root(true) . '/modules/' . $module->module . '/assets/js/owl.carousel-2.3.4.min.js');
}

$doc->addStylesheet(Uri::root(true) . '/modules/' . $module->module . '/assets/css/style.css');
$doc->addScript(Uri::root(true) . '/modules/' . $module->module . '/assets/js/main.js');
$doc->addStylesheet(Uri::root(true) . '/components/com_avisamusicdb/assets/css/avisamusicdb-font.css');

// Get items
$items             = ModAvisamusicdbMusicHelper::getMusics($params);
$moduleclass_sfx   = htmlspecialchars($params->get('moduleclass_sfx'));

require ModuleHelper::getLayoutPath('mod_avisamusicdb_music', $params->get('layout'));
