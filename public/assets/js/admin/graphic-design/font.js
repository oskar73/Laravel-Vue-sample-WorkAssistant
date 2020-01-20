var table1
var deleteUrl

$(function () {
  hashUpdate(window.location.hash)
  table1 = $('.datatable-all').DataTable(setParam())
})
$('.datatable-all').on('draw.dt', function () {
  $('.all_count').html(table1.ajax.json().recordsTotal)
})

function setParam() {
  let ajax = {
    url: route('admin.graphics.font.index'),
    type: 'get'
  }

  let columns = [
    { data: 'name', name: 'name' },
    { data: 'public_path', name: 'public_path' },
    { data: 'extension', name: 'extension' },
    { data: 'created_at', name: 'created_at' },
    { data: 'action', name: 'action', orderable: false }
  ]

  return setTbl(ajax, columns, 3, false)
}
$(document).on('click', '.deleteBtn', function (e) {
  e.preventDefault()
  deleteUrl = $(this).attr('href')
  askToast.question('Confirm', 'Are you sure?', 'switchAction')
})
function switchAction() {
  $.ajax({
    url: deleteUrl,
    data: { _token: token },
    method: 'delete',
    success: function (result) {
      console.log(result)
      if (result.status === 1) {
        itoastr('success', 'Successfully deleted!')

        table1.ajax.reload()
      } else {
        dispErrors(result.data)
      }
    },
    error: function (e) {
      console.log(e)
    }
  })
}
let files = null
$('#upload-fonts').change(function () {
  files = this.files
  $.each(files, (index, file) => {
    const fontItem = $(`<div class="font-item mt-1 d-flex justify-content-between align-items-center">
                <div class="file-name bordered wrap-content py-1 text-capitalize px-3 text-16px">${file.name}</div>
                <div class="status">
                    <span class="status-icon">
                        <i class="fa fa-spin fa-spinner fa-sm"></i>
                        <i class="fa fa-check-circle text-success"></i>
                        <i class="fa fa-times-circle text-danger"></i>
                    </span>
                    <span class="status-text ml-3">Checking...</span>
                </div>
            </div>`)
    $('.fonts-container').append(fontItem)
    const formData = new FormData()
    formData.append('font', file)
    $.post('/admin/logotypes/item/getFontName', formData, (res) => {
      if (res.status) {
        fontItem.find('.status-icon').html('<i class="fa fa-check-circle text-success"></i>')
        fontItem.find('.status-text').html(res.data)
      }
    })
  })
  $('#upload_fonts_modal').modal('show')
})

$('#upload_fonts_modal_form').submit(function (e) {
  e.preventDefault()
  const formData = new FormData()
  if (files) {
    $.each(files, (index, file) => {
      formData.append('fonts[]', file)
    })
  }
  $('#btnSubmit').loading()
  $.post('/admin/logotypes/font', formData, (res) => {
    itoastr('success', 'Successfully updated!')
    files = null
    $('#upload_fonts_modal').modal('hide')
    $('.fonts-container').empty()
    $('#upload-fonts').val('')
    table1.ajax.reload()
  }).then(() => {
    $('#btnSubmit').loading(false)
  })
})
