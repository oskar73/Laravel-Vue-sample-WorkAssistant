var id

$(document).ready(function () {
    $('#sortable').sortable({
        update: function() {
            var sorts = []
            $(this).find('.widget-card').each(function () {
                sorts.push($(this).data('id'))
            })
            console.log('sorts', sorts)
            $.ajax({
                url: '/admin/widget/sort',
                method: 'POST',
                data: {
                    _token: token,
                    sorts
                },
                success: function (result) {
                    if (!result.status) {
                        itoastr('danger', 'Sorting failed!')
                    }
                },
                error: function (e) {
                    itoastr('danger', 'Sorting failed!')
                }
            })
        }
    })
    $('#sortable').disableSelection()
    $('.sortable').sortable({
        update: function() {
            const widget = $(this).data('id')
            var sorts = []
            $(this).find('li').each(function (index) {
                sorts.push($(this).data('id'))
            })
            $.ajax({
                url: '/admin/widget/sort/items',
                method: 'POST',
                data: {
                    _token: token,
                    widget,
                    sorts
                },
                success: function (result) {
                    if (!result.status) {
                        itoastr('danger', 'Sorting failed!')
                    }
                },
                error: function (e) {
                    itoastr('danger', 'Sorting failed!')
                }
            })
        }
    })
    $('.sortable').disableSelection()
})

$('.new-widget-btn').on('click', function () {
    var cat = $(this).data('widget')

    $('#category').val(cat)
    $('#id').val(null)
    $('#item_title').val(null)
    $('#url').val(null)
    $('.form-control-feedback').html(null)
    $('#widget_item_title').html('New Widget')
    $('#create_widget_item_modal').modal('toggle')
})

$('.createBtn').on('click', function () {
    $('#category_id').val(null)
    $('#title').val(null)
    $('#description').val(null)
    $('#create_widget_modal').modal('toggle')
})

$('.delete-widget-btn').on('click', function () {
    var title = $(this).data('title')
    id = $(this).data('id')
    askToast.question('Confirm', 'Do you want to delete ' + title + '?', 'deleteAction')
})

function deleteAction() {
    $.ajax({
        url: '/admin/widget/delete/item/' + id,
        method: 'get',
        success: function (result) {
            if (result.error) {
                dispErrors(result.message)
            } else {
                itoastr('success', 'Successfully deleted!')
                setTimeout(() => {
                    location.reload()
                }, 3000)
            }
        },
        error: function (e) {
            console.log(e)
        }
    })
}

$('.edit-btn').on('click', function () {
    var id = $(this).data('id')
    var title = $(this).data('title')
    var description = $(this).data('description')
    var link = $(this).data('link')
    var status = $(this).data('status')

    $('#category_id').val(id)
    $('#title').val(title)
    $('#description').val(description)
    $('#link').val(link)
    $('#status').prop('checked', status)
    $('#create_widget_modal').modal('toggle')
})

$('.edit-widget-btn').on('click', function () {
    var category = $(this).data('category')
    var id = $(this).data('id')
    var title = $(this).data('title')
    var url = $(this).data('url')

    $('#category').val(category)
    $('#id').val(id)
    $('#item_title').val(title)
    $('#url').val(url)
    $('.form-control-feedback').html(null)
    $('#widget_item_title').html('Update Widget')
    $('#create_widget_item_modal').modal('toggle')
})

$('#create_widget_form').on('submit', function (e) {
    e.preventDefault()

    $('.widget-btn').html("<i class='fa fa-spin fa-spinner fa-2x'></i>").prop('disabled', true)

    $.ajax({
        url: $(this).attr('action'),
        method: 'POST',
        data: new FormData(this),
        dataType: 'JSON',
        contentType: false,
        cache: false,
        processData: false,
        success: function (result) {
            if (result.status === 0) {
                dispErrors(result.data)
                dispValidErrors(result.data)
            } else {
                itoastr('success', 'Successfully created!')
                $('#create_widget_modal').modal('hide')
                setTimeout(() => {
                    location.reload()
                }, 3000)
            }
            $('.widget-btn').html("Submit").prop('disabled', false)
        },
        error: function (e) {
            $('.widget-btn').html("Submit").prop('disabled', false)
        }
    })
})

$('#create_widget_item_form').on('submit', function (e) {
    e.preventDefault()

    $('.widget-item-btn').html("<i class='fa fa-spin fa-spinner fa-2x'></i>").prop('disabled', true)

    $.ajax({
        url: $(this).attr('action'),
        method: 'POST',
        data: new FormData(this),
        dataType: 'JSON',
        contentType: false,
        cache: false,
        processData: false,
        success: function (result) {
            if (result.status === 0) {
                dispErrors(result.data)
                dispValidErrors(result.data)
            } else {
                itoastr('success', 'Successfully created!')
                $('#create_widget_item_modal').modal('hide')
                setTimeout(() => {
                    location.reload()
                }, 3000)
            }
            $('.widget-item-btn').html("Submit").prop('disabled', false)
        },
        error: function (e) {
            $('.widget-item-btn').html("Submit").prop('disabled', false)
        }
    })
})