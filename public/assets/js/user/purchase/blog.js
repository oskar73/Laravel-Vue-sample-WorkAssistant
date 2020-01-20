$(document).ready(function () {
  hashUpdate(window.location.hash)
  getData()
})
function getData() {
  $.ajax({
    url: '/account/purchase/blog',
    type: 'get',
    dataType: 'JSON',
    contentType: false,
    cache: false,
    processData: false,
    success: function (result) {
      if (result.status === 1) {
        $('#all_area .m-portlet__body').html(result.all)
        $('.all_count').html(result.count.all)
        $('.datatable').dataTable(dataTblSet())
      }
    },
    error: function (e) {
      console.log(e)
    }
  })
}
