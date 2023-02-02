<?php

/**
 * @package     Joomla.Site
 * @subpackage  mod_avisamusicdb_search
 *
 * @copyright   Copyright (C) 2020 - 2023 AvisaPro. All rights reserved.
 * @license     GNU General Public License version 2 or later.
 */


// no direct access
defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Uri\Uri;

$input = Factory::getApplication()->input;
$searchword = $input->get('searchword', '', 'STRING');
$searchtype = $input->get('type', '', 'STRING');

?>

<div id="mod_avisamusicdb_search<?php echo $module->id; ?>" class="mod-avisamusicdb-search musicdb_search <?php echo $params->get('moduleclass_sfx') ?>">
	<div class="input-group musicdb-search-wrap">
		<form id="musicdb-search">
			<div class="search-panel">
				<div class="select-menu">
					<select name="searchtype" id="searchtype" class="selectpicker">
						<option value="musics" <?php echo ($searchtype == 'musics') ? 'selected="selected"' : ''; ?>>
							<?php echo Text::_('MOD_AVISAMUSICDBSEARCH_MUSICS'); ?>
						</option>
						<option value="celebrities" <?php echo ($searchtype == 'celebrities') ? 'selected="selected"' : ''; ?>>
							<?php echo Text::_('MOD_AVISAMUSICDBSEARCH_CELEBRITIES'); ?>
						</option>
						<option value="trailers" <?php echo ($searchtype == 'trailers') ? 'selected="selected"' : ''; ?>>
							<?php echo Text::_('MOD_AVISAMUSICDBSEARCH_TRAILERS'); ?>
						</option>
						<option value="genres" <?php echo ($searchtype == 'genres') ? 'selected="selected"' : ''; ?>>
							<?php echo Text::_('MOD_AVISAMUSICDBSEARCH_GENRES'); ?>
						</option>
					</select>
				</div>
			</div>
			<div class="input-box">
				<input type="hidden" id="rooturl" name="rooturl" value="<?php echo Uri::root(); ?>">
				<input type="hidden" id="mid" name="rooturl" value="<?php echo AvisamusicdbHelper::getItemid('musics'); ?>">
				<input type="hidden" id="cid" name="rooturl" value="<?php echo AvisamusicdbHelper::getItemid('celebrities'); ?>">
				<input type="hidden" id="tid" name="rooturl" value="<?php echo AvisamusicdbHelper::getItemid('trailers'); ?>">
				<input type="text" id="searchword" name="searchword" class="avisamusicdb-search-input form-control" value="<?php echo $searchword; ?>" placeholder="<?php echo Text::_('MOD_AVISAMUSICDBSEARCH_PLACEHOLDER'); ?>" autocomplete="off">
			</div>
			<span class="search-icon">
				<button type="submit" class="avisamusicdb-search-submit">
					<span class="avisamusicdb-search-icons">
						<i class="avisamusicdb-icon-search"></i>
					</span>
				</button>
			</span>
		</form>
		<div class="avisamusicdb-search-results"></div>
	</div>
</div>