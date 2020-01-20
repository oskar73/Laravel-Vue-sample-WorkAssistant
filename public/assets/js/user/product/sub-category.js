var $appointment_date = $('#date')
var checkbox_count = 0
var switch_action
var alone = 0
var selected
var subCategoryTable

$(function () {
    subCategoryTable = $('#all_area .datatable-all').DataTable(getSubCategoryTable())
})

function modalClearToggle(subCategoryId = '', categoryId = '', name = '',  status = 1) {
    $('.form-control-feedback').html('')

    $('#sub_category_id').val(subCategoryId)
    $('#category_id').val(categoryId).trigger('change')
    $('#name').val(name)
    if (status)
        $('#status').prop('checked', true);
    else
        $('#status').prop('checked', false);

    $('#addSubCategory_modal').modal('toggle')
}

function getSubCategoryTable() {
    let ajax = {
        url: '/account/product/sub-category',
        type: 'get',
        data: {}
    }

    let columns = [
        { data: 'checkbox', name: 'checkbox', orderable: false, searchable: false },
        { data: 'categoryName', name: 'categoryName', orderable: false, searchable: false },
        { data: 'name', name: 'name' },
        { data: 'status', name: 'status', render: function (data, type, obj, meta) {
            let statusAction = ''
            if(obj.status == 1){

                statusAction = `<span class="c-badge c-badge-success hover-handle">Active</span>
                                <a href="javascript:void(0);" class="h-cursor c-badge c-badge-danger d-none origin-none down-handle hover-box switchOne"
                                    data-action="inactive">Inactive?</a>`
            } else {

                statusAction = `<span class="c-badge c-badge-danger hover-handle" >InActive</span>
                                <a href="javascript:void(0);" class="h-cursor c-badge c-badge-success d-none origin-none down-handle hover-box switchOne"
                                    data-action="active">Active?</a>`
            }

            return statusAction;
        }},
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
$(document).on('click', '.newSubCategory', function () {
    modalClearToggle()
})
$(document).on('click', '.edit_btn', function () {
    modalClearToggle($(this).data('id'), $(this).data('product_category_id'), $(this).data('name'), $(this).data('status'))
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
        url: '/account/product/sub-category/switch',
        data: { ids: checkedIds(), action: switch_action },
        method: 'get',
        success: function (result) {

            if (result.status === 0) {
                dispErrors(result.data)
            } else {
                itoastr('success', 'Successfully updated')
                subCategoryTable.ajax.reload()
            }
        },
        error: function (e) {
            console.log(e)
        }
    })
}

$('#addSubCategoryForm').on('submit', function (e) {
    e.preventDefault()
    $('.smtBtn').html("<i class='fa fa-spin fa-spinner fa-2x'></i>").prop('disabled', true)

    //store url
    let url = '/account/product/sub-category'
    let subCategoryId = $('#sub_category_id').val()

    if (subCategoryId != '') {
        url = '/account/product/sub-category/update/'+subCategoryId
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

                $('#addSubCategory_modal').modal('hide')
                subCategoryTable.ajax.reload()
            }
        },
        error: function (e) {
            console.log(e)
        }
    })
})

