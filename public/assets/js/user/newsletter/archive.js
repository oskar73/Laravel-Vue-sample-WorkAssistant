$(function() {
  getDatatableTable()
})

function getDatatableTable() {
  $.ajax({
    url: '/account/newsletter/archive',
    type: 'get',
    dataType: 'JSON',
    contentType: false,
    cache: false,
    processData: false,
    success: function(result) {
      if (result.status === 1) {
        $('.show_checked').addClass('d-none')

        $('#all_area .m-portlet__body').html(result.html)
        $('.all_count').html(result.count)
        $('.datatable').dataTable(dataTblSet())
      }
    },
    error: function(e) {
      console.log(e)
    }
  })
}
