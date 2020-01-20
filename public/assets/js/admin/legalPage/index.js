$(function () {
  getDatatableTable()
})
function getDatatableTable() {
  $.ajax({
    url: '/admin/legalPage',
    type: 'get',
    dataType: 'JSON',
    contentType: false,
    cache: false,
    processData: false,
    success: function (result) {
      if (result.status === 1) {
        $('#legal_page_area').html(result.all)
      }
    },
    error: function (e) {
      console.log(e)
    }
  })
}
