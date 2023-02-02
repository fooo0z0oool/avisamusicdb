/**
 * @package Avisa Media Manager
 * @author AvisaPro http://www.avisapro.ir
 * @copyright Copyright (c) 2020 - 2023 AvisaPro
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 or later
*/
(function ($) {

  /* ========================================================================
  * Browse Media
  * ======================================================================== */

  $.fn.browseMedia = function(options) {
    var options = $.extend({
      search : '',
      date : '',
      start : 0,
      filter : true
    }, options)

    $.ajax({
      type : 'POST',
      url: 'index.php?option=com_avisamusicdb&view=media&layout=browse&format=json',
      data: {date: options.date, start: options.start, search: options.search},
      beforeSend: function() {
        $('#avisa-media-modal .btn-loadmore').hide()
        $('#avisa-media-modal .avisa-media').remove()
        $('#avisa-media-modal .avisa-media-modal-body').prepend($('<div class="spinner"><div class="bounce1"></div><div class="bounce2"></div><div class="bounce3"></div></div>'))
      },
      success: function (response) {
        $('#avisa-media-modal .spinner').remove();
        try {
          var data = $.parseJSON(response)

          if(options.filter) {
            $('#avisa-media-modal .avisa-media-modal-filter').html(data.date_filter)
          }

          $('#avisa-media-modal .avisa-media-modal-body-inner').prepend(data.output)

          if(data.loadmore) {
            $('#avisa-media-modal .btn-loadmore').removeAttr('style')
          } else {
            $('#avisa-media-modal .btn-loadmore').hide();
          }

        } catch (e) {
          $('#avisa-media-modal .avisa-media-modal-body-inner').html(response)
        }
      }
    })
  }


  /* ========================================================================
  * Browse Folders
  * ======================================================================== */

  $.fn.browseFolders = function(options) {

    var options = $.extend({
      path: '/images',
      filter: true
    }, options);

    return this.each(function() {
      $.ajax({
        url: 'index.php?option=com_avisamusicdb&view=media&layout=folders&format=json',
        type   : 'POST',
        data: {path: options.path},

        beforeSend: function() {
          if(!options.filter) {
            $('#avisa-media-modal .avisa-folder-filter').val( options.path )
          }

          $('#avisa-media-modal .btn-loadmore').hide()
          $('#avisa-media-modal .avisa-media').remove()
          $('#avisa-media-modal .avisa-media-modal-body').prepend($('<div class="spinner"><div class="bounce1"></div><div class="bounce2"></div><div class="bounce3"></div></div>'))
        },

        success: function (response) {
          $('#avisa-media-modal .spinner').remove();
          try {
            var data = $.parseJSON(response)

            if(options.filter) {
              $('#avisa-media-modal .avisa-media-modal-filter').html(data.folders_tree)
            }

            $('#avisa-media-modal .avisa-media-modal-body-inner').prepend(data.output)

          } catch (e) {
            $('#avisa-media-modal .avisa-media-modal-body-inner').html(response)
          }
        }
      })
    })
  }


  $(document).on('click', '#avisa-media-modal .tab-browse-folder', function(event){
    event.preventDefault()

    if($(this).parent().hasClass('active')) {
      return true
    }

    $('#avisa-media-modal .btn-delete-media, #avisa-media-modal .avisa-media-search').hide()
    $('#avisa-media-modal .tab-browse-media').parent().removeClass('active')
    $(this).parent().addClass('active')
    $(this).browseFolders()
  })

  $(document).on('click', '#avisa-media-modal .to-folder-back', function(event){
    event.preventDefault()
    $('#avisa-media-modal .avisa-media-modal-btn-tools').hide()
    $(this).browseFolders({
      filter: false,
      path: $(this).data('path')
    })
  })

  $(document).on('click', '#avisa-media-modal .to-folder', function(event){
    event.preventDefault()
    $('#avisa-media-modal .avisa-media-modal-btn-tools').hide()
    $('#avisa-media-modal .avisa-media').find('>li.avisa-media-item').removeClass('selected')
    $('#avisa-media-modal .avisa-media').find('>li.avisa-media-folder').removeClass('folder-selected')
    $(this).closest('li.avisa-media-folder').addClass('folder-selected')
  })

  $(document).on('dblclick', '#avisa-media-modal .to-folder', function(event){
    event.preventDefault()
    $('#avisa-media-modal .avisa-media-modal-btn-tools').hide()
    $(this).browseFolders({
      filter: false,
      path: $(this).data('path')
    })
  })


  $(document).on('change', '#avisa-media-modal .avisa-folder-filter', function(event){
    event.preventDefault()
    $(this).browseFolders({
      filter: false,
      path: $(this).val()
    })
  })
  

  /* ========================================================================
  * Upload Media
  * ======================================================================== */

  $.fn.uploadMedia = function(options) {

    var options = $.extend({
      data : ''
    }, options)

    $.ajax({
      type: "POST",
      url: 'index.php?option=com_avisamusicdb&task=media.upload_media',
      data: options.data,
      contentType: false,
      cache: false,
      processData:false,
      beforeSend: function() {

        if($('#avisa-media-modal .avisa-media-folder').length) {
          $('#avisa-media-modal .avisa-media-folder').last().after($('<li class="avisa-media-image-loader"><div><div><div class="avisa-media-image"><i class="fa fa-spinner fa-pulse"></i></div></div></div></li>'))
        } else {
          $('#avisa-media-modal .avisa-media').prepend($('<li class="avisa-media-image-loader"><div><div><div class="avisa-media-image"><i class="fa fa-spinner fa-pulse"></i></div></div></div></li>'))
        }

        $('#avisa-media-modal .btn-upload-media').attr('disabled', 'disabled')
      },
      success: function(response) {
        try {
          var data = $.parseJSON(response)
          if(data.status) {
            $('#avisa-media-modal .avisa-media').find('.avisa-media-image-loader').remove()

            var output  = '<li class="avisa-media-item" data-id="' + data.id + '" data-src="' + data.src + '" data-path="' + data.path + '">'
            output    += '<div>';
            output    += '<div>';
            output    += '<div class="avisa-media-image">';
            output    += '<img src="' + data.src + '">';
            output    += '<span class="avisa-media-title">' + data.title + '</span>';
            output    += '</div>';
            output    += '</div>';
            output    += '</div>';
            output    += '</div>';
            output    += '</li>';

            if($('#avisa-media-modal .avisa-media-folder').length) {
              $('#avisa-media-modal .avisa-media-folder').last().after(output)
            } else {
              $('#avisa-media-modal .avisa-media').prepend(output)
            }

            $('#avisa-media-modal .btn-upload-media').removeAttr('disabled')
          }
        } catch (e) {
          $('#avisa-media-modal .avisa-media-modal-body-inner').html(response)
        }
      }         
    })
  }


  /* ========================================================================
  * Search Media
  * ======================================================================== */
  
  var searchPreviousValue,
  liveSearchTimer

  $(document).on('keyup', '.input-search-media', function(event) {
    event.preventDefault()

    if($(this).val() != '') {
      $('#avisa-media-modal .avisa-clear-search').show()
    } else {
      $('#avisa-media-modal .avisa-clear-search').hide()
    }

    if ($(this).val() != searchPreviousValue) {
      var query = $(this).val().trim();

      if (liveSearchTimer) {
        clearTimeout(liveSearchTimer);
      }

      liveSearchTimer = setTimeout(function () {
        if (query) {
          $(this).browseMedia({
            search: query,
            filter: true,
            date: $('#avisa-media-modal .avisa-date-filter').val()
          })
        }
        else {
          $(this).browseMedia({
            filter: true,
            date: $('#avisa-media-modal .avisa-date-filter').val()
          })
        }
      }, 300);

      searchPreviousValue = $(this).val()
    }
  })

  $(document).on('click', '#avisa-media-modal .avisa-clear-search', function(event) {
    event.preventDefault()
    $('.input-search-media').val('').focus().keyup()
  })

  $(document).on('click', '.input-search-media', function(event) {
    event.preventDefault()
  })


  /* ========================================================================
  * Initiate Media Manager
  * ======================================================================== */

  $(document).on('click', '.avisa-btn-media-manager', function(event) {
    event.preventDefault()
    var media_modal = '<div class="avisa-media-modal-overlay" tabindex="-1">'
    media_modal += '<div id="avisa-media-modal" data-thumbsize="'+ $(this).data('thumbsize') +'">'
    media_modal += '<div class="avisa-media-modal-inner">'
    media_modal += '<div class="avisa-media-modal-header clearfix">'
    media_modal += '<h3 class="pull-left"><i class="fa fa-toggle-right"></i><span class="hidden-phone hidden-xs"> ' + Joomla.JText._('AVISAMEDIA_MANAGER') + '</span></h3>'
    media_modal += '<div class="pull-right"><input type="file" accept="image/*" style="display:none"><a href="#" class="btn btn-success btn-large btn-upload-media"><i class="fa fa-upload"></i><span class="hidden-phone hidden-xs"> ' + Joomla.JText._('AVISAMEDIA_MANAGER_UPLOAD_FILE') + '</span></a><a href="#" class="btn btn-danger btn-large btn-close-modal"><i class="fa fa-times"></i><span class="hidden-phone hidden-xs"> ' + Joomla.JText._('AVISAMEDIA_MANAGER_CLOSE') + '</span></a></div>'
    media_modal += '</div>'

    media_modal += '<div class="avisa-media-modal-subheader clearfix">'

    media_modal += '<ul class="avisa-media-modal-tab">'
    media_modal += '<li class="active"><a class="tab-browse-media" href="#"><i class="fa fa-image"></i><span class="hidden-phone hidden-xs"> ' + Joomla.JText._('AVISAMEDIA_MANAGER_BROWSE_MEDIA') + '</span></a></li>'
    media_modal += '<li><a class="tab-browse-folder" href="#"><i class="fa fa-folder-open-o"></i><span class="hidden-phone hidden-xs"> ' + Joomla.JText._('AVISAMEDIA_MANAGER_BROWSE_FOLDERS') + '</span></a></li>'
    media_modal += '</ul>'

    media_modal += '<div class="avisa-media-modal-filter-tools hidden-phone hidden-xs">'
    media_modal += '<div class="avisa-media-search"><i class="fa fa-search"></i><input type="text" class="input-search-media" placeholder="' + Joomla.JText._('AVISAMEDIA_MANAGER_SEARCH') + '"><a href="#" class="avisa-clear-search" style="display: none;"><i class="fa fa-times-circle"></i></a></div>'
    media_modal += '<div class="avisa-media-modal-filter"></div>'
    media_modal += '</div>'

    media_modal += '</div>'

    media_modal += '<div class="avisa-media-modal-btn-tools clearfix" style="display:none;">'
    media_modal += '<a href="#" class="btn btn-primary btn-insert-media" data-target="'+ $(this).prev().attr('id') +'"><i class="fa fa-check"></i> ' + Joomla.JText._('AVISAMEDIA_MANAGER_INSERT') + '</a> <a href="#" class="btn btn-warning btn-cancel-media"><i class="fa fa-times"></i> ' + Joomla.JText._('AVISAMEDIA_MANAGER_CANCEL') + '</a> <a href="#" class="btn btn-danger btn-delete-media hidden-phone hidden-xs"><i class="fa fa-minus-circle"></i> ' + Joomla.JText._('AVISAMEDIA_MANAGER_DELETE') + '</a>'
    media_modal += '</div>'

    media_modal += '<div class="avisa-media-modal-body">'
    media_modal += '<div class="avisa-media-modal-body-inner">'

    media_modal += '<div class="spinner">'
    media_modal += '<div class="bounce1"></div>'
    media_modal += '<div class="bounce2"></div>'
    media_modal += '<div class="bounce3"></div>'
    media_modal += '</div>'

    media_modal += '<a class="btn btn-default btn-large btn-loadmore" href="#" style="display: none;"><i class="fa fa-refresh"></i> ' + Joomla.JText._('AVISAMEDIA_MANAGER_LOAD_MORE') + '</a>';

    media_modal += '</div>'
    media_modal += '</div>'

    media_modal += '</div>'
    media_modal += '</div>'

    $(media_modal).hide().appendTo($('body').addClass('avisa-media-modal-open')).fadeIn(300, function() {
      $(this).browseMedia()
    })
  })

  // Media Tab
  // =======================

  $(document).on('click', '#avisa-media-modal .tab-browse-media', function(event){
    event.preventDefault()
    
    if($(this).parent().hasClass('active')) {
      return true
    }

    $('#avisa-media-modal .btn-delete-media, #avisa-media-modal .avisa-media-search').show()
    $('#avisa-media-modal .tab-browse-folder').parent().removeClass('active')
    $(this).parent().addClass('active')
    $(this).browseMedia()
  })


  /* ========================================================================
  * Load More
  * ======================================================================== */

  $(document).on('click', '#avisa-media-modal .btn-loadmore', function(event){
    event.preventDefault()
    var $this = $(this)
    var query = $('#avisa-media-modal .input-search-media').val().trim()

    $.ajax({
      type   : 'POST',
      url: 'index.php?option=com_avisamusicdb&view=media&layout=browse&format=json',
      data: {search: query, date: $('#avisa-media-modal .avisa-date-filter').val(), start: $('#avisa-media-modal .avisa-media').find('>li').length},
      beforeSend: function() {
        $this.find('.fa').removeClass('fa-refresh').addClass('fa-spinner fa-spin')
      },
      success: function (response) {
        try {
          var data = $.parseJSON(response)

          $this.find('.fa').removeClass('fa-spinner fa-spin').addClass('fa-refresh')

          $('#avisa-media-modal .avisa-media').append(data.output)

          if(data.loadmore) {
            $('#avisa-media-modal .btn-loadmore').removeAttr('style')
          } else {
            $('#avisa-media-modal .btn-loadmore').hide();
          }

        } catch (e) {
          $('#avisa-media-modal .avisa-media-modal-body-inner').html(response)
        }
      }
    })
  })

  // Date Filter
  // =======================

  $(document).on('change', '#avisa-media-modal .avisa-date-filter', function(event){
    event.preventDefault()
    $(this).browseMedia({
      filter: false,
      date: $(this).val()
    })
  })

  /* ========================================================================
  * Select Media
  * ======================================================================== */
  $(document).on('click', '.avisa-media > li.avisa-media-item', function(event) {
    event.preventDefault()
    $('#avisa-media-modal .avisa-media').find('>li.avisa-media-folder').removeClass('folder-selected')
    $('#avisa-media-modal .avisa-media > li.avisa-media-item').not(this).each(function(){
      $(this).removeClass('selected')
    });

    if($(this).hasClass('selected')) {
      $(this).removeClass('selected')
    } else {
      $(this).addClass('selected')
    }

    if($('#avisa-media-modal .avisa-media > li.avisa-media-item.selected').length) {
      $('#avisa-media-modal .avisa-media-modal-btn-tools').show()
    } else {
      $('#avisa-media-modal .avisa-media-modal-btn-tools').hide()
    }
  })

  /* ========================================================================
  * Insert Media
  * ======================================================================== */
  $(document).on('click', '#avisa-media-modal .btn-insert-media', function(event) {
    event.preventDefault()

    if($(this).data('target') == 'editor') {
      var $target = $('#' + $(this).data('target'))
      var img = $('.avisa-media > li.avisa-media-item.selected').data('path')
      jInsertEditorText('<img src="'+ img +'" alt="">', editor);
    } else {
      var $target = $('#' + $(this).data('target'))
      $target.prev('.avisa-media-preview').removeClass('no-image').attr('src', $('.avisa-media > li.avisa-media-item.selected').data('src'))
      $target.val($('.avisa-media > li.avisa-media-item.selected').data('path'))
    }

    $('.avisa-media-modal-overlay').fadeOut(200, function() {
      $('.avisa-media-modal-overlay').remove()
      $('body').removeClass('avisa-media-modal-open')
    })
  })

  /* ========================================================================
  * Remove Media
  * ======================================================================== */
  $(document).on('click', '.btn-clear-image', function(event) {
    event.preventDefault()

    $(this).parent().find('.avisa-media-preview').addClass('no-image').removeAttr('src')
    $(this).parent().find('.avisa-media-input').val('')
  })

  /* ========================================================================
  * Cancel Media
  * ======================================================================== */
  $(document).on('click', '.btn-cancel-media', function(event) {
    event.preventDefault()
    $('.avisa-media > li.avisa-media-item.selected').removeClass('selected')
    $('.avisa-media-modal-btn-tools').hide()
  })

  /* ========================================================================
  * Upload Media
  * ======================================================================== */

  $(document).on('click', '#avisa-media-modal .btn-upload-media', function(event){
    event.preventDefault()
    $('#avisa-media-modal input[type="file"]').click()
  });

  $(document).on('change', '#avisa-media-modal input[type="file"]', function(event){
    event.preventDefault()
    var $this = $(this)
    var file = $(this).prop('files')[0]
    var formdata = new FormData()

    var allowed = file.type.match('image/jpg') || file.type.match('image/jpeg') || file.type.match('image/png') || file.type.match('image/gif') || file.type.match('image/svg')

    if (allowed) {
      formdata.append('image', file)
      formdata.append('thumbsize', $('#avisa-media-modal').data('thumbsize'))

      if($('#avisa-media-modal .avisa-folder-filter').val() != undefined) {
        formdata.append('folder', $('#avisa-media-modal .avisa-folder-filter').val())
      }

      $(this).uploadMedia({
        data: formdata
      })
    } else {
      alert(Joomla.JText._('AVISAMEDIA_MANAGER_UNSUPPORTED_FORMAT'))
    }

    $this.val('')
  })


  /* ========================================================================
  * Drag & Drop Upload
  * ======================================================================== */

  $(document).on('dragenter', '#avisa-media-modal .avisa-media-modal-body', function (event){
    event.preventDefault()
    $(this).addClass('dragdrop')
  })

  $(document).on('mouseleave', '#avisa-media-modal .avisa-media-modal-body', function (event){
    event.preventDefault()
    $(this).removeClass('dragdrop')
  })

  $(document).on('dragover', '#avisa-media-modal .avisa-media-modal-body', function (event){
    event.preventDefault()
  })

  $(document).on('drop', '#avisa-media-modal .avisa-media-modal-body', function (event){
    event.preventDefault()
    $(this).removeClass('dragdrop')
    var image = event.originalEvent.dataTransfer.files
    var formdata = new FormData()

    var allowed = image[0].type.match('image/jpg') || image[0].type.match('image/jpeg') || image[0].type.match('image/png') || image[0].type.match('image/gif') || image[0].type.match('image/svg')

    if (allowed) {
      formdata.append('image', image[0])
      formdata.append('thumbsize', $('#avisa-media-modal').data('thumbsize'))

      if($('#avisa-media-modal .avisa-folder-filter').val() != undefined) {
        formdata.append('folder', $('#avisa-media-modal .avisa-folder-filter').val())
      }

      $(this).uploadMedia({
        data: formdata
      })
    } else {
      alert(Joomla.JText._('AVISAMEDIA_MANAGER_UNSUPPORTED_FORMAT'))
    }
  })


  /* ========================================================================
  * Delete Media
  * ======================================================================== */

  $(document).on('click', '#avisa-media-modal .btn-delete-media', function(event) {
    event.preventDefault()
    var $this = $(this)
    var $target = $('.avisa-media > li.avisa-media-item.selected')

    if (confirm(Joomla.JText._('AVISAMEDIA_MANAGER_CONFIRM_DELETE')) == true) {
      $.ajax({
        type: "POST",
        url: 'index.php?option=com_avisamusicdb&task=media.delete_media',
        data: {id: $target.data('id')},
        success: function(response) {
          try {
            var data = $.parseJSON(response)
            if(data.status) {
              $target.remove()
              $('.avisa-media-modal-btn-tools').hide()
            } else {
              alert(data.output)
            }
          } catch (e) {
            $('#avisa-media-modal .avisa-media-modal-body-inner').html(response)
          }
        }
      })
    }
  })

  // Close Modal
  $(document).on('click', '.btn-close-modal', function(event) {
    event.preventDefault()
    $('.avisa-media-modal-overlay').fadeOut(200, function() {
      $('.avisa-media-modal-overlay').remove()
      $('body').removeClass('avisa-media-modal-open')
    })
  })

})(jQuery)