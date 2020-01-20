$(document).on('blur', '.addPrice', function (e) {
  let $addObj = parseFloat($(this).text())
  let $addPrice = $.isNumeric($addObj) ? $addObj : 0
  let id = $(this).parent().data('id')
  let action = $(this).parent().data('action')
  let $yourObj = parseFloat($(this).parent().find('.yourPrice').text())
  let $yourPrice = $.isNumeric($yourObj) ? $yourObj : 0
  let $totalPrice = parseFloat($addPrice) + parseFloat($yourPrice)
  $(this).parent().find('.totalPrice').html(parseFloat($totalPrice).toFixed(2))

  $(this).html(parseFloat($addPrice).toFixed(2))

  $.ajax({
    headers: {
      'X-CSRF-TOKEN': token
    },
    url: '/admin/domainPrice/update/' + id,
    method: 'PUT',
    data: { Action: action, addPrice: $addPrice },
    success: function (result) {
      if (result.error) {
        dispErrors(result.message)
      } else {
        itoastr('success', result.message)
      }
    },
    error: function () {}
  })
})
$(document).on('change', '.switchStatus', function () {
  let obj = $(this).data('obj')
  let id = $(this).data('id')
  let action = $(this).data('action')
  let value = $(this).val()

  $.ajax({
    url: '/admin/domainPrice/switch',
    method: 'GET',
    data: { obj: obj, id: id, action: action, value: value },
    success: function (result) {
      console.log(result)
      if (result.error) {
        dispErrors(result.message)
      } else {
        itoastr('success', result.message)
      }
    },
    error: function (e) {
      console.log(e)
    }
  })
})
