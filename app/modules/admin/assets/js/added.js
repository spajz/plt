/*
 <a href="posts/2" data-method="delete"> <---- We want to send an HTTP DELETE request

 - Or, request confirmation in the process -

 <a href="posts/2" data-method="delete" data-confirm="Are you sure?">
 */
var laravel = {
    initialize: function () {
        this.methodLinks = $('a[data-method]');

        this.registerEvents();
    },

    registerEvents: function () {
        this.methodLinks.on('click', this.handleMethod);
    },

    handleMethod: function (e) {
        var link = $(this);
        var httpMethod = link.data('method').toUpperCase();
        var form;

        // If the data-method attribute is not PUT or DELETE,
        // then we don't know what to do. Just ignore.
        if ($.inArray(httpMethod, ['PUT', 'DELETE']) === -1) {
            return;
        }

        // Allow user to optionally provide data-confirm="Are you sure?"
        if (link.data('confirm')) {
            if (!laravel.verifyConfirm(link)) {
                return false;
            }
        }

        form = laravel.createForm(link);
        form.submit();

        e.preventDefault();

    },

    verifyConfirm: function (link) {
        return confirm(link.data('confirm'));
    },

    createForm: function (link) {
        var form =
                $('<form>', {
                    'method': 'POST',
                    'action': link.attr('href')
                });

        var token =
                $('<input>', {
                    'type': 'hidden',
                    'name': 'csrf_token',
                    'value': '<?php echo csrf_token(); ?>' // hmmmm...
                });

        var hiddenInput =
                $('<input>', {
                    'name': '_method',
                    'type': 'hidden',
                    'value': link.data('method')
                });

        return form.append(token, hiddenInput)
                .appendTo('body');
    }
};

function ajaxLoaderShow() {
    $('#ajax-loader').delay(100).show('fast');
}

function ajaxLoaderHide() {
    $('#ajax-loader').delay(100).hide('fast');
}

function documentScroll() {
    // Scroll to postion from cookie
    $(document).scrollTop($.cookie('scrollTop') ? $.cookie('scrollTop') : 0);
}

// Sortable
function tableSortable() {
    $('.table-sortable tbody').sortable({
        handle: '.sortable-handle',
        placeholder: 'ui-sortable-placeholder',
        start: function (e, ui) {
            ui.placeholder.height(ui.helper.outerHeight());
        },
        helper: function (e, tr) {
            var $originals = tr.children();
            var $helper = tr.clone();
            $helper.children().each(function (index) {
                // Set helper cell sizes to match the original sizes
                $(this).width($originals.eq(index).width());
            });
            return $helper;
        },
        update: function (event, ui) {
            var element = ui.item;
            var sortData = $(this).sortable('serialize', {
                attribute: 'data-id'
            });
            var model = $(this).attr('data-model');
            model = 'model=' + model + '&';
            $.ajax({
                type: 'post',
                url: baseUrl + '/admin/api/sort-model',
                data: model + sortData,
                success: function (data) {
                    var tdElem = element.find('td');
                    var color = tdElem.css('background-color');
                    tdElem.css('background-color', '#87b87f').animate({
                        backgroundColor: color
                    }, 800, function () {
                        tdElem.removeAttr('style');
                    });
                },
                error: function (xhr, status, error) {
                    alert(xhr.responseText);
                },
                beforeSend: function () {
                }
            });
        }
    });
};

var allowScroll = true;

function processDynamic() {
    $('.dynamic').each(function () {
        var thisObj = $(this);
        var model = thisObj.attr('data-main-model');
        var id = thisObj.attr('data-id');
        var view = thisObj.attr('data-view');
        $.ajax({
            type: 'get',
            url: baseUrl + '/admin/api/get-model-by-id/' + model + '/' + id + '/' + view,
            success: function (data) {
                thisObj.replaceWith(data);
                tableSortable();
                if (allowScroll) {
                    documentScroll();
                }
            }
        });
    });
};

