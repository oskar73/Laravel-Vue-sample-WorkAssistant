/**
 * Template Preview
 */
$(document).on('click', '.template_item_choose', function (e) {
    e.preventDefault()
    $.ajax({
        type: 'get',
        url: '/template/preview/' +  $(this).data('slug'),
        success: function (result) {
            console.log(result)
            if (result.status === 1) {
                window.document.getElementsByTagName('html')[0].style.overflow = 'hidden'
                $('.template_preview_window').addClass('active').html(result.data)
            }
        },
        error: function (e) {
            console.error(e)
        }
    })
})

$(document).on('click', '.preview_header .view_switch_btn', function () {
    $('.parent_iframe_area')
        .removeClass()
        .addClass('parent_iframe_area')
        .addClass($(this).data('hook') + '_area')
})

$(document).on('click', '.preview_header .back_btn', function (e) {
    e.preventDefault()
    $('.template_preview_window').removeClass('active')
    window.document.getElementsByTagName('html')[0].style.overflow = ''
})

$(document).on('click', '.choose_btn', function (e) {
    e.preventDefault()
    window.location.href = $(this).data('url');
})
