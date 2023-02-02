<?php

/**
 * @package     Joomla.Site
 * @subpackage  mod_avisamusicdb_albums
 *
 * @copyright   Copyright (C) 2020 - 2023 AvisaPro. All rights reserved.
 * @license     GNU General Public License version 2 or later.
 */


// no direct access
defined('_JEXEC') or die('Restricted access!');

use Joomla\CMS\Factory;
use Joomla\CMS\Helper\ModuleHelper;
use Joomla\CMS\Uri\Uri;

require_once __DIR__ . '/helper.php';
require_once JPATH_BASE . '/components/com_avisamusicdb/helpers/helper.php';

$doc = Factory::getDocument();
$doc->addStylesheet(Uri::root(true) . '/components/com_avisamusicdb/assets/css/avisamusicdb-font.css');
$doc->addStylesheet(Uri::root(true) . '/modules/' . $module->module . '/assets/css/style.css');

// Get items
$items              = modAvisamusicdbAlbumsHelper::getAlbums($params);
$moduleclass_sfx    = htmlspecialchars($params->get('moduleclass_sfx'));

require ModuleHelper::getLayoutPath('mod_avisamusicdb_albums', $params->get('layout'));
