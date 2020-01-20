var $appointment_date = $('#date')
var checkbox_count = 0
var switch_action
var alone = 0
var selected
var unitTable

$(function () {
    unitTable = $('#all_area .datatable-all').DataTable(getUnitTable())
})

function modalClearToggle(unitId = '', name = '') {
    $('.form-control-feedback').html('')
    $('#unit_id').val(unitId)
    $('#name').val(name)

    $('#addUnit_modal').modal('toggle')
}

function getUnitTable() {
    let ajax = {
        url: '/account/product/unit',
        type: 'get',
        data: {}
    }

    let columns = [
        { data: 'checkbox', name: 'checkbox', orderable: false, searchable: false },
        { data: 'name', name: 'name' },
        { data: 'action', name: 'action', orderable: false }
    ]

    return setTbl(ajax, columns, 2, false)
}

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
$(document).on('click', '.newUnit', function () {
    modalClearToggle()
})
$(document).on('click', '.edit_btn', function () {
    modalClearToggle($(this).data('id'), $(this).data('name'))
})
function switchAlert(item) {
    var msg
    switch (switch_action) {
        case 'approve':
            msg = 'Do you want to approve ' + item + '?'
        break
        case 'cancel':
            msg = 'Do you want to cancel ' + item + '?'
        break
        case 'delete':
            msg = 'Do you want to delete ' + item + '?'
        break
    }
    askToast.question('Confirm', msg, 'switchAction')
}

function switchAction() {
    $.ajax({
        url: '/account/product/unit/switch',
        data: { ids: checkedIds(), action: switch_action },
        method: 'get',
        success: function (result) {

            if (result.status === 0) {
                dispErrors(result.data)
            } else {
                itoastr('success', 'Successfully updated')
                unitTable.ajax.reload()
            }
        },
        error: function (e) {
            console.log(e)
        }
    })
}

$('#addUnitForm').on('submit', function (e) {
    e.preventDefault()
    $('.smtBtn').html("<i class='fa fa-spin fa-spinner fa-2x'></i>").prop('disabled', true)

    //store url
    let url = '/account/product/unit'
    let unitId = $('#unit_id').val()

    if (unitId != '') {
        url = '/account/product/unit/update/'+unitId
    }

    $.ajax({
        url: url,
        method: 'POST',
        data: new FormData(this),
        dataType: 'JSON',
        contentType: false,
        cache: false,
        processData: false,
        success: function (result) {
            $('.smtBtn').prop('disabled', false).html('Submit')

            if (result.status === 0) {
                dispErrors(result.data)
                dispValidErrors(result.data)
            } else {
                itoastr('success', 'Success!')

                $('#addUnit_modal').modal('hide')
                unitTable.ajax.reload()
            }
        },
        error: function (e) {
            console.log(e)
        }
    })
})

