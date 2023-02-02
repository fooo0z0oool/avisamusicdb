<?php

/**
 * @package     Joomla.Site
 * @subpackage  mod_avisamusicdb_tab
 *
 * @copyright   Copyright (C) 2020 - 2023 AvisaPro. All rights reserved.
 * @license     GNU General Public License version 2 or later.
 */


// no direct access
defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\Helper\ModuleHelper;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Uri\Uri;
use Joomla\CMS\Version;

require_once __DIR__ . '/helper.php';
require_once JPATH_BASE . '/components/com_avisamusicdb/models/music.php';
require_once JPATH_BASE . '/components/com_avisamusicdb/helpers/helper.php';

// Load the method jquery script.
HTMLHelper::_('jquery.framework');
HTMLHelper::_('bootstrap.framework');

$limit = $params->get('limit', 6);

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
$doc->addScript(Uri::root(true) . '/modules/' . $module->module . '/assets/js/avisamusicdb-tab.js');
$doc->addStylesheet(Uri::root(true) . '/components/com_avisamusicdb/assets/css/avisamusicdb-font.css');

// Get items
$musics_lists = array();
$musics_lists['latest']['title'] = Text::_('MOD_AVISAMUSICDB_TAB_LATEST');
$musics_lists['latest']['musics'] = ModAvisamusicdbTabHelper::getMusics('latest', $limit);

if ($params->get('show_fetured')) {
	$musics_lists['featured']['title'] = Text::_('MOD_AVISAMUSICDB_TAB_FEATURED');
	$musics_lists['featured']['musics'] = ModAvisamusicdbTabHelper::getMusics('featured', $limit);
}

if ($params->get('show_comingsoon')) {
	$musics_lists['coming']['title'] = Text::_('MOD_AVISAMUSICDB_TAB_COMING_SOON');
	$musics_lists['coming']['musics'] = ModAvisamusicdbTabHelper::getMusics('coming', $limit);
}

if ($params->get('show_toprated')) {
	$musics_lists['toprated']['title'] = Text::_('MOD_AVISAMUSICDB_TAB_TOP_RATED');
	$musics_lists['toprated']['musics'] = ModAvisamusicdbTabHelper::getMusics('top', $limit);
}

if ($params->get('show_latesttrailer')) {
	$musics_lists['trailer']['title'] = Text::_('MOD_AVISAMUSICDB_TAB_TOP_LATEST_TRAILERS');
	$musics_lists['trailer']['musics'] = ModAvisamusicdbTabHelper::getMusics('ltrailers', $limit);
}

$moduleclass_sfx 	= htmlspecialchars($params->get('moduleclass_sfx'));

require ModuleHelper::getLayoutPath('mod_avisamusicdb_tab', $params->get('layout'));
