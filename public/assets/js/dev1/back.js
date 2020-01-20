$(document).ready(function() {
  var $container = $('#m_ver_menu')
  var $scrollTo = $('#m_ver_menu .m-menu__item--active').not('.m-menu__item--open')
  if ($scrollTo.length) {
    var height = $scrollTo.offset().top - $container.height() / 2

    if (height > 0) {
      $('#m_ver_menu').stop().animate(
        {
          scrollTop: height
        },
        300
      )
    }
  }
})

$(document).on('keydown', function(e) {
  if (e.keyCode === 191 && !$('input').is(':focus')) {
    e.preventDefault()
    document.getElementById('header_search').focus()
  }
})
$(document).on('click', '.selectAll', function() {
  var table = $(this).data('area')
  $('.datatable tbody input[type=checkbox]').prop('checked', false)
  $('.datatable:not(.' + table + ') thead input[type=checkbox]').prop('checked', false)

  if ($(this).prop('checked') === true) {
    $('.' + table + ' input[type=checkbox]').prop('checked', true)
  } else {
    $('.' + table + ' input[type=checkbox]').prop('checked', false)
  }
})
$('.m-menu__item .m-menu__link:not(.m-menu__toggle)').click(function() {
  $('.m-menu__item').removeClass('m-menu__item--active')
  $(this).parent().addClass('m-menu__item--active')
})
$('.datatable').on('draw.dt', function() {
  $('.datatable thead input[type=checkbox]').prop('checked', false)
  $('.show_checked').addClass('d-none')
})
$(document).on('mouseenter', '.hover-handle', function(e) {
  e.preventDefault()
  $(this).toggleClass('d-none')
  $(this).next().toggleClass('d-none')
})
$(document).on('mouseleave', '.down-handle', function(e) {
  e.preventDefault()
  $(this).toggleClass('d-none')
  $(this).prev().toggleClass('d-none')
})

var dataTblSet = () => {
  return {
    iDisplayLength: 10,
    aLengthMenu: [
      [10, 20, 50, 100, -1],
      [10, 20, 50, 100, 'All']
    ],
    deferRender: true,
    dom: '<\'row\'<\'col-4\'i><\'col-8\'f>>' + '<\'row\'<\'col-sm-12\'tr>>' + '<\'row\'<\'col-sm-12 col-md-5\'l><\'col-sm-12 col-md-7\'p>>',
    language: {
      info: 'Total: _TOTAL_',
      sLengthMenu: '_MENU_'
    },
    order: [],
    stateSave: true,
    columnDefs: [{
      targets: 'no-sort',
      orderable: false
    }]
  }
}

function setTbl(ajax, columns, order = 1, asc = true) {
  return {
    processing: true,
    serverSide: true,
    retrieve: true,
    ajax: ajax,
    columns: columns,
    order: [[order, asc ? 'asc' : 'desc']],
    iDisplayLength: 10,
    aLengthMenu: [
      [10, 20, 50, 100, -1],
      [10, 20, 50, 100, 'All']
    ],
    deferRender: true,
    dom: '<\'row\'<\'col-4\'i><\'col-8\'f>>' + '<\'row\'<\'col-sm-12\'tr>>' + '<\'row\'<\'col-sm-12 col-md-5\'l><\'col-sm-12 col-md-7\'p>>',
    language: {
      info: 'Total: _TOTAL_',
      sLengthMenu: '_MENU_',
      processing: '<i class="fa fa-spinner fa-spin fa-3x fa-fw"></i><span class="loading-txt">Loading...</span>'
    }
  }
}

$(document).on('change', '.uploadImageBox', function(event) {
  var target = $(this).data('target')
  $('#' + target).show()
  var reader = new FileReader()
  reader.onload = function() {
    var output = document.getElementById(target)
    output.src = reader.result
  }
  reader.readAsDataURL(event.target.files[0])
})

function checkedIds() {
  let ids = []
  if (alone === 0) {
    $('.datatable tbody input[type=checkbox]:checked').each(function(index) {
      ids.push($(this).data('id'))
    })
  } else {
    ids.push(selected)
  }
  return ids
}

function makeid(length) {
  var result = ''
  var characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789'
  var charactersLength = characters.length
  for (var i = 0; i < length; i++) {
    result += characters.charAt(Math.floor(Math.random() * charactersLength))
  }
  return result
}

function tinymceInit(selector = 'textarea', inline = false, folder = null) {
  var path
  if (folder == null) {
    path = '/uploadImage'
  } else {
    path = '/uploadImage/' + folder
  }
  tinymce.init({
    selector: selector,
    inline: inline,
    menubar: false,
    resize: 'both',
    statusbar: false,
    style_formats_autohide: true,
    toolbar:
      'undo redo | styleselect  fontselect fontsizeselect  forecolor backcolor | alignleft aligncenter alignright bullist numlist outdent indent code bold italic blockquote | link image preview media table',
    plugins: 'image autolink autoresize code link lists fullscreen media preview autosave table legacyoutput',
    min_height: 150,
    remove_script_host: false,
    convert_urls: true,
    image_title: true,
    automatic_uploads: true,
    relative_urls: false,
    images_upload_url: path,
    file_picker_types: 'image',
    file_picker_callback: function(cb, value, meta) {
      var input = document.createElement('input')
      input.setAttribute('type', 'file')
      input.setAttribute('accept', 'image/*')

      input.onchange = function() {
        var file = this.files[0]

        var reader = new FileReader()
        reader.readAsDataURL(file)
        reader.onload = function() {
          var id = 'blobid' + new Date().getTime()
          var blobCache = tinymce.activeEditor.editorUpload.blobCache
          var base64 = reader.result.split(',')[1]
          var blobInfo = blobCache.create(id, file, base64)
          blobCache.add(blobInfo)
          cb(blobInfo.blobUri(), { title: file.name })
        }
      }
      input.click()
    }
  })

  return true
}

function ajaxSelect2(url, placeholder, id, name) {
  return {
    width: '100%',
    placeholder: placeholder,
    minimumInputLength: 1,
    ajax: {
      url: url,
      dataType: 'json',
      data: function(params) {
        return {
          q: $.trim(params.term)
        }
      },
      delay: 250,
      processResults: function(result) {
        console.log(result)
        return {
          results: $.map(result.data, function(item) {
            return {
              text: item[name],
              id: item[id]
            }
          })
        }
      },
      cache: true
    }
  }
}

$(document).on('click', '#m_quick_sidebar_close', function() {
  $('#m_quick_sidebar').removeClass('m-quick-sidebar--on')
})
$(document).on('click', '#m_quick_sidebar_toggle1', function() {
  $('#m_quick_sidebar').addClass('m-quick-sidebar--on')
  getNote()
})

function getNote() {
  $.ajax({
    url: '/account/note',
    method: 'get',
    success: function(result) {
      if (result.status === 1) {
        $('#note_result').html(result.data)
      }
    }
  })
}

function toggleNote(action, id) {
  $.ajax({
    url: '/account/note/toggle',
    method: 'get',
    data: {
      id: id,
      action: action
    }
  })
}

$('#note').on('keyup', function(e) {
  if (e.key === 'Enter' || e.keyCode === 13) {
    submitNoteForm()
  }
})
$('#note_submit').click(function() {
  submitNoteForm()
})

function submitNoteForm() {
  if (!$('#note').val()) {
    itoastr('error', 'Please fill form.')
  } else {
    $.ajax({
      url: '/account/note',
      method: 'post',
      data: {
        note: $('#note').val(),
        _token: token
      },
      success: function(result) {
        if (result.status === 1) {
          $('#note').val(null)
          getNote()
        } else {
          dispErrors(result.data)
          dispValidErrors(result.data)
        }
      },
      error: function(e) {
        console.log(e)
      }
    })
  }
}

$(document).on('click', '.check_note', function(e) {
  e.preventDefault()
  $(this).toggleClass('slashed_text')
  toggleNote('toggle', $(this).data('id'))
})

$(document).on('click', '.remove_note', function(e) {
  e.preventDefault()
  $(this).parent().parent().remove()
  toggleNote('remove', $(this).data('id'))
})

function simpleTinymce(selector, inline = false) {
  tinymce.init({
    selector: selector, // change this value according to the HTML
    inline: inline,
    plugins: 'link autolink emoticons wordcount paste',
    toolbar: 'bold link unlink emoticons blockquote',
    menubar: false,
    statusbar: false,
    paste_preprocess: function(plugin, args) {
      console.log('Attempted to paste: ', args.content)
      // replace copied text with empty string
      args.content = ''
    }
  })
}

function minimizeSidebar() {
  $('body').addClass('m-aside-left--minimize m-brand--minimize')
  $('#m_aside_left_minimize_toggle').addClass('m-brand__toggler--active')
}

$.fn.extend({
  crud: function(options) {
    let crud = new CRUD({ container: this, ...options })
    crud.initialize()
    return crud
  },
  disable: function(disabled = true) {
    if (disabled && !this.hasClass('disabled')) {
      this.addClass('disabled')
    }

    if (!disabled && this.hasClass('disabled')) {
      this.removeClass('disabled')
    }
  }
})

class CRUD {
  constructor(options) {
    Object.assign(
      this,
      {
        isCsvImport: false,
        dataTable: true,
        dataTableOption: {},
        ajaxOption: {},
        hasImage: true,
        isImageCrop: true,
        markIndex: true,
        isCreateAble: true,
        createUrl: null,
        fnCreate: null,
        fnCreateSuccess: null,
        isUpdateAble: true,
        updateUrl: null,
        editUrl: null,
        fnEdit: null,
        fnUpdate: null,
        fnUpdateSuccess: null,
        isDetailView: false,
        fnView: null,
        multiSubmitForAdd: false,
        editable: true,
        deletable: true,
        deleteUrl: null,
        indexColumnNumber: 0,
        apiProcessing: false,
        updating: false,
        showTableWithAddForm: false,
        tableFetchUrl: null,
        container: null,
        ids: [],
        rows: [],
        table: null,
        fnFilter: null,
        image: null,
        editItemData: null,
        editItem: null,

        // Default Display - 'table', 'create-form', 'update-form'
        defaultDisplay: 'table',

        // Forms
        createForm: null,
        updateForm: null,

        // Buttons
        addButton: null,
        updateButton: null,
        editButton: null,
        csvImportButton: null,
        backButton: null,
        selectAllCheckBox: null,

        chkSelectAll: '.select-all',
        chkSelectItem: '.select-item',

        btnDeleteAll: '.delete-all',
        btnDeleteItem: '.delete-item',
        btnEditItem: '.edit-item'
      },
      options
    )
  }

  initialize() {
    let $this = this

    // Launching create/edit modal

    $('.createBtn').click(function() {
      $('#create_modal').modal('show')
    })

    $this.container.on('click', '.data-table-filter', function() {
      $this.fnFilter($this, $this.table)
    })

    // Create form submit
    $('#create_modal_form').submit(function(e) {
      e.preventDefault()

      let formData = new FormData(this)

      $('input[type="file"]').each((index, item) => {
        formData.append(item.name, item.files[0])
      })

      if (typeof $this.fnCreate == 'function') {
        $this.fnCreate(formData, $(this))
      }

      $this.apiProcessing = true
      $('.smtBtn').loading()

      $.post(this.action, formData, (res) => {
        if (res.tableData.length) {
          if (res.action === 'create') {
            $this.table.fnAddData(res.tableData)
          } else {
            let row = $this.editItemTableRow
            $this.table.fnUpdate(res.tableData, $this.table.fnGetPosition(row[0]))
          }
        }
        itoastr('success', 'Created successfully!')
        if (typeof $this.fnCreateSuccess === 'function') {
          $this.fnCreateSuccess()
        }
        $('#create_modal').modal('hide').clearForm()
        $this.init()
      }).then(() => {
        $this.apiProcessing = false
        $('.smtBtn').loading(false)
      })
    })

    $(this.chkSelectAll).click(function() {
      if ($this.dataTable) {
        let filteredRows = $this.table.$('tr', { filter: 'applied' })
        filteredRows.each((index, item) => {
          $(item).find($this.chkSelectItem).prop('checked', $(this).prop('checked'))
        })
      } else {
        $($this.chkSelectItem).prop('checked', this.checked)
      }

      $this.checkSelectedItems()
    })

    $(document).on('change', $this.chkSelectItem, function() {
      $this.checkSelectedItems()
    })

    $(document).on('click', $this.btnDeleteItem, function() {
      $this.ids = [$(this).data('id')]
      $this.rows = [$(this).parents('tr')]
      askToast.question('Confirm', 'Are you sure?', $this.deleteAction.bind($this, this))
    })

    $(document).on('click', $this.btnEditItem, function() {
      if (!$(this).hasClass('disabled')) {
        $this.editItemData = $(this).data('item')
        $this.editItemTableRow = $(this).parents('tr')
        const modal = $('#create_modal')
        if (typeof $this.fnEdit === 'function') {
          $this.fnEdit($this, $(this))
        }
        modal.modal('show')
      }
    })

    // handle click delete all button
    $(this.btnDeleteAll).click(function(e) {
      e.preventDefault()
      askToast.question('Confirm', 'Are you sure?', $this.deleteAction.bind($this, this))
    })

    if ($this.dataTable) {
      $this.table = $this.container.find('.table').dataTable({
        order: [],
        stateSave: true,
        columnDefs: [
          {
            targets: 'no-sort',
            orderable: false
          },
          {
            targets: 'no-search',
            searchable: false
          }
        ],
        drawCallback: function() {
          $this.dataTableUpdate()
        },
        processing: true,
        ...$this.ajaxOption,
        ...$this.dataTableOption,
        language: {
          loadingRecords: '<div style="margin:40px 0;"><i class="fa fa-spinner fa-spin fa-2x fa-fw"></i></div>',
          processing: ''
        }
      })
    }

    $this.init()
  }

  init() {
    this.ids = []
    this.rows = []
    $(this.btnDeleteAll).prop('disabled', true)
    $('.selectAll').prop('checked', false)
    this.markIndexNumbers()
  }

  deleteAction(button) {
    let formData = new FormData()
    if (this.ids.length > 0) {
      formData.append('ids', this.ids.join(','))
      formData.append('_method', 'delete')
      $(button).loading()
      $.post(this.deleteUrl, formData, (res) => {
        if (res.status) {
          for (let row of this.rows) {
            if (this.dataTable) {
              this.table.fnDeleteRow(row.data(row))
            } else {
              row.remove()
            }
          }

          $(button).loading(false)
          $('#delete-confirm-modal').modal('hide')
          this.init()
          itoastr('success', res.message || 'Deleted Successfully!')
        }
      })
    }
  }

  checkSelectedItems() {
    let disabled = true
    this.ids = []
    this.rows = []
    let $this = this

    if ($this.dataTable) {
      $this.table &&
      $this.table.fnGetNodes().forEach((item) => {
        checkRow(item)
      })
    } else {
      $this.container.find('tbody tr').each(function(index, item) {
        checkRow(item)
      })
    }

    function checkRow(item) {
      if ($(item).find('.select-item').prop('checked') && !$(item).find('.select-item').prop('disabled')) {
        disabled = false
        $(item).find($this.btnEditItem).disable()
        $(item).find($this.btnDeleteItem).disable()
        $this.ids.push($(item).find($this.chkSelectItem).data('id'))
        $this.rows.push($(item))
      } else {
        $(item).find('.edit-item').disable(false)
        $(item).find('.delete-item').disable(false)
      }
    }

    if (this.ids.length) {
      $($this.btnDeleteAll).prop('disabled', false)
    } else {
      $($this.btnDeleteAll).prop('disabled', true)
    }
  }

  // mark index numbers to table.
  markIndexNumbers() {
    let $this = this
    if ($this.dataTable) {
      $this.table &&
      $this.table.fnGetNodes().forEach((item, index) => {
        $this.updating = true
        if ($(item).find('td').length > 3) {
          $($(item).find('td')[$this.indexColumnNumber]).text(index + 1)
        }
      })
    } else {
      $this.container.find('tbody tr').each((index, item) => {
        if ($(item).find('td').length > 3) {
          $($(item).find('td')[$this.indexColumnNumber]).text(index + 1)
        }
      })
    }
  }

  dataTableUpdate() {
    if (!this.updating && !this.apiProcessing) {
      this.markIndex && this.markIndexNumbers()
      this.checkSelectedItems()
    }
    this.updating = false
  }
}

$(document).on('click', '.modal button[type="submit"]', function() {
  let form = $(this).parents('form')
  if (form.length > 0) return
  form = $(this).parents('.modal').find('form')
  if (form) {
    form.submit()
  }
})

$(document).on('click', 'a[data-toggle="modal"]', function(e) {
  e.preventDefault()
  const modal = $(this).data('target')
  const url = $(this).attr('href') ? $(this).attr('href') : $(this).data('url')

  const modalContainer = $(modal)
  if (!modalContainer) {
    throw 'Modal is undefined'
  }

  if (!url) {
    throw 'url is undefined'
  }

  $.ajax({
    type: 'get',
    url: url,
    success: (view) => {
      modalContainer.find('.modal-body').html(view)

      $('.selectpicker').selectpicker()

      modalContainer.modal('show')
    }
  })
})

document.addEventListener('alpine:init', () => {
  window.Alpine.directive('outside-click', function(el, { expression }, { evaluateLater }) {
    const evaluate = evaluateLater(expression)
    document.body.addEventListener('click', function() {
      if (!el.contains(event.target)) {
        evaluate()
      }
    })
  })
})