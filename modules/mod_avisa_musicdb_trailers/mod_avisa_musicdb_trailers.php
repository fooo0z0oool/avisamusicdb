<?php

/**
 * @package     Joomla.Site
 * @subpackage  mod_avisa_musicdb_trailers
 * @copyright   Copyright (C) 2020 - 2023 AvisaPro. All rights reserved.
 * @license     GNU General Public License version 2 or later.
 */


//no direct access
defined('_JEXEC') or die('No direct access');

use Joomla\CMS\Factory;
use Joomla\CMS\Helper\ModuleHelper;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Uri\Uri;

HTMLHelper::_('jquery.framework');

require_once __DIR__ . '/helper.php';
require_once JPATH_BASE . '/components/com_avisamusicdb/models/music.php';
require_once JPATH_BASE . '/components/com_avisamusicdb/models/musics.php';
require_once JPATH_BASE . '/components/com_avisamusicdb/helpers/helper.php';

//includes js and css
$doc = Factory::getDocument();
$doc->addStylesheet(Uri::root(true) . '/components/com_avisamusicdb/assets/css/avisamusicdb-common.css');
$doc->addScript(Uri::base(true) . '/modules/' . $module->module . '/assets/js/avisamusicdb-trailers.js');
$doc->addStylesheet(Uri::root(true) . '/components/com_avisamusicdb/assets/css/avisamusicdb-font.css');
$doc->addStylesheet(Uri::root(true) . '/modules/' . $module->module . '/assets/css/style.css');

// Get items
$items               = ModAvisamusicdbTrailersHelper::getTrailers($params);
$moduleclass_sfx     = htmlspecialchars($params->get('moduleclass_sfx'));

require ModuleHelper::getLayoutPath('mod_avisa_musicdb_trailers', $params->get('layout'));
