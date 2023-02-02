<?php

/**
 * @package     Joomla.Site
 * @subpackage  mod_avisamusicdb_tab
 *
 * @copyright   Copyright (C) 2020 - 2023 AvisaPro. All rights reserved.
 * @license     GNU General Public License version 2 or later.
 */


// no direct access
defined('_JEXEC') or die('restricted aceess');

use Joomla\CMS\Uri\Uri;
use Joomla\CMS\Version;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Layout\FileLayout;

$genres = new FileLayout('music.genres', $basePath = JPATH_ROOT . '/components/com_avisamusicdb/layouts');

$autoplay = ($params->get('autoplay')) ? 'data-autoplay="true"' : 'data-autoplay="false"';
$slidelimit = ($params->get('slidelimit')) ? 'data-slidelimit="' . $params->get('slidelimit') . '"' : 'data-slidelimit="4"';

?>

<div id="avisa-avisamusicdb-tab" class="mod-avisamusicdb-tab avisamusicdb-tab module-id-<?php echo $module->id; ?> <?php echo $params->get('moduleclass_sfx') ?>">

    <ul class="nav nav-tabs" role="tablist">
        <?php foreach ($musics_lists as $key => $section) { ?>
            <?php if (Version::MAJOR_VERSION < 4) : ?>
                <li role="presentation" class="nav-item">
                    <a class="nav-link <?php echo ($key == 'latest') ? 'active' : ''; ?>" href="#avisa-avisamusicdb-tab-<?php echo $key; ?>" aria-controls="avisa-avisamusicdb-tab-<?php echo $key; ?>" role="tab" data-toggle="tab" data-bs-toggle="tab">
                        <?php echo $section['title']; ?>
                    </a>
                </li>
            <?php else : ?>
                <li role="presentation" class="nav-item">
                    <a
                        href="#" 
                        class="nav-link <?php echo ($key == 'latest') ? 'active' : ''; ?>" 
                        role="tab" data-toggle="tab" 
                        aria-controls="avisa-avisamusicdb-tab-<?php echo $key; ?>"
                        data-bs-target="#avisa-avisamusicdb-tab-<?php echo $key; ?>"
                        data-bs-toggle="tab">
                        <?php echo $section['title']; ?>
                    </a>
                </li>
            <?php endif; ?>
        <?php } ?>
    </ul><!-- /.nav-tabs -->

    <!-- Tab panes -->
    <div class="tab-content">
        <?php foreach ($musics_lists as $key => $musics_list) { ?>
            <div id="avisa-avisamusicdb-tab-<?php echo $key; ?>" class="avisamusicdb-tab-wrap owl-carousel show fade in tab-pane<?php echo ($key == 'latest') ? ' active' : ''; ?>" role="tabpanel" <?php echo $autoplay; ?> <?php echo $slidelimit; ?>>
                <?php foreach ($musics_list['musics'] as $music) { ?>
                    <div class="item">
                        <div class="music-poster">
                            <img src="<?php echo Uri::root() . $music->profile_image; ?>" alt="">
                            <?php if ((count($music->turls) > 0) && $music->turls) { ?>
                                <a class="play-icon play-video" href="<?php echo $music->turls[0]['src']; ?>" data-type="<?php echo $music->turls[0]['host']; ?>">
                                    <i class="avisamusicdb-icon-play"></i>
                                </a>
                            <?php } else { ?>
                                <a class="play-icon" href="<?php echo $music->url; ?>">
                                    <i class="avisamusicdb-icon-enter"></i>
                                </a>
                            <?php } ?>
                        </div>
                        <div class="music-details">
                            <?php
                            if (isset($music->ratings) && $music->ratings->count) {
                                $rating = round($music->ratings->total / $music->ratings->count);
                            } else {
                                $rating = 0;
                            }
                            ?>
                            <div class="avisa-musicdb-rating-wrapper">
                                <div class="avisa-musicdb-rating">
                                    <span class="star active"></span>
                                </div>
                                <span class="avisamusicdb-rating-summary"><span><?php echo $rating; ?></span>/<?php echo Text::_('COM_AVISAMUSICDB_RATING_MAX'); ?></span>
                            </div>
                            <div class="music-name">
                                <a href="<?php echo $music->url; ?>">
                                    <h3 class="music-title"><?php echo $music->title; ?></h3>
                                </a>
                                <span><?php echo $genres->render(array('genres' => $music->genres)); ?></span>
                            </div>
                        </div>
                    </div>
                <?php } ?>
            </div>
        <?php } ?>
    </div><!-- /.tab-content -->

    <div class="mod-avisamusicdb-music video-container">
        <span class="video-close"><i class="avisamusicdb-icon-close"></i></span>
    </div><!-- /.video-container -->
</div> <!-- /.avisa-avisamusicdb-tab -->