$(document).ready(function () {

    documentScroll();

    // Notification
    function showNotification(msg) {
        var box = $('#notification-box');
        if (msg) {
            box.html(msg);
        }
        if (box.html()) {
            if (box.not(':visible')) box.hide();
            box.delay(300).slideDown('fast');
        }
    }

    showNotification();

    function createMsg(msg, type) {
        return $.ajax({
            type: 'post',
            url: baseUrl + '/admin/api/create-msg',
            async: false,
            data: { msg: msg, type: type},
            beforeSend: function () {
            }
        }).responseText
    }

    // Set scrollTop cookie
    $('body').on('click', 'a, button', function () {
        var date = new Date();
        var sec = 10;
        date.setTime(date.getTime() + (sec * 1000));
        $.removeCookie('scrollTop');
        $.cookie('scrollTop', $(document).scrollTop(), { expires: date });
    })

    // Confirm dialog
    $('body').on('click', 'a[data-confirm]', function () {
        return confirm($(this).attr('data-confirm'));
    })

    // Ajax loader
    $.ajaxSetup({
        beforeSend: function () {
            ajaxLoaderShow();
        },
        complete: function () {
            ajaxLoaderHide();
        }
    });

    // Submit form on change
    $('body').on('change', '.submit-form-change', function () {
        $(this).closest('form').submit();
    });

    // Submit form on click
    $('body').on('click', '.submit-form-click', function (e) {
        e.preventDefault();
        var form = $(this).attr('data-form');
        if (form) {
            $(form).submit();
        }
        else {
            $(this).closest('form').submit();
        }
    });

    // Check uncheck checkboxes
    $('.check-all').on('click', function (e) {
        e.preventDefault();
        var elem = $(this).attr('data-elem');
        $('input:checkbox', elem).prop('checked', true);
        $(elem).css('background-color', '#6fb3e0');
    });

    $('.uncheck-all').on('click', function (e) {
        e.preventDefault();
        var elem = $(this).attr('data-elem');
        $('input:checkbox', elem).prop('checked', false);
        $(elem).css('background-color', '#eee');
    });

    $('.check-invert').on('click', function (e) {
        e.preventDefault();
        var elem = $(this).attr('data-elem');
        $('input:checkbox', elem).each(function () {
            $(this).trigger('click');
        });
    });

    // Add remove table rows
    $('body').on('click', '.clone-elem', function (e) {
        e.preventDefault();
        var cloneElem = $(this);
        var elem = cloneElem.attr('data-elem');
        if (elem) {
            cloneElem = cloneElem.closest(elem);
        }
        if (cloneElem.siblings().length >= 9) {
            showNotification(createMsg('You can not add more than 10 elements.', 'warning'));
        } else {
            cloneElem.after(cloneElem.clone());
        }
    });

    $('body').on('click', '.remove-elem', function (e) {
        e.preventDefault();
        var removeElem = $(this);
        var elem = removeElem.attr('data-elem');
        if (elem) {
            removeElem = removeElem.closest(elem);
        }
        if (removeElem.siblings().length <= 0) {
            showNotification(createMsg('You can not remove the last element.', 'warning'));
        } else {
            removeElem.remove();
        }
    });

    if (('.table-sortable').length) {
        tableSortable();
    }

    $('body').on('click', '.sortable-handle', function (e) {
        e.preventDefault();
    });

    // Anchor links
    function anchorLinks() {
        var links = [];
        $('.anchor-this').each(function () {
            var id = $(this).attr('id');
            var dataTitle = $(this).attr('data-title');
            var title = dataTitle ? dataTitle : $(this).text();
            if (id) {
                links.push('<a href="#' + id + '">' + title + '</a>');
            }
        });
        $('.anchor-show').html(links.join(' | '));
    }

    if (('.anchor-this').length) {
        anchorLinks();
    }

    // Search engine
    $('body').on('click', '.search-results .pagination a, .search-start', function (e) {
        e.preventDefault();
        var thisObj = $(this);
        var term = encodeURIComponent($('.search-term').val());
        var href = thisObj.attr('href');
        var id = $('#id').val();
        if (thisObj.attr('class')) {
            var scroll = thisObj.attr('class').indexOf('search-start')
        }
        $.ajax({
            type: 'get',
            url: href,
            data: { term: term, id: id},
            success: function (data) {
                $('.search-results').html(data);
                if (scroll) $(document).scrollTop(thisObj.offset().top);
            },
            error: function (xhr, status, error) {
                alert(xhr.responseText);
            }
        });
    });

    $('body').on('click', '.search-checkbox', function () {
        if ($(this).is(':checked')) {
            $(this).closest('.search-item-box').css('background-color', '#6fb3e0');
        } else {
            $(this).closest('.search-item-box').css('background-color', '#eee');
        }
    });

    $('body').on('submit', '.ajax-form', function (e) {
        e.preventDefault();
        var form = $(this);
        var displayBox = $(this).attr('data-box');
        $.ajax({
            type: 'post',
            url: form.attr('action'),
            data: form.serialize(),
            success: function (data) {
                $(displayBox).html(data);
            }
        });
    });

    // Add items from search
    $('body').on('submit', '#search-engine-form', function (e) {
        e.preventDefault();
        allowScroll = false;
        var form = $(this);
        var formData = form.serializeArray();
        formData.push({name: 'id', value: $('#id').val()});
        formData.push({name: 'model', value: $('input[name=model]').val()});
        $.ajax({
            type: 'post',
            url: form.attr('action'),
            data: formData,
            success: function (data) {
                console.log(data);
                processDynamic();
                showNotification(data);
            }
        });
    });

    // Fancybox
    $('.fb-videos').fancybox({
        helpers: {
            media: {}
        }
    });

    $('.fb-images').fancybox({
        openEffect: 'none',
        closeEffect: 'none',
        maxWidth: '75%',
        maxHeight: '75%',
        autoSize: false,
        closeClick: false
    });

    // Change status
    $('body').on('click', '.toggle-status', function (e) {
        e.preventDefault();
        var thisObj = $(this);
        var model = $(this).attr('data-model');
        var id = $(this).attr('data-id');
        $.ajax({
            type: 'get',
            url: baseUrl + '/admin/api/toggle-status/' + model + '/' + id,
            success: function (data) {
                thisObj.replaceWith(data);
            },
            beforeSend: function () {
            }
        });
    });

    if (('.dynamic').length) {
        processDynamic();
    }

    // Crop image in fancybox
    var jcropObj = null;
    /* Set to your image here */
    $('.fb-crop').fancybox({
        fitToView: true,
        afterShow: function () {
            initCrop($(this));
        },
        onUpdate: function () {
            initCrop($(this));
        },
        afterLoad: function () {
            this.title = '<form action="" method="post">';
            this.title += '<input type="hidden" name="image" value="' + this.href + '">';
            this.title += '<input type="hidden" name="crop[x]" value="0">';
            this.title += '<input type="hidden" name="crop[y]" value="0">';
            this.title += '<input type="hidden" name="crop[w]" value="0">';
            this.title += '<input type="hidden" name="crop[h]" value="0">';
            this.title += '<b>Original:</b> ' + $(this).attr('width') + ' x ' + $(this).attr('height');
            this.title += '&nbsp; <b>Croped:</b> <span class="cropedw">N/A</span> x <span class="cropedh">N/A</span>';
            this.title += '<div class="center"><a href="#" class="btn btn-danger marr10 fb-close">';
            this.title += '<i class="icon-remove-sign bigger-110"></i>';
            this.title += 'Close</a>';
            this.title += '<a href="' + this.href + '" class="btn btn-success crop-image-btn" data-config="' + (this.element).attr('data-config') + '">';
            this.title += '<i class="icon-crop bigger-110"></i>';
            this.title += 'Crop</a></div>';
            this.title += '</form>';
        },
        helpers: {
            title: {
                type: 'inside'
            }
        }
    });

    function initCrop(thisObj) {
        if (jcropObj) jcropObj.destroy();
        var inner = $('.fancybox-inner');
        var img = inner.find('img').first();
        img.width(inner.width());
        img.height(inner.height());
        inner.find('img').first().Jcrop({
            onChange: showCoords,
            onSelect: showCoords,
            boxWidth: inner.width(),
            boxHeight: inner.height(),
            trueSize: [thisObj.attr('width'), thisObj.attr('height')]
        }, function () {
            //$.fancybox.update();
            jcropObj = this;
        });
    }

    $('body').on('click', '.fb-close', function (e) {
        e.preventDefault();
        $.fancybox.close();
    })

    $('body').on('click', '.crop-image-btn', function (e) {
        e.preventDefault();
        var thisObj = $(this);
        var formData = thisObj.closest('form').serializeArray();
        formData.push({name: 'config', value: thisObj.attr('data-config')});
        $.ajax({
            type: 'post',
            data: formData,
            url: baseUrl + '/admin/api/crop-image',
            success: function (data) {
                showNotification(createMsg('Image successfully cropped.', 'success'));
                processDynamic();
            }
        });
        $.fancybox.close();

    })

    function showCoords(c) {
        $('.cropedw').text(parseInt(c.w));
        $('.cropedh').text(parseInt(c.h));
        $("input[name='crop[x]']").val(parseInt(c.x));
        $("input[name='crop[y]']").val(parseInt(c.y));
        $("input[name='crop[w]']").val(parseInt(c.w));
        $("input[name='crop[h]']").val(parseInt(c.h));
    }




});

    // Get model list
    function getModelList(model, column, key) {
        $.ajax({
            type: 'get',
            dataType: 'json',
            url: baseUrl + '/admin/api/get-model-list/' + model + '/' + column + '/' + key,
            success: function (data) {
                alert(data)
            },
            beforeSend: function () {
            }
        });
    }