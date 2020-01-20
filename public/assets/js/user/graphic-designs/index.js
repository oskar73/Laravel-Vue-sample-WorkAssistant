let tableAll, tables = [];
let deleteUrl
let callProgress = 1

$(function () {
  tableAll = $('.datatable-all').DataTable(setParam('all'))
  for(const graphic of userGraphics) {
    tables[graphic.slug] = $(`.datatable-${graphic.slug}`).DataTable(setParam(graphic.id))
  }
})
$('.datatable-all').on('draw.dt', function () {
  $('.all_count').html(tableAll.ajax.json().recordsTotal)
  checkProgress('all')
})

for(const graphic of userGraphics) {
  $(`.datatable-${graphic.slug}`).on('draw.dt', function () {
    $(`.${graphic.slug}_count`).html(tables[graphic.slug].ajax.json().recordsTotal)
    checkProgress(graphic.slug)
  })
}

function setParam(graphic_id) {
  let ajax = {
    url: window.route('user.graphics.index'),
    type: 'get',
    data: { graphic_id: graphic_id }
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

        tableAll.ajax.reload()
        for (const table of tables) {
          table.ajax.reload()
        }
      } else {
        dispErrors(result.data)
      }
    },
    error: function (e) {
      console.log(e)
    }
  })
}

$(document).on('click', '.downloadDesignBtn', function (e) {
  e.preventDefault()

  $.ajax({
    url: $(this).attr('href'),
    success: function (result) {
      if (result.status === 1) {
        const downloadLink = document.createElement('a')
        downloadLink.href = result.data
        downloadLink.download = 'design.png'
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
      url: '/account/graphics/progress',
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
