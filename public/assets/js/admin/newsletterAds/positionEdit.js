var selected = 0
var del_price_id
var width = type.width
var height = type.height
var slimOption, slimCropper

$(document).ready(function() {
  hashUpdate(window.location.hash)

  $('.non_search_select2').select2({
    placeholder: 'Choose',
    width: '100%',
    minimumResultsForSearch: -1
  })

  getPrice()

  slimOption = {
    ratio: `4:3`,
    download: true,
    buttonRemoveTitle: 'upload',
    instantEdit: false,
    maxFileSize: 10,
    label: 'Drop or choose image'
  }

  let slimInput = $('#thumbnail')
  slimCropper = new Slim(slimInput[0], slimOption)
  if (window.thumbnailUrl) {
    slimCropper.load(window.thumbnailUrl + '?1')
  }

  let imageInput = $('#ads_image')
  let imageCropper = new Slim(imageInput[0], imageOption)
  if (imageUrl) {
    imageCropper.load(imageUrl + '?1')
  }
})

$('#submit_form').on('submit', function(e) {
  e.preventDefault()
  btnLoading()

  $.ajax({
    url: $(this).attr('action'),
    method: 'POST',
    data: new FormData(this),
    dataType: 'JSON',
    contentType: false,
    cache: false,
    processData: false,
    success: function(result) {
      btnLoadingStop()
      clearError()

      if (result.status === 0) {
        dispErrors(result.data)
        dispValidErrors(result.data)
      } else {
        itoastr('success', 'Success!')
        window.location.hash = '#/price'
        reloadAfterDelay()
      }
    },
    error: function(e) {
      console.log(e)
    }
  })
})

function getPrice() {
  $.ajax({
    url: '/admin/newsletterAds/position/edit/' + item_id,
    method: 'get',
    dataType: 'JSON',
    contentType: false,
    cache: false,
    processData: false,
    success: function(result) {
      if (result.status === 0) {
        dispValidErrors(result.data)
      } else {
        $('.price_area').html(result.data)
      }
    },
    error: function(e) {
      console.error(e)
    }
  })
}

$('.addPriceBtn').click(function() {
  $('#edit_price').val(null)
  $('#price_modal').modal('toggle')
})

$('#price_modal_form').on('submit', function(event) {
  event.preventDefault()
  btnLoading()
  $.ajax({
    url: $(this).attr('action'),
    method: 'post',
    data: new FormData(this),
    dataType: 'JSON',
    contentType: false,
    cache: false,
    processData: false,
    success: function(result) {
      btnLoadingStop()
      clearError()

      if (result.status === 0) {
        dispValidErrors(result.data)
        dispErrors(result.data)
      } else {
        itoastr('success', 'Success!')
        $('#price_modal').modal('toggle')
        getPrice()
      }
    },
    error: function(e) {
      console.log(e)
    }
  })
})

$(document).on('blur', '.price', function() {
  if ($(this).val() !== '') {
    $(this).val(parseFloat($(this).val()).toFixed(2))
  }
})

$(document).on('click', '.editPriceBtn', function() {
  var price = $(this).data('price')

  $('#edit_price').val(price.id)
  $('#payment_type').val(price.type)
  $('#period').val(price.period)
  $('#impression').val(price.impression)
  $('#price').val(price.price)
  $('#slashed_price').val(price.slashed_price)
  $('#price_standard').prop('checked', price.standard == 1 ? true : false)
  $('#price_status').prop('checked', price.status == 1 ? true : false)
  $('.payment_type_select').addClass('d-none')
  $(`.${price.type}_select`).removeClass('d-none')
  $('.selectpicker').selectpicker('refresh')

  $('#price_modal').modal('toggle')
})

$(document).on('click', '.delPriceBtn', function() {
  del_price_id = $(this).data('id')
  askToast.question('Confirm', 'Do you want to delete this item?', 'delPerform')
})

function delPerform() {
  $.ajax({
    headers: {
      'X-CSRF-TOKEN': token
    },
    url: '/admin/newsletterAds/position/deletePrice/' + item_id,
    method: 'delete',
    data: { id: del_price_id },
    success: function(result) {
      if (result.status === 0) {
        dispValidErrors(result.data)
      } else {
        itoastr('success', 'Successfully deleted!')
        getPrice()
      }
    },
    error: function(e) {
      console.log(e)
    }
  })
}

$('.statusUpdate').on('click', function(e) {
  e.preventDefault()
  var status = $('#final_status').is(':checked')
  btnLoading('.statusUpdate')

  $.ajax({
    url: $(this).attr('href'),
    data: {
      ids: [item_id],
      action: status === true ? 'active' : 'inactive'
    },
    method: 'get',
    success: function(result) {
      btnLoadingStop('.statusUpdate')
      if (result.status === 0) {
        dispErrors(result.message)
      } else {
        itoastr('success', 'Success!')
        reloadAfterDelay()
      }
    },
    error: function(e) {
      console.log(e)
    }
  })
})

$('#listing_form').on('submit', function(event) {
  event.preventDefault()
  btnLoading()
  $.ajax({
    url: $(this).attr('action'),
    method: 'post',
    data: new FormData(this),
    dataType: 'JSON',
    contentType: false,
    cache: false,
    processData: false,
    success: function(result) {
      btnLoadingStop()
      clearError()

      if (result.status === 0) {
        dispValidErrors(result.data)
        dispErrors(result.data)
      } else {
        itoastr('success', 'Success!')
        window.location.hash = '#/status'
        reloadAfterDelay()
      }
    },
    error: function(e) {
      console.log(e)
    }
  })
})