$(document).ready(function () {
  $('.selectpicker').selectpicker()
  $('#date').datepicker({
    format: 'yyyy-mm-dd',
    startDate: '0d',
    todayHighlight: !0,
    autoclose: !0
  })
  $('.timepicker').timepicker({
    minuteStep: 30,
    showMeridian: !1
  })

  let d_status = $('#status').val()
  let d_date = $('#date').val()
  let d_start_time = $('#start_time').val()
  let d_end_time = $('#end_time').val()
  let d_change_arr = [1,1,1]
  function changeStatus(e, val, index){
    if($(e.currentTarget).val() != val){
        $('#status').val('rescheduled').change()
        d_change_arr[index] = 0
      } else {
        d_change_arr[index] = 1
        if(d_change_arr.join('') == '111'){
          $('#status').val(d_status).change()
        }
      }
  }
  $('#date').on('change', (e) => {
    changeStatus(e, d_date, 0)
  })
  $('#start_time').on('change', (e) => {
    changeStatus(e, d_start_time, 1)
  })
  $('#end_time').on('change', (e) => {
    changeStatus(e, d_end_time, 2)
  })
})
$('#submit_form').on('submit', function (e) {
  e.preventDefault()
  $('.smtBtn').html("<i class='fa fa-spin fa-spinner'></i>").prop('disabled', true)
  $.ajax({
    url: $(this).attr('action'),
    method: 'POST',
    data: new FormData(this),
    dataType: 'JSON',
    contentType: false,
    cache: false,
    processData: false,
    success: function (result) {
      console.log(result)
      $('.smtBtn').html('Submit').prop('disabled', false)
      if (result.status === 1) {
        itoastr('success', 'Successfully updated!')
      } else {
        dispErrors(result.data)
      }
    }
  })
})
