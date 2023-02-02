/**
 * @package    	Joomla.Site
 * @subpackage 	com_avisamusicdb
 * @author 		AvisaPro support@avisapro.ir
 * @copyright 	Copyright (c) 2020 - 2023 AvisaPro <https://www.avisapro.ir>. All rights reserved.
 * @license     GNU General Public License version 2 or later; see http://www.gnu.org/licenses/gpl-2.0.html
 */

jQuery(function ($) {

	$('.avisa-musicdb-rating.can-rate').find('span.star').on('click', function (event) {
		event.preventDefault();
		var $ratings = $(this).parent().find('span.star');
		$ratings.removeClass('active');

		for (var i = $(this).data('rating_val') - 1; i >= 0; i--) {
			$ratings.eq(9 - i).addClass('active');
		}

		$('#form-music-review').find('#input-rating').val($(this).data('rating_val'));
		$(this).parent().next('.avisamusicdb-rating-summary').find('>span').text($(this).data('rating_val'));
		$('#form-music-review').find('#input-review').focus();
	})

	$('#form-music-review').on('submit', function (event) {
		event.preventDefault();

		var value = $(this).serializeArray();
		$.ajax({
			type: 'POST',
			url: avisamusicdb_url + "&task=review.add_review",
			data: value,
			beforeSend: function () {
				$('.reviewers-form').addClass('avisa-loader');
			},
			success: function (response) {

				var data = $.parseJSON(response);

				if (data.status) {

					$('.reviewers-form').removeClass('avisa-loader')

					if (data.update) {
						$('#reviewers-form-popup').prepend($('<p class="alert alert-success text-center"><strong>' + Joomla.JText._('COM_AVISAMUSICDB_REVIEW_UPDATED') + '</strong></p>').hide().fadeIn());
						$('#submit-review').attr('disabled', 'disabled');

						setInterval(function () {
							$('#reviewers-form-popup').fadeOut(200, function () {
								window.location.reload(true);
							})
						}, 1500);

						$('.own-review').empty().html($(data.content).html());
					} else {
						$('.reviewers-form').fadeOut(200, function () {
							$(this).remove();
						});
						$('.reviewers-form').after(data.content);
					}
				} else {
					alert(data.content);
				}
			}
		})
	})

	/* Load More */
	$('#avisamusicdb-load-review').on('click', function (event) {
		event.preventDefault();
		$this = $(this);

		$.ajax({
			type: 'POST',
			url: avisamusicdb_url + '&task=review.reviews&music_id',
			data: { 'music_id': $(this).data('music_id'), 'start': $('#reviews').find('>.review-item').length },
			beforeSend: function () {
				$this.find('.fa').removeClass('fa-refresh').addClass('fa-spinner fa-spin');
			},
			success: function (response) {
				var data = $.parseJSON(response);

				if (data.status) {
					$('#reviews').append(data.content);

					$this.find('.fa').removeClass('fa-spinner fa-spin').addClass('fa-refresh');

					if (!data.loadmore) {
						$this.remove();
					}
				}
			}
		})
	});

	$('#avisamusicdb-my-review').on('click', function (event) {
		event.preventDefault();
		$('body').addClass('reviewers-form-popup-open')
		$('#reviewers-form-popup').show();
	})

	$('#reviewers-form-popup .close-popup').on('click', function (event) {
		event.preventDefault();
		$('body').removeClass('reviewers-form-popup-open');
		$('#reviewers-form-popup').hide();
	})
});


jQuery(document).ready(function ($) {

	$('.play-video').on('click', function (event) {
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

		$('.video-list').slideUp();
		$('.video-container').find('#video-player').remove();
		$('.video-container').prepend($video);
		$('.video-container').fadeIn();
	});


	$('.video-close').on('click', function (event) {
		event.preventDefault();
		$('.video-container').fadeOut(600, function () {
			$('#video-player').remove();
		});
	});

	$('.video-list-button').on('click', function (event) {
		event.preventDefault();
		$('.video-list').slideToggle();
	});

	/* music list sorting */
	$('select#sorting-by-years').on('change', function () {
		window.location = $(this).val();
	})

	/* Load Music Trailer */
	$('.show-music-trailers').on('click', function (event) {
		event.preventDefault();
		var itemId = $(this).data('id');
		$.ajax({
			type: 'POST',
			url: avisamusicdb_url + '&task=musics.trailers&id=' + $(this).data('id'),
			beforeSend: function () {
				$('.reviewers-form').addClass('avisa-loader') 
			},
			success: function (response) {

				var data = $.parseJSON(response);

				if (data.status) {
					var windowWidth = $('#musicdb-item-wrap').width();
					var currentItem = $('.item.item-id-' + itemId);
					var parent = currentItem.parent();
					$('.view-trailers .active').removeClass('active');

					currentItem.addClass('active');

					if ($("#music-trailer-video").length) {
						$('#music-trailer-video').remove();
					}

					$(parent).after('<div id="music-trailer-video"></div>');
					var musicTrailer = $('#music-trailer-video');
					$(musicTrailer).append('<div class="musicdb-trailer-loader"><i class="avisamusicdb-icon-spinner avisamusicdb-icon-spin"></i></div>');

					setTimeout(function () {
						$('.musicdb-trailer-loader').remove();
						musicTrailer.slideDown("normal", function () { $(this).append(data.content); });
					}, 300);

					$('html, body').animate({
						scrollTop: $(musicTrailer).offset().top - $(window).height() / 3
					}, 300);

				}

			}
		})
	});

	$('body').on('click', '.view-trailers .video-close', function () {
		$('.view-trailers .active').removeClass('active');
		$('#music-trailer-video').slideUp("normal", function () { $(this).remove(); });
	});
});