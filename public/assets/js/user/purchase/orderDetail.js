$(function () {})

$(document).on('click', '.confirm_btn', function(){
    var id = $(this).data('id')
    $.ajax({
        url: '/account/purchase/order/confirm',
        method: 'POST',
        data: { _token: token, id },
        success: function (result) {
            if (result.status === 1) {
                itoastr('success', 'Successfully confirmed.')
                window.setTimeout(function () {
                window.location.reload()
                }, 1000)
            } else {
                dispErrors(result.data)
            }
        }
    })
})

$(document).on('click', '.del_btn', function(){
    var id = $(this).data('id')
    console.log('con', id)
})