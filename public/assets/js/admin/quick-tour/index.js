var switch_action
var checkbox_count
var alone = 0
var selected
var previewCropped = ''
var isInitialized = false
var cropper = ''
var file = ''

$(function () {
    // $('.select_picker').selectpicker()
    getDatatableTable()
})
function getDatatableTable() {
    $.ajax({
        url: '/admin/quick-tours',
        type: 'get',
        dataType: 'JSON',
        contentType: false,
        cache: false,
        processData: false,
        success: function (result) {
            if (result.status === 1) {
                $('.show_checked').addClass('d-none')

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

function initializeSelect2(data) {
    $("#targetID").select2({
        placeholder: "Select Target",
        allowClear: true,
        tags: false,
        minimumResultsForSearch: -1,
        dropdownAutoWidth: true,
        ajax: {
            url: '/admin/quick-tours/get-target-ids',
            dataType: 'json',
            type: "GET",
            data: function(params) {
                return {
                    selected: data
                }
            }
        }
    });
}

$('.createBtn').click(function () {
    initializeSelect2(null)
    modalInit()
})
function modalInit() {
    $('#item_id').val(null)
    $('#item_modal_form select').val('')
    $('#title').val('')
    $('#description').val('')
    $("#targetID").val('').trigger('change');
    $('#item_modal').modal('toggle')
}

$('#item_modal_form').submit(function (event) {
    event.preventDefault()
    var formData = new FormData(this)

    mApp.block('#item_modal .modal-content', {})
    $.ajax({
        url: '/admin/quick-tours',
        method: 'POST',
        data: formData,
        dataType: 'JSON',
        contentType: false,
        cache: false,
        processData: false,
        success: function (result) {
            console.log(result)
            mApp.unblock('#item_modal .modal-content')
            if (result.status === 0) {
                dispErrors(result.data)
            } else {
                itoastr('success', 'Successfully Updated!')
                $('#item_modal').modal('toggle')
                getDatatableTable()
            }
        },
        error: function (e) {
            console.log(e)
        }
    })
})
$(document).on('click', '.edit_btn', function () {
    var item = $(this).data('item')
    $('#item_id').val(item.id)
    $('#title').val(item.title)
    $('#description').val(item.description)
    $('#targetID').val(item.targetID);
    // $('.selectpicker').selectpicker('refresh');
    if (item.status === 1) {
        $('#status').prop('checked', true)
    } else {
        $('#status').prop('checked', false)
    }
    initializeSelect2(item.targetID)

    $('#item_modal').modal('toggle')
})
$(document).on('change', 'input[type=checkbox]', function () {
    checkbox_count = $('.datatable tbody input[type=checkbox]:checked').length
    if (checkbox_count > 0) {
        $('.show_checked').removeClass('d-none')
    } else {
        $('.show_checked').addClass('d-none')
        $('.datatable thead input[type=checkbox]').prop('checked', false)
    }
})

$(document).on('click', '.switchBtn', function () {
    switch_action = $(this).data('action')
    var item = checkbox_count + ' items'
    alone = 0
    switchAlert(item)
})
$(document).on('click', '.switchOne', function () {
    switch_action = $(this).data('action')
    alone = 1
    selected = $(this).parent().parent().find('.checkbox').data('id')
    switchAlert('this item')
})
function switchAlert(item) {
    var msg

    switch (switch_action) {
        case 'active':
            msg = 'Do you want to activate ' + item + '?'
            break
        case 'inactive':
            msg = 'Do you want to make inactivate ' + item + '?'
            break
        case 'delete':
            msg = 'Do you want to delete ' + item + '?'
            break
    }
    askToast.question('Confirm', msg, 'switchAction')
}
function switchAction() {
    $.ajax({
        url: '/admin/quick-tours/switch',
        data: { ids: checkedIds(), action: switch_action },
        method: 'get',
        success: function (result) {
            if (result.error) {
                dispErrors(result.message)
            } else {
                itoastr('success', 'Successfully updated!')
                getDatatableTable()
            }
        },
        error: function (e) {
            console.log(e)
        }
    })
}

$('.sortBtn').click(function () {
    mApp.blockPage()
    $.ajax({
        url: '/admin/quick-tours/sort',
        method: 'GET',
        success: function (result) {
            //   console.log(result)
            mApp.unblockPage()
            $('#sortable').html(result.view)
            $('#sort-modal').modal('toggle')
            $('#sortable').sortable()
            $('#sortable').disableSelection()
        },
        error: function (err) {
            console.log('Error!', err)
        }
    })
})
$('#sort_submit').click(function () {
    mApp.block('#sort-modal .modal-content', {})
    var sorts = []
    $('#sortable li').each(function (index) {
        sorts.push($(this).data('id'))
    })
    $.ajax({
        url: '/admin/quick-tours/sort',
        method: 'POST',
        data: { _token: token, sorts: sorts },
        success: function (result) {
            itoastr('success', 'Successfully Updated!')
            mApp.unblock('#sort-modal .modal-content', {})
            $('#sort-modal').modal('toggle')
            getDatatableTable()
        },
        error: function (err) {
            console.log('Error!', err)
        }
    })
})
