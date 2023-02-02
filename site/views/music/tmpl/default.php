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
use Joomla\CMS\Router\Route;
use Joomla\CMS\Uri\Uri;

HTMLHelper::_('jquery.framework');
$input = Factory::getApplication()->input;
$doc = Factory::getDocument();
$user = Factory::getUser();
$doc->addScript(Uri::base(true) . '/components/com_avisamusicdb/assets/js/avisamusicdb.js');
$doc->addScriptdeclaration('var avisamusicdb_url="' . Uri::base() . 'index.php?option=com_avisamusicdb";');

Text::script('COM_AVISAMUSICDB_REVIEW_UPDATED');
?>

<div id="avisamusicdb" class="avisamusicdb view-avisamusicdb-music">

    <!-- music cover -->
    <div class="music-cover" style="background-image: url(<?php echo Uri::root() . $this->item->cover_image; ?>); ">
        <div class="avisamusicdb-container">
            <div class="avisamusicdb-row">
                <div class="avisamusicdb-col-sm-9 avisamusicdb-col-sm-offset-3">
                    <div class="music-info-warpper">
                        <div class="music-info">
                            <div class="pull-left">
                                <h1 class="music-title"><?php echo $this->item->title; ?></h1>
                                <?php if (isset($this->item->genres) && $this->item->genres) { ?>
                                    <span class="tag"><?php echo LayoutHelper::render('music.genres', array('genres' => $this->item->genres)); ?></span> |
                                <?php }
                                if (isset($this->item->duration) && $this->item->duration) { ?>
                                    <span class="music-duration"><?php echo $this->item->duration; ?></span>
                                <?php } ?>
                                <div class="rating-star">
                                    <span><?php echo Text::_('COM_AVISAMUSICDB_MUSIC_YOUR_RATTING'); ?>:</span>
                                    <?php if (isset($this->ratings) && $this->ratings->count) {
                                        $rating = round($this->ratings->total / $this->ratings->count);
                                    } else {
                                        $rating = 0;
                                    } ?>
                                    <?php echo LayoutHelper::render('review.ratings', array('rating' => $rating)); ?>
                                </div> <!-- ?rating-star -->

                                <?php if (isset($this->item->facebook) || isset($this->item->twitter) || isset($this->item->gplus) || isset($this->item->youtube) || isset($this->item->vimeo) || isset($this->item->website)) { ?>
                                    <div class="music-social-icon">
                                        <ul>
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
                                    </div> <!-- /.music-social-icon -->
                                <?php } ?>

                            </div>
                            <div class="pull-right count-rating-wrapper">
                                <div class="count-rating">
                                    <span>
                                        <?php if (isset($this->ratings) && $this->ratings->count) {
                                            $rating = round($this->ratings->total / $this->ratings->count);
                                        } else {
                                            $rating = 0;
                                        }
                                        echo $rating;
                                        ?>
                                    </span>
                                </div>
                            </div>
                        </div> <!-- //music-info -->
                        <div class="clearfix"></div>
                    </div>
                </div>
            </div>
        </div>

    </div> <!-- //end cover -->

    <!-- music details -->
    <div class="music-details-wrap">
        <div class="avisamusicdb-container">
            <div class="avisamusicdb-row">
                <div id="music-info-sidebar" class="avisamusicdb-col-sm-3 music-info-sidebar">
                    <div class="img-wrap">
                        <div class="item-img">
                            <img src="<?php echo Uri::root() . $this->item->profile_image; ?>" alt="">
                        </div>
                        <div class="avisamusicdb-details-wrapper">
                            <h3 class="title"><?php echo Text::_('COM_AVISAMUSICDB_MUSIC_INFO'); ?></h3>
                            <ul class="list-style-none list-inline">
                                <?php if (isset($this->item->directors) && $this->item->directors) { ?>
                                    <li class="director">
                                        <span><?php echo Text::_('COM_AVISAMUSICDB_DIRECTOR'); ?>: </span>
                                        <?php echo $this->item->directors; ?>
                                    </li>
                                <?php }
                                if (isset($this->item->actors) && $this->item->actors) { ?>
                                    <li class="actors">
                                        <span><?php echo Text::_('COM_AVISAMUSICDB_MUSIC_ACTORS'); ?>: </span>
                                        <?php echo $this->item->actors; ?>
                                    </li>
                                <?php }
                                if (isset($this->item->release_date) && $this->item->release_date) { ?>
                                    <li class="release-date">
                                        <span><?php echo Text::_('COM_AVISAMUSICDB_MUSIC_RELEASED_DATE'); ?>: </span>
                                        <?php echo HTMLHelper::date($this->item->release_date, 'd M, Y'); ?>
                                    </li>
                                <?php }
                                if (isset($this->item->genres) && $this->item->genres) { ?>
                                    <li class="genres">
                                        <span><?php echo Text::_('COM_AVISAMUSICDB_MUSIC_GENRES'); ?>: </span>
                                        <?php echo LayoutHelper::render('music.genres', array('genres' => $this->item->genres)); ?>
                                    </li>
                                <?php }
                                if (isset($this->item->country) && $this->item->country) { ?>
                                    <li class="country">
                                        <span><?php echo Text::_('COM_AVISAMUSICDB_MUSIC_COUNTRY'); ?>:</span>
                                        <?php echo $this->item->country; ?>
                                    </li>
                                <?php }
                                if (isset($this->item->m_language) && $this->item->m_language) { ?>
                                    <li class="language">
                                        <span><?php echo Text::_('COM_AVISAMUSICDB_MUSIC_LANGUAGE'); ?>:</span>
                                        <?php echo $this->item->m_language; ?>
                                    </li>
                                <?php } ?>
                            </ul>
                        </div> <!-- //avisamusicdb-details-wrapper -->
                    </div>
                    <?php if (isset($this->item->dvdlink) && $this->item->dvdlink) { ?>
                        <a href="<?php echo $this->item->dvdlink; ?>" class="btn sppb-btn-primary btn-block buy-ticket" target="_blank">
                            <i class="avisa-musicw-ticket"></i> <?php echo Text::_('COM_AVISAMUSICDB_MUSIC_BUY_DVD'); ?>
                        </a>
                    <?php } ?>

                </div> <!-- music-info-sidebar -->

                <div class="avisamusicdb-col-sm-9 music-info-warpper">

                    <!-- music-details -->
                    <div class="music-details">
                        <div class="header-title">
                            <span><i class="avisamusicdb-icon-story"></i></span>
                            <h3 class="title"><?php echo Text::_('COM_AVISAMUSICDB_MUSIC_STORY'); ?></h3>
                        </div>
                        <div class="music-details-text">
                            <?php echo $this->item->music_story; ?>
                        </div>

                        <div class="music-social-icon">
                            <span><?php echo Text::_('COM_AVISAMUSICDB_MUSIC_SOCIAL_SHARE'); ?>:</span>
                            <?php echo LayoutHelper::render('social_share', array('url' => $this->item->url, 'title' => $this->item->title)); ?>
                        </div> <!-- /.social-icon -->

                    </div> <!-- //music-details -->

                    <!-- trailers -->
                    <?php if ((count($this->item->turls) > 0) && $this->item->turls) {
                        echo LayoutHelper::render('music.trailers', array('trailers' => $this->item->turls));
                    } ?>

                    <div class="clearfix"></div>

                    <div class="user-reviews">
                        <div class="reviews-menu">
                            <div class="header-title pull-left">
                                <span><i class="avisamusicdb-icon-user-review"></i></span>
                                <h3 class="title">Reviews <small>( <?php echo count($this->reviews); ?> )</small></h3>
                            </div>
                            <div class="pull-right">
                                <ul class="list-inline list-style-none">
                                    <?php if ($this->myReview) { ?>
                                        <li><a id="avisamusicdb-my-review" href="#"><i class="avisamusicdb-icon-write"></i> <?php echo Text::_('COM_AVISAMUSICDB_EDIT_REVIEW'); ?></a></li>
                                    <?php } ?>

                                    <?php if ($user->guest) { ?>
                                        <li><a href="<?php echo Route::_('index.php?option=com_users&view=login&return=' . base64_encode('index.php?option=com_avisamusicdb&view=music&id=' . $this->item->id . ':' . $this->item->alias . AvisamusicdbHelper::getItemid('musics'))); ?>"><i class="avisamusicdb-icon-write"></i> <?php echo Text::_('COM_AVISAMUSICDB_LOGIN_TO_REVIEW'); ?></a></li>
                                    <?php } ?>
                                </ul>
                            </div>
                        </div>
                        <!--/.reviews-menu -->
                        <div class="clearfix"></div>

                        <?php echo LayoutHelper::render('review.form', array('review' => $this->myReview, 'music_id' => $this->item->id, 'url' => 'index.php?option=com_avisamusicdb&view=music&id=' . $this->item->id . ':' . $this->item->alias . AvisamusicdbHelper::getItemid('musics'))); ?>

                        <div id="reviews">
                            <?php
                            foreach ($this->reviews as $key => $this->review) {
                                echo LayoutHelper::render('review.review', array('review' => $this->review));
                            }
                            ?>
                        </div>

                        <?php if ($this->showLoadMore) { ?>
                            <a id="avisamusicdb-load-review" class="btn btn-link btn-lg btn-block" data-music_id="<?php echo $this->item->id; ?>" href="#"><i class="fa fa-refresh"></i> <?php echo Text::_('COM_AVISAMUSICDB_REVIEW_LOAD_MORE'); ?></a>
                        <?php } ?>
                    </div>
                    <!--/.user-reviews-->

                    <?php if (isset($this->item->show_times) && count($this->item->show_times)) { ?>
                        <!-- Music Showtime -->
                        <div class="music-showtime">
                            <div class="header-title">
                                <span><i class="avisamusicdb-icon-showtime"></i></span>
                                <h3 class="title"><?php echo Text::_('COM_AVISAMUSICDB_MUSIC_SHOW_TIME'); ?></h3>
                            </div>

                            <?php foreach ($this->item->show_times as $showtime) { ?>
                                <div class="music-schedule avisamusicdb-row">
                                    <div class="avisamusicdb-col-sm-4 location">
                                        <p class="location-name"><?php echo $showtime['theatre_name']; ?></p>
                                        <p class="address"><i class="fa fa-map-marker"></i> <?php echo $showtime['theatre_location']; ?></p>
                                    </div>

                                    <div class="avisamusicdb-col-sm-8">
                                        <?php if ((isset($showtime['times']) > 0) && $showtime['times']) { ?>
                                            <div class="times pull-left">
                                                <p class="visible-xs show-time"><?php echo Text::_('COM_AVISAMUSICDB_MUSIC_SHOW_TIME_TITLE'); ?></p>
                                                <ul class="list-style-none list-inline">
                                                    <?php foreach ($showtime['times'] as $time) { ?>
                                                        <li><span><?php echo $time; ?></span></li>
                                                    <?php } ?>
                                                </ul>
                                            </div>
                                        <?php } ?>

                                        <?php if ((isset($showtime['ticket_url']) > 0) && $showtime['ticket_url']) { ?>
                                            <div class="ticket-urls pull-right">
                                                <a href="<?php echo $showtime['ticket_url'] ?>" class="btn sppb-btn-primary buy-ticket" target="_blank">
                                                    <i class="avisamusicdb-icon-ticket"></i>
                                                    <?php echo Text::_('COM_AVISAMUSICDB_MUSIC_BUY_TICKET'); ?>
                                                </a>
                                            </div>
                                        <?php } ?>
                                    </div>
                                </div> <!-- //music-schedule -->
                                <div class="clearfix"></div>
                            <?php } ?>

                        </div> <!-- //Music Showtime -->
                    <?php } ?>

                    <!-- Recommend musics -->
                    <?php if (isset($this->related_musics) && count($this->related_musics) && $this->related_musics) { ?>
                        <div class="recommend-musics">
                            <div class="header-title">
                                <span><i class="avisamusicdb-icon-like"></i></span>
                                <h3 class="title">Recommend musics</h3>
                            </div>
                            <div class="avisamusicdb-row">
                                <?php foreach ($this->related_musics as $this->related_music) { ?>
                                    <div class="item avisamusicdb-col-sm-4 avisamusicdb-col-xs-12">
                                        <?php echo LayoutHelper::render('music.list_layout', array('music' => $this->related_music)); ?>
                                    </div>
                                <?php } ?>
                            </div> <!-- //avisamusicdb-row -->

                        </div> <!-- //Recommend musics -->
                    <?php } ?>
                </div> <!-- //col-sm-9 -->
            </div> <!-- //avisamusicdb-row -->
        </div> <!-- //avisamusicdb-container -->
        <div class="video-container">
            <span class="video-close"><i class="avisamusicdb-icon-close"></i></span>
        </div> <!-- /.video-container -->
    </div>
</div> <!-- //music details -->

</div> <!-- /#avisamusicdb .avisamusicdb -->