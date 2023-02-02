/**
 * @package     Joomla.Site
 * @subpackage  mod_splmscoursesearch
 *
 * @copyright   Copyright (C) 2020 - 2023 AvisaPro. All rights reserved.
 * @license     GNU General Public License version 2 or later.
 */

jQuery(function ($) {

    $('.mod-avisamusicdb-search #musicdb-search').submit(function () {
        var searchtype = $('.mod-avisamusicdb-search #searchtype').val();
        var rooturl = $('.mod-avisamusicdb-search #rooturl').val();
        var searchword = $('.mod-avisamusicdb-search #searchword').val();
        var mid = $('.mod-avisamusicdb-search #mid').val();
        var cid = $('.mod-avisamusicdb-search #cid').val();
        var tid = $('.mod-avisamusicdb-search #tid').val();

        var itemId = '';
        if (searchtype == 'celebrities') {
            var itemId = cid;
        } else if (searchtype == 'trailers') {
            var itemId = tid;
        } else {
            var itemId = mid;
        }

        if (searchword) { // require a URL
            window.location = rooturl + 'index.php?option=com_avisamusicdb&view=searchresults&searchword=' + searchword + '&type=' + searchtype + itemId + ''; // redirect
        }
        return false;
    });


    var searchPreviousValue,
        liveSearchTimer

    $('.avisamusicdb-search-input').on('keyup', function (event) {

        event.preventDefault();

        //Return on escape
        if (event.keyCode == 27) {
            $('.avisamusicdb-search-results').fadeOut(400);
            return;
        }

        var icon = $('.mod-avisamusicdb-search .avisamusicdb-search-icons').find('i');

        if ($(this).val() != searchPreviousValue) {

            var query = $(this).val().trim();

            // Remove Special Charecter
            var re = /[`~!@#$%^&*_|+\=?;:'",.<>\{\}\[\]\\\/]/gi;
            var isSplChar = re.test(query);
            if (isSplChar) {
                var query = query.replace(/[`~!@#$%^&*_|+\=?;:'",.<>\{\}\[\]\\\/]/gi, '');
                $(this).val(query);
            }

            if (liveSearchTimer) {
                clearTimeout(liveSearchTimer);
            }

            if (query == '') {
                $('.avisamusicdb-search-results').fadeOut(400);
            } else {
                $('.avisamusicdb-search-results').fadeIn(400);
            }

            query = query.trim();

            if (query != '' && !isSplChar) {
                liveSearchTimer = setTimeout(function () {

                    $.ajax({
                        type: 'POST',
                        url: 'index.php?option=com_ajax&module=avisamusicdb_search&format=json',
                        data: { type: $('.mod-avisamusicdb-search #searchtype').val(), query: query },
                        beforeSend: function () {
                            icon.removeClass('avisamusicdb-icon-search').addClass('avisamusicdb-icon-spinner avisamusicdb-icon-spin');
                        },
                        success: function (response) {
                            icon.removeClass('avisamusicdb-icon-spinner avisamusicdb-icon-spin').addClass('avisamusicdb-icon-search');
                            var data = $.parseJSON(response);
                            $('.avisamusicdb-search-results').html(data.content);
                        }
                    });
                }, 300);
            }

            searchPreviousValue = $(this).val()
        }

        return false;
    });

    // click outside slideup
    $(document).on('click', function (e) {
        if (!$('.avisamusicdb-search-results').is(e.target) && !$('.avisamusicdb-search-results *').is(e.target)) {
            $('.avisamusicdb-search-results').fadeOut(400);
        }
    });

});