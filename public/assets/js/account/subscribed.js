var selected_id, selected_type
$(document).ready(function () {
  getSubscribed()
})
$(document).on('click', '.unsubscribeBtn', function () {
  selected_id = $(this).data('id')
  selected_type = $(this).data('type')

  askToast.question('Confirm', 'Do you want to unsubscribe?', 'switchAction')
})
$('#submit_form').on('submit', function (e) {
  e.preventDefault()
  $('.smtBtn').html("<i class='fa fa-spinner fa-spin'></i>").prop('disabled', true)
  $.ajax({
    url: $(this).attr('action'),
    method: 'POST',
    data: new FormData(this),
    dataType: 'JSON',
    contentType: false,
    cache: false,
    processData: false,
    success: function (result) {
      if (result.status == 1) {
        itoastr('success', 'Success!')
        $('.smtBtn').html('Update').prop('disabled', false)
      }
    }
  })
})
$('#status').on('change', function () {
  if ($(this).prop('checked') === true) {
    $('.sub_category').removeClass('d-none')
  } else {
    $('.sub_category').addClass('d-none')
  }
})
$('.category_item').on('change', function () {
  if ($(this).prop('checked') === true) {
    $(this).parent().prev().prop('disabled', true)
  } else {
    $(this).parent().prev().prop('disabled', false)
  }
})
function getSubscribed() {
  $.ajax({
    url: '/admin/subscribed',
    dataType: 'JSON',
    contentType: false,
    cache: false,
    processData: false,
    success: function (result) {
      if (result.status === 1) {
        $('.result_area').html(result.data)
      }
    }
  })
}
function switchAction() {
  $.ajax({
    url: '/admin/subscribed/switch',
    method: 'get',
    data: { id: selected_id, type: selected_type },
    success: function (result) {
      console.log(result)
      if (result.status === 1) {
        itoastr('success', 'Success!')
        getSubscribed()
      }
    },
    error: function (e) {
      console.log(e)
    }
  })
}
