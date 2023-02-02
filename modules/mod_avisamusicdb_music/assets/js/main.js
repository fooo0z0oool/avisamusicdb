/**
* @package     Joomla.Site
* @subpackage  mod_avisamusicdb_music
*
* @copyright   Copyright (C) 2020 - 2023 AvisaPro. All rights reserved.
* @license     GNU General Public License version 2 or later.
*/

jQuery(function($) {

   // $('.mod-avisamusicdb-music .owl-stage-outer').hide();
    $('.mod-avisamusicdb-music').append( '<div class="container mod-musicdb-loader-wrap"><div class="mod-musicdb-music-loader"><div class="musicdb-signal-animate"></div></div></div>' );

    setTimeout(function(){
       $('.mod-avisamusicdb-music .owl-stage-outer').show();
       $('.mod-musicdb-loader-wrap').remove();
    }, 400);


    var $avisamumusic = $('.avisa-mu-music');
    var $autoplay   = $avisamumusic.attr('data-autoplay');
    if ($autoplay == 'true') { var $autoplay = true; } else { var $autoplay = false};
    var $slidelimit   = parseInt($avisamumusic.attr('data-slidelimit'));

    $avisamumusic.owlCarousel({
        loop:true,
        dots:false,
        nav:false,
        autoplay:$autoplay,
        autoplayTimeout:3000,
        autoplayHoverPause:true,
        autoHeight: false,
        lazyLoad:false,
        responsive:{
            0:{
                items:1
            },
            420:{
                items:2
            },
            767:{
                items:3
            },
            1000:{
                items:$slidelimit
            }
        }
    })
});



jQuery(document).ready(function($) {

    $('.mod-avisamusicdb-music .play-video').on('click', function(event) {
        event.preventDefault();
        var $that       = $(this),
        type        = $that.data('type'),
        videoUrl    = $that.attr('href'), $video;

        if ( type === 'youtube' ) {
            $video = '<iframe id="video-player" src="' + videoUrl + '?rel=0&amp;showinfo=0&amp;controls=1&amp;autoplay=1" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>';
        }
        else if ( type === 'vimeo' ) {
            $video = '<iframe id="video-player" src="' + videoUrl + '?autoplay=1&color=ffffff&title=0&byline=0&portrait=0&badge=0" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>';
        } else if ( type === 'dailymotion' ) {
            $video = '<iframe id="video-player" src="' + videoUrl + '?autoplay=1&color=ffffff&title=0&byline=0&portrait=0&badge=0" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>';
        } else if ( type === 'self' ) {
            $video = '<video id="video-player" controls autoplay> <source src="'+ videoUrl +'">Your browser does not support the video tag.</video>';
        }

        $('.mod-avisamusicdb-music .video-list').slideUp();

        $('.mod-avisamusicdb-music .video-container').find('#video-player').remove();

        $('.mod-avisamusicdb-music .video-container').prepend( $video );

        $('.mod-avisamusicdb-music .video-container').fadeIn();
    });

    $('.mod-avisamusicdb-music .video-close').on('click', function(event) {
        event.preventDefault();
        $('.mod-avisamusicdb-music .video-container').fadeOut(600, function(){
            $('.mod-avisamusicdb-music #video-player').remove();
        });
    });

    $('.mod-avisamusicdb-music .video-list-button').on('click', function(event) {
        event.preventDefault();
        $('.mod-avisamusicdb-music .video-list').slideToggle();
    });
});