/**
 * @package     Joomla.Site
 * @subpackage  mod_avisamusicdb_tab
 *
 * @copyright   Copyright (C) 2020 - 2023 AvisaPro. All rights reserved.
 * @license     GNU General Public License version 2 or later.
 */

jQuery(function ($) {

    var $AvisaMusicwTab = $(".avisamusicdb-tab-wrap");

    var $autoplay = $AvisaMusicwTab.attr('data-autoplay');
    if ($autoplay == 'true') { var $autoplay = true; } else { var $autoplay = false };
    var $slidelimit = parseInt($AvisaMusicwTab.attr('data-slidelimit'));

    $AvisaMusicwTab.owlCarousel({
        margin: 30,
        nav: true,
        loop: true,
        dots: false,
        autoplay: $autoplay,
        autoplayTimeout: 3000,
        autoplayHoverPause: true,
        autoHeight: false,
        responsive: {
            0: {
                items: 1
            },
            420: {
                items: 2
            },
            767: {
                items: $slidelimit - 1
            },
            1000: {
                items: $slidelimit
            }
        }
    });

});


jQuery(document).ready(function ($) {

    $('.mod-avisamusicdb-tab .play-video').on('click', function (event) {
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

        $('.mod-avisamusicdb-tab .video-list').slideUp();

        $('.mod-avisamusicdb-tab .video-container').find('#video-player').remove();

        $('.mod-avisamusicdb-tab .video-container').prepend($video);

        $('.mod-avisamusicdb-tab .video-container').fadeIn();
    });


    $('.video-close').on('click', function (event) {
        event.preventDefault();
        $('.mod-avisamusicdb-tab .video-container').fadeOut(600, function () {
            $('.mod-avisamusicdb-tab #video-player').remove();
        });
    });

    $('.mod-avisamusicdb-tab .video-list-button').on('click', function (event) {
        event.preventDefault();
        $('.mod-avisamusicdb-tab .video-list').slideToggle();
    });

});