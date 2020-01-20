var table1, table2, table3
var deleteUrl
var callProgress = 1

$(function () {
  table1 = $('.datatable-all').DataTable(setParam('all'))
  table2 = $('.datatable-notdownload').DataTable(setParam('notdownload'))
  table3 = $('.datatable-download').DataTable(setParam('download'))
})
$('.datatable-all').on('draw.dt', function () {
  $('.all_count').html(table1.ajax.json().recordsTotal)
  checkProgress('all')
})
$('.datatable-notdownload').on('draw.dt', function () {
  $('.notdownload_count').html(table2.ajax.json().recordsTotal)
  checkProgress('notdownload')
})

$('.datatable-download').on('draw.dt', function () {
  $('.download_count').html(table3.ajax.json().recordsTotal)
  checkProgress('download')
})

function setParam(status) {
  let ajax = {
    url: '/account/favicon',
    type: 'get',
    data: { status: status }
  }

  let columns = [
    { data: 'type', name: 'type', orderable: false, searchable: false },
    { data: 'edit', name: 'edit', orderable: false, searchable: false },
    { data: 'preview', name: 'preview', orderable: false, searchable: false },
    { data: 'download1', name: 'download1', orderable: false, searchable: false },
    { data: 'download2', name: 'download2', orderable: false, searchable: false },
    { data: 'view_live', name: 'view_live', orderable: false, searchable: false },
    { data: 'version', name: 'version' },
    { data: 'created_at', name: 'created_at' },
    { data: 'updated_at', name: 'updated_at' },
    { data: 'action', name: 'action', orderable: false, searchable: false }
  ]

  return setTbl(ajax, columns, 8, false)
}
$(document).on('click', '.deleteBtn', function (e) {
  e.preventDefault()
  deleteUrl = $(this).attr('href')
  askToast.question('Confirm', 'Are you sure?', 'switchAction')
})
$(document).on('click', '.preview_img_click', function (e) {
  e.preventDefault()
  var iframe = "<iframe width='100%' frameborder='0' height='100%' src='" + $(this).attr('src') + "'></iframe>"
  var x = window.open()
  x.document.open()
  x.document.write(iframe)
  x.document.close()
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
        table2.ajax.reload()
        table3.ajax.reload()
      } else {
        dispErrors(result.data)
      }
    },
    error: function (e) {
      console.log(e)
    }
  })
}

$(document).on('click', '.downloadLogoTypeBtn', function (e) {
  e.preventDefault()

  $.ajax({
    url: $(this).attr('href'),
    success: function (result) {
      if (result.status === 1) {
        const downloadLink = document.createElement('a')
        downloadLink.href = result.data
        downloadLink.download = 'logotype.png'
        document.body.appendChild(downloadLink)
        downloadLink.click()
        document.body.removeChild(downloadLink)
      } else if (result.status === 0) {
        dispErrors(result.data)
      } else if (result.status === 2) {
        itoastr('info', result.message, 10000)
        redirectAfterDelay(result.redirect, 10000)
      }
    },
    error: function (e) {}
  })
})

function checkProgress($status) {
  let ids = []
  $(`table.datatable-${$status} .progress.progress_el`).each(function () {
    ids.push($(this).data('id'))
  })
  let process = true
  if (ids.length) {
    $.ajax({
      type: 'get',
      url: '/account/favicon/progress',
      data: { ids: ids },
      success: function (result) {
        if (result.status === 1) {
          $.each(result.data, function (index, item) {
            $(`.progress_area[data-id='${index}']`).html(item)
          })
        }
      }
    }).always(function () {
      if (process) {
        setTimeout(() => {
          checkProgress($status)
        }, 1500)
      }
    })
  } else {
    process = false
  }
}
// $(document).on("click", '.downloadPackageBtn', function(e) {
//     e.preventDefault();
//
//     $.ajax({
//         url:$(this).attr("href"),
//         success:function(result) {
//             if(result.status===1)
//             {
//                 itoastr("success", "Success! System started to generate full logo package in the background. We will send to your email full package files in 1 minute!");
//             }else {
//                 dispErrors(result.data)
//             }
//         },
//         error:function(e) {
//             console.log(e);
//         }
//     })
//
// });
