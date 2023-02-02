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

use Joomla\CMS\Factory;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Layout\LayoutHelper;
use Joomla\CMS\Uri\Uri;


HTMLHelper::_('jquery.framework');
$doc = Factory::getDocument();
$doc->addScript(Uri::base(true) . '/components/com_avisamusicdb/assets/js/avisamusicdb.js');

$doc->addScriptdeclaration('var avisamusicdb_url="' . Uri::base() . 'index.php?option=com_avisamusicdb";');

?>

<div id="com-avisa-musicdb" class="avisamusicdb view-trailers avisa-musicdb-view-items">

    <div class="musicdb-view-top-wrap">
        <p class="search-result-title"><?php echo Text::_('COM_AVISAMUSICDB_SEARCH_RESULTS'); ?></p>
    </div>

    <?php if (!count($this->items)) { ?>
        <div class="alert alert-warning">
            <?php echo Text::_('COM_AVISAMUSICDB_NOTHING_FOUND'); ?>
        </div>
    <?php } ?>

    <?php foreach (array_chunk($this->items, $this->columns) as $this->items) { ?>
        <div class="avisamusicdb-row">
            <?php foreach ($this->items as $item) {
                $item->item_id = (isset($item->id) && $item->id) ? 'item-id-' . $item->id : '';
            ?>
                <div class="item avisamusicdb-col-xs-12 avisamusicdb-col-sm-6 avisamusicdb-col-md-6 avisamusicdb-col-lg-<?php echo round(12 / $this->columns); ?> <?php echo $item->item_id; ?>">
                    <div class="music-poster">
                        <img src="<?php echo Uri::root() . $item->profile_image; ?>" alt="<?php echo $item->title; ?>">
                        <?php if (isset($item->id) && $item->id) { ?>
                            <?php if (isset($item->turls[0]) && $item->turls[0]) { ?>
                                <a href="javascript:void(0);" data-id="<?php echo $item->id; ?>" class="play-icon show-music-trailers">
                                    <i class="avisamusicdb-icon-play"></i>
                                </a>
                            <?php } else { ?>
                                <a href="<?php echo $item->url; ?>" class="play-icon">
                                    <i class="avisamusicdb-icon-enter"></i>
                                </a>
                            <?php } ?>
                        <?php } else { ?>
                            <a href="<?php echo $item->url; ?>" class="play-icon">
                                <i class="avisamusicdb-icon-enter"></i>
                            </a>
                        <?php } ?>
                    </div>
                    <div class="music-details">
                        <div class="music-name">
                            <a href="<?php echo $item->url; ?>">
                                <h4 class="music-title"><?php echo $item->title; ?></h4>
                            </a>

                            <?php if (isset($item->id) && $item->id) { ?>
                                <span><?php echo LayoutHelper::render('music.genres', array('genres' => $item->genres)); ?></span>
                            <?php } ?>
                        </div>
                    </div>
                </div>
            <?php } ?>
        </div>
    <?php } ?>

    <?php
    if ($this->pagination->pagesTotal > 1) { ?>
        <?php echo $this->pagination->getPagesLinks(); ?>
    <?php } ?>
</div>