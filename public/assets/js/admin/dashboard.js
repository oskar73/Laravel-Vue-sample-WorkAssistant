$(document).ready(function () {
  hashUpdate(window.location.hash)
  getCardData(0)
  $('.website').selectpicker()
})
$('#analytics_form').on('submit', function (e) {
  e.preventDefault()
  $('.analytics_smt_btn').prop('disabled', true)
  $.ajax({
    url: $(this).attr('action'),
    method: 'POST',
    data: new FormData(this),
    dataType: 'JSON',
    contentType: false,
    cache: false,
    processData: false,
    success: function (result) {
      $('.analytics_smt_btn').prop('disabled', false)
      if (result.status === 0) {
        dispErrors(result.data)
      } else {
        itoastr('success', 'Successfully Updated!')
        window.setTimeout(function () {
          window.location.reload()
        }, 1000)
      }
    },
    error: function (e) {
      console.log(e)
    }
  })
})
$('.revoke_btn').click(function (e) {
  e.preventDefault()
  askToast.question('Confirm', 'Are you sure to revoke file? This will disable analytics view.', 'revokeFile')
})
$('#website').on('change', function () {
  getCardData($(this).val())
})
function getCardData(id) {
  $.ajax({
    url: '/admin/dashboard/getCardData',
    data: { id: id },
    success: function (result) {
      if (result.status === 1) {
        $('.dashboard_card_area').html(result.data)
      }
    },
    error: function (e) {
      console.log(e)
    }
  })
}
function revokeFile() {
  $.ajax({
    url: '/admin/dashboard/analytics',
    method: 'PUT',
    data: { _token: token },
    success: function (result) {
      if (result.status === 0) {
        dispErrors(result.data)
      } else {
        itoastr('success', 'Successfully revoked!')
        window.setTimeout(function () {
          window.location.reload()
        }, 1000)
      }
    },
    error: function (e) {
      console.log(e)
    }
  })
}
