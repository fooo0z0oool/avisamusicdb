/**
 * @package     Joomla.Site
 * @subpackage  mod_musicdb_trailers
 *
 * @copyright   Copyright (C) 2020 - 2023 AvisaPro. All rights reserved.
 * @license     GNU General Public License version 2 or later.
 */

jQuery(document).ready(function ($) {

    $('.mod-avisamusicdb-trailers .play-video').on('click', function (event) {
        event.preventDefault();
        var $that = $(this),
            type = $that.data('type'),
            videoUrl = $that.attr('href'), $video;

        if (type === 'youtube') {
            $video = '<iframe id="video-player" src="' + videoUrl + '?rel=0&amp;showinfo=0&amp;controls=1&amp;autoplay=1" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>';
        }
        else if (type === 'vimeo') {
            $video = '<iframe id="video-player" src="' + videoUrl + '?autoplay=1&color=ffffff&title=0&byline=0&portrait=0&badge=0" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>';
        } else if (type === 'dailymotion') {
            $video = '<iframe id="video-player" src="' + videoUrl + '?autoplay=1&color=ffffff&title=0&byline=0&portrait=0&badge=0" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>';
        } else if (type === 'self') {
            $video = '<video id="video-player" controls autoplay> <source src="' + videoUrl + '">Your browser does not support the video tag.</video>';
        }

        $('.mod-avisamusicdb-trailers .video-list').slideUp();

        $('.mod-avisamusicdb-trailers .video-container').find('#video-player').remove();

        $('.mod-avisamusicdb-trailers .video-container').prepend($video);

        $('.mod-avisamusicdb-trailers .video-container').fadeIn();
    });


    $('.mod-avisamusicdb-trailers .video-close').on('click', function (event) {
        event.preventDefault();
        $('.mod-avisamusicdb-trailers .video-container').fadeOut(600, function () {
            $('.mod-avisamusicdb-trailers #video-player').remove();
        });
    });

    $('.mod-avisamusicdb-trailers .video-list-button').on('click', function (event) {
        event.preventDefault();
        $('.mod-avisamusicdb-trailers .video-list').slideToggle();
    });
});