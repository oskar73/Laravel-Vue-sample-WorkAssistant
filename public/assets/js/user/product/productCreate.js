let i = 0
let j = 0
let k = 0
let slimOption, slimCropper
var addedSizes = [], addedColors = []

$(document).ready(function () {

    slimOption = {
        ratio: '1:1',
        download: true,
        buttonRemoveTitle: 'upload',
        instantEdit: false,
        maxFileSize: 50,
        label: 'Drop or choose image'
    }

    let slimInput = $('#slimInput')
    slimCropper = new Slim(slimInput[0], slimOption)
    if (window.thumbNailUrl) {
        slimCropper.load(window.thumbNailUrl + '?1')
    }

    $('.timepicker').timepicker({
        minuteStep: 30,
        showMeridian: !1
    })
})
$(document).on('click', '.btn_remove', function () {
    var $row_id = $(this).data('id')
    $('#' + $row_id + '').remove()
})

$(document).on('click', '.delSizeBtn,.delColorBtn', function () {
    let row = $(this).closest('tr')
    let dataId = $(this).data('id')
    row.remove();

    if ($(this).hasClass('delSizeBtn')) {
        addedSizes = addedSizes.filter(item => item != dataId)
    } else if ($(this).hasClass('delColorBtn')) {
        addedColors = addedColors.filter(item => item != dataId)
    }
})

function additionalAddRow(content='', type='', id='', price='', delBtnClass='') {
    $('#additionalTableBody').append(
        `<tr>
            <td>${content}</td>
            <td class="text-capitalize">${type}</td>
            <td>${id}</td>
            <td>
                <input type="hidden" name="${type}Ids[]" value="${id}">
                <input type="hidden" name="${type}Prices[]" value="${id}">
                <a href="javascript:void(0)" class="m-portlet__nav-link btn m-btn m-btn--hover-brand m-btn--icon m-btn--icon-only m-btn--pill ${delBtnClass}"
                    title="Delete" data-id="${id}">
                        <i class="la la-remove"></i>
                </a>
            </td>
        </tr>`
    )
}

$(document).on('click', '#addSize', function () {
    let sizeText = $('#sizeId').find(":selected").text()
    let sizeId = $('#sizeId').val()
    let sizePrice = $('#sizePrice').val()

    if (sizeId === ''){
        $('.error-sizeId').html('Required')
        setTimeout( "$('.error-sizeId').html('');", 3000);
    }
    else if (sizePrice === ''){
        $('.error-sizePrice').html('Required')
        setTimeout( "$('.error-sizePrice').html('');", 3000);
    }
    else if(sizeId != '' && sizePrice != '' && !addedSizes.includes(sizeId))
        additionalAddRow(sizeText, 'size', sizeId, sizePrice, 'delSizeBtn')

    if (addedSizes.includes(sizeId)) {
        $('.error-sizeId').html('Already exists!')
        setTimeout( "$('.error-sizeId').html('');", 3000);
    } else {
        addedSizes.push(sizeId);
    }

    $('#sizeId').val(''); $('#sizePrice').val('')
})

$(document).on('click', '#addColor', function () {
    let colorText = $('#colorId').find(":selected").text()
    let colorId = $('#colorId').val()
    let colorPrice = $('#colorPrice').val()

    if (colorId === ''){
        $('.error-colorId').html('Required')
        setTimeout( "$('.error-colorId').html('');", 3000);
    }
    else if (colorPrice === ''){
        $('.error-colorPrice').html('Required')
        setTimeout( "$('.error-colorPrice').html('');", 3000);
    }
    else if(colorId != '' && colorPrice != '' && !addedColors.includes(colorId))
        additionalAddRow(colorText, 'color', colorId, colorPrice, 'delColorBtn')

    if (addedColors.includes(colorId)) {
        $('.error-colorId').html('Already exists!')
        setTimeout( "$('.error-colorId').html('');", 3000);
    } else {
        addedColors.push(colorId);
    }

    $('#colorId').val(''); $('#colorPrice').val('')
})

$(document).on('click', '.checkbox', function () {
    var $weekday = $(this).data('name')
    if ($(this).prop('checked') == true) {
        $('.' + $weekday + '_table_area').css('display', 'table')
    } else {
        $('.' + $weekday + '_table_area').css('display', 'none')
    }
})
$('#submit_form').submit(function (event) {
    event.preventDefault()
    $('.smtBtn').prop('disabled', true).html("<i class='fa fa-spinner fa-spin fa-2w'></i>")
    $.ajax({
        url: '/account/product/store',
        method: 'POST',
        data: new FormData(this),
        dataType: 'JSON',
        contentType: false,
        cache: false,
        processData: false,
        success: function (result) {
            $('.smtBtn').prop('disabled', false).html('Submit')
                console.log(result)
            if (result.status === 0) {
                dispErrors(result.data)
            } else {
                itoastr('success', 'Success!')
                window.setTimeout(function () {
                    window.location.href = '/account/product'
                }, 1000)
            }
        },
            error: function (e) {
            console.log(e)
        }
    })
})
