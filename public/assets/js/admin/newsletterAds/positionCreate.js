var selected = 0
$(document).ready(function() {
  $('.non_search_select2').select2({
    placeholder: 'Choose Ads Type',
    width: '100%',
    minimumResultsForSearch: -1
  })
})

$('#submit_form').on('submit', function(e) {
  e.preventDefault()

  btnLoading()
  $.ajax({
    url: $(this).attr('action'),
    method: 'POST',
    data: new FormData(this),
    dataType: 'JSON',
    contentType: false,
    cache: false,
    processData: false,
    success: function(result) {
      btnLoadingStop()
      clearError()
      if (result.status === 0) {
        dispErrors(result.data)
        dispValidErrors(result.data)
      } else {
        itoastr('success', 'Success!')
        redirectAfterDelay(result.data)
      }
    },
    error: function(e) {
      console.log(e)
    }
  })
})
