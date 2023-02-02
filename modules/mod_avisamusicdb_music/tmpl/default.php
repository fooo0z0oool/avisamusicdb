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

use Joomla\CMS\Language\Text;
use Joomla\CMS\Layout\FileLayout;
use Joomla\CMS\Uri\Uri;

$autoplay = ($params->get('autoplay')) ? 'data-autoplay="true"' : 'data-autoplay="false"';
$slidelimit = ($params->get('slidelimit')) ? 'data-slidelimit="' . $params->get('slidelimit') . '"' : 'data-slidelimit="5"';

?>

<div id="avisamusicdb-music" class="mod-avisamusicdb-music moduleid-<?php echo $module->id; ?> avisamusicdb-trailers <?php echo $params->get('moduleclass_sfx') ?>">
    <div class="row-fluid">
        <div class="avisa-mu-music owl-carousel" <?php echo $autoplay; ?> <?php echo $slidelimit; ?>>
            <?php foreach ($items as $music) { ?>
                <div class="item">
                    <div class="music-poster">
                        <img src="<?php echo Uri::root() . $music->profile_image; ?>" alt="">
                    </div> <!-- /.music-poster -->
                    <?php if ((count($music->turls) > 0) && $music->turls) { ?>
                        <a class="play-icon play-video" href="<?php echo $music->turls[0]['src']; ?>" data-type="<?php echo $music->turls[0]['host']; ?>">
                            <i class="avisamusicdb-icon-play"></i>
                        </a>
                    <?php } else { ?>
                        <a class="play-icon" href="<?php echo $music->url; ?>">
                            <i class="avisamusicdb-icon-enter"></i>
                        </a>
                    <?php } ?>
                    <div class="music-details">
                        <?php if (isset($music->ratings) && $music->ratings->count) {
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
                            <h2 class="music-title"><a href="<?php echo $music->url; ?>"><?php echo $music->title; ?></a></h2>
                            <span class="tag">
                                <?php
                                $genres = new FileLayout('music.genres', $basePath = JPATH_ROOT . '/components/com_avisamusicdb/layouts');
                                echo $genres->render(array('genres' => $music->genres));
                                ?>
                            </span>
                        </div>
                        <!--/.music-name-->
                        <div class="cast">
                            <span><?php echo Text::_('COM_AVISAMUSICDB_CAST'); ?> : </span> <?php echo $music->actors; ?>
                        </div> <!-- /.cast -->
                    </div>

                </div>
            <?php } // END:: foreach 
            ?>

        </div> <!-- /.avisa-mu-music -->
    </div>
    <!--/.row-fluid-->

    <div class="mod-avisamusicdb-music video-container">
        <span class="video-close"> <i class="avisamusicdb-icon-close"></i> </span>
    </div><!-- /.video-container -->

</div> <!-- /.avisa-musicw-music -->