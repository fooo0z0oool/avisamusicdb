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
?>

<div id="avisamusicdb" class="avisamusicdb view-avisamusicdb-album">

    <!-- album cover -->
    <div class="album-cover" style="background-image: url(<?php echo Uri::root() . $this->item->cover_image; ?>); ">
        <div class="avisamusicdb-container">
            <div class="avisamusicdb-row">
                <div class="avisamusicdb-col-sm-9 avisamusicdb-col-sm-offset-3">
                    <div class="album-info-warpper">
                        <!-- album-info -->
                        <div class="album-info">
                            <div class="">
                                <h1 class="album-title"><?php echo $this->item->title; ?></h1>
                                <div class="music-social-icon">
                                    <span><?php echo Text::_('COM_AVISAMUSICDB_MUSIC_SOCIAL_SHARE'); ?>:</span>
                                    <?php echo LayoutHelper::render('social_share', array('url' => $this->item->url, 'title' => $this->item->title)); ?>
                                </div> <!-- /.social-icon -->
                            </div>
                        </div> <!-- //album-info -->
                    </div>
                </div>
            </div>
        </div>
    </div> <!-- //end cover -->

    <!-- album details -->
    <div class="album-details-wrap">
        <div class="avisamusicdb-container">
            <div class="avisamusicdb-row">
                <div id="album-info-sidebar" class="avisamusicdb-col-sm-3 album-info-sidebar">
                    <div class="img-wrap">
                        <div class="item-img">
                            <img src="<?php echo Uri::root() . $this->item->profile_image; ?>" alt="">
                        </div>
                        <div class="avisamusicdb-details-wrapper">
                            <h3 class="title"><?php echo Text::_('COM_AVISAMUSICDB_ALBUM_PERSONAL_INFO'); ?></h3>
                            <ul class="list-style-none list-inline">
                                <?php if (isset($this->item->birth_name) && $this->item->birth_name) { ?>
                                    <li>
                                        <span><?php echo Text::_('COM_AVISAMUSICDB_ALBUM_BIRTHNAME'); ?>: </span>
                                        <?php echo $this->item->birth_name; ?>
                                    </li>
                                <?php }
                                if (isset($this->item->dob) && $this->item->dob) { ?>
                                    <li>
                                        <span><?php echo Text::_('COM_AVISAMUSICDB_ALBUM_DOB'); ?>: </span>
                                        <?php echo HTMLHelper::date($this->item->dob, 'd, M Y'); ?>
                                    </li>
                                <?php }
                                if (isset($this->item->residence) && $this->item->residence) { ?>
                                    <li>
                                        <span><?php echo Text::_('COM_AVISAMUSICDB_ALBUM_RESIDENCE'); ?>: </span>
                                        <?php echo $this->item->residence; ?>
                                    </li>
                                <?php }
                                if (isset($this->item->height) && $this->item->height) { ?>
                                    <li>
                                        <span><?php echo Text::_('COM_AVISAMUSICDB_ALBUM_HEIGHT'); ?>: </span>
                                        <?php echo $this->item->height; ?>
                                    </li>
                                <?php } ?>
                            </ul>
                            <?php if (isset($this->item->facebook) || isset($this->item->twitter) || isset($this->item->gplus) || isset($this->item->youtube) || isset($this->item->vimeo) || isset($this->item->website)) { ?>
                                <div class="music-social-icon">
                                    <span><?php echo Text::_('COM_AVISAMUSICDB_MUSIC_SOCIAL'); ?>:</span>
                                    <ul class="list-style-none list-inline">
                                        <?php if (isset($this->item->facebook) && $this->item->facebook) { ?>
                                            <li>
                                                <a class="facebook" href="<?php echo $this->item->facebook; ?>">
                                                    <i class="avisamusicdb-icon-facebook"></i></a>
                                            </li>
                                        <?php }
                                        if (isset($this->item->twitter) && $this->item->twitter) { ?>
                                            <li>
                                                <a class="twitter" href="<?php echo $this->item->twitter; ?>">
                                                    <i class="avisamusicdb-icon-twitter"></i></a>
                                            </li>
                                        <?php }
                                        if (isset($this->item->gplus) && $this->item->gplus) { ?>
                                            <li>
                                                <a class="googleplus" href="<?php echo $this->item->gplus; ?>">
                                                    <i class="avisamusicdb-icon-google-plus"></i></a>
                                            </li>
                                        <?php }
                                        if (isset($this->item->youtube) && $this->item->youtube) { ?>
                                            <li>
                                                <a class="youtube" href="<?php echo $this->item->youtube; ?>">
                                                    <i class="avisamusicdb-icon-youtube"></i></a>
                                            </li>
                                        <?php }
                                        if (isset($this->item->vimeo) && $this->item->vimeo) { ?>
                                            <li>
                                                <a class="vimeo" href="<?php echo $this->item->vimeo; ?>">
                                                    <i class="avisamusicdb-icon-vimeo"></i></a>
                                            </li>
                                        <?php }
                                        if (isset($this->item->website) && $this->item->website) { ?>
                                            <li>
                                                <a class="vimeo" href="<?php echo $this->item->website; ?>">
                                                    <i class="avisamusicdb-icon-web"></i></a>
                                            </li>
                                        <?php } ?>
                                    </ul>
                                </div> <!-- /.social-icon -->
                            <?php } ?>
                        </div> <!-- //avisamusicdb-details-wrapper -->
                    </div>
                </div> <!-- //album-info-sidebar -->

                <div class="avisamusicdb-col-sm-9 album-info-warpper">

                    <!-- album-details -->
                    <div class="album-details">
                        <div class="header-title">
                            <span><i class="avisamusicdb-icon-story"></i></span>
                            <h3 class="title"><?php echo Text::_('COM_AVISAMUSICDB_ALBUM_ALBUMBIO'); ?></h3>
                        </div>
                        <?php echo $this->item->albumbio; ?>

                    </div> <!-- //album-details -->

                    <?php if (isset($this->item->album_musics) && (count($this->item->album_musics) > 0) && $this->item->album_musics) { ?>
                        <!-- Filmography -->
                        <div class="avisamusicdb-filmography col-sm-12">
                            <div class="header-title">
                                <span><i class="avisamusicdb-icon-film"></i></span>
                                <h3 class="title"><?php echo Text::_('COM_AVISAMUSICDB_ALBUM_FILMOGRAPHY'); ?></h3>
                            </div> <!-- //header-title -->

                            <ul class="list-unstyled avisamusicdb-film-list">
                                <li class="main-title">
                                    <p class="pull-left"><?php echo Text::_('COM_AVISAMUSICDB_MUSIC_NAME'); ?></p>
                                    <p class="pull-right"><?php echo Text::_('COM_AVISAMUSICDB_MUSIC_RATINGS'); ?></p>
                                </li> <!-- //main-title -->

                                <?php foreach ($this->item->album_musics as $c_music) { ?>
                                    <li calss="music-list">
                                        <div class="details pull-left">
                                            <div class="img-warp pull-left" style="background-image: url(<?php echo Uri::root(true) . '/' .  $c_music->profile_image; ?>); ">
                                            </div>
                                            <div>
                                                <a href="<?php echo $c_music->murl; ?>" class="music-name">
                                                    <strong>
                                                        <?php echo $c_music->title; ?>
                                                    </strong>
                                                </a>
                                                <div class="clelarfix"></div>
                                                <p class="album-music-genres">
                                                    <?php
                                                    echo LayoutHelper::render('music.genres', array('genres'=>$c_music->genres));
                                                    ?>
                                                </p>
                                            </div>
                                        </div> <!-- //details -->

                                        <div class="pull-right avisa-musicdb-rating-wrap">
                                            <?php
                                            if (isset($c_music->ratings) && $c_music->ratings->count) {
                                                $rating = (int) round($c_music->ratings->total / $c_music->ratings->count);
                                            } else {
                                                $rating = 0;
                                            }
                                            echo LayoutHelper::render('review.ratings', array('rating'=>$rating));
                                            ?>
                                        </div> <!-- /.avisa-musicdb-rating-wrap -->
                                    </li> <!-- //music-list -->
                                <?php } ?>

                            </ul>
                        </div>
                        <div class="clearfix"></div>
                        <!-- //Filmography -->
                    <?php } // Filmography 
                    ?>

                    <?php if (isset($this->item->actor_trailers) && (count($this->item->actor_trailers) > 0) && $this->item->actor_trailers) { ?>
                        <!-- trailers-videos -->
                        <div class="trailers-videos">
                            <div class="header-title pull-left">
                                <span><i class="avisamusicdb-icon-trailer"></i></span>
                                <h3 class="title">
                                    <?php echo Text::_('COM_AVISAMUSICDB_ALBUM_TRAILERS_AND_VIDEOS'); ?>
                                </h3>
                            </div>

                            <div class="avisamusicdb-row">
                                <?php foreach ($this->item->actor_trailers as $key => $trailer) {
                                    if ($key == 0) {
                                        $item_type = 'leading avisamusicdb-col-sm-12';
                                        $trailer->thumb = $trailer->turls['tmb_large'];
                                    } else {
                                        $item_type = 'subleading avisamusicdb-col-sm-4';
                                        $trailer->thumb = $trailer->turls['tmb_small'];
                                    }
                                ?>

                                    <div class="avisamusicdb-trailer-item <?php echo $item_type; ?>">
                                        <div class="avisamusicdb-trailer">
                                            <div class="trailer-image-wrap">
                                                <img src="<?php echo Uri::root(true) . '/' . $trailer->thumb; ?>" alt="">
                                                <a class="play-video" href="<?php echo $trailer->turls['src']; ?>" data-type="<?php echo $trailer->turls['host']; ?>">
                                                    <i class="play-icon avisamusicdb-icon-play"></i>
                                                </a>
                                            </div>
                                            <div class="avisamusicdb-trailer-info avisa-avisamusicdb-trailers-info">

                                                <div class="avisamusicdb-trailer-info-block">
                                                    <?php if ($key == 0) { ?>
                                                        <img src="<?php echo Uri::root(true) . '/' . $trailer->turls['tmb_small']; ?>" class="thumb-img" alt="">
                                                    <?php } ?>
                                                    <a href="<?php echo $trailer->murl; ?>">
                                                        <h4 class="album-title"><?php echo $trailer->title; ?></h4>
                                                    </a>
                                                    <?php //if($key == 0 ){
                                                    ?>
                                                    <p class="avisamusicdb-genry">
                                                        <?php echo LayoutHelper::render('music.genres', array('genres' => $trailer->genres)); ?>
                                                    </p>
                                                    <?php //} 
                                                    ?>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                <?php } ?>
                            </div> <!-- ./avisamusicdb-row -->

                            <div class="video-container">
                                <span class="video-close"><i class="avisamusicdb-icon-close"></i></span>
                            </div> <!-- /.video-container -->

                        </div> <!-- //trailers-videos -->
                    <?php } ?>

                    <div class="clearfix"></div>
                </div> <!-- //col-sm-9 -->
            </div> <!-- //avisamusicdb-row -->
        </div> <!-- //avisamusicdb-container -->
    </div>
</div> <!-- //album details -->

</div> <!-- /#avisamusicdb .avisamusicdb -->