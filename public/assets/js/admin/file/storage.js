var main = 0
var users = 0
var c_tab

$(document).ready(function () {
  let hash = window.location.hash
  if (hash === '' || hash === '#/main') {
    c_tab = 'main'
  } else {
    c_tab = 'users'
  }
  hashUpdate(hash)
  getFile()
})
function getFile() {
  $.ajax({
    url: '/admin/file/storage/getData',
    data: { area: c_tab },
    success: function (result) {
      if (c_tab === 'main' && main !== 1) {
        main = 1
        $('#' + c_tab + '_area').html(result.data)
      } else if (c_tab === 'users' && users !== 1) {
        users = 1
        $('#' + c_tab + '_area').html(result.data)
      }
    },
    error: function (e) {
      console.log(e)
    }
  })
}
$(document).on('click', '.file_tab', function () {
  if ($(this).data('area') === '#main') {
    c_tab = 'main'
  } else {
    c_tab = 'users'
  }
  getFile()
})
