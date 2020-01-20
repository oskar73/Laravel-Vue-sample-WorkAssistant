var template_obj = $('#template')
var header_obj = $('#header')
var footer_obj = $('#footer')

$(document).ready(function () {
  hashUpdate(window.location.hash)
  $('#modules').select2({
    placeholder: 'Choose Modules',
    width: '100%'
  })
  $('.select_picker').selectpicker()
  loadCustom()
})
$('#subdomain').on('keyup', function () {
  domain = $(this).val() + $('.bizinasite_domain').html()
  $.ajax({
    url: '/account/website/domainKeyUp',
    data: { subdomain: $(this).val(), domain: domain },
    success: function (result) {
      console.log(result)
      if (result.status === 1) {
        $('.error-subdomain').html('')
      } else {
        dispValidErrors(result.data)
      }
    }
  })
})
template_obj.change(function () {
  var img = $(this).find('option:selected').data('img')
  var slug = $(this).find('option:selected').data('slug')
  var header = $(this).find('option:selected').data('header')
  var footer = $(this).find('option:selected').data('footer')
  console.log(header)
  console.log(footer)
  header_obj.val(header)
  footer_obj.val(footer)
  $('.select_picker').selectpicker('refresh')
  previewTemplate(img, slug)
})
$('#submit_form').submit(function (event) {
  event.preventDefault()

  $('.smtBtn').html("<i class='fa fa-spinner fa-spin fa-2x fa-fw'></i>").attr('disabled', true)

  $.ajax({
    url: '/admin/website/list/create',
    method: 'POST',
    data: new FormData(this),
    dataType: 'JSON',
    contentType: false,
    cache: false,
    processData: false,
    success: function (result) {
      console.log(result)
      $('.smtBtn').html('Submit').attr('disabled', false)
      $('.form-control-feedback').html('')

      if (result.status === 0) {
        dispValidErrors(result.data)
        dispErrors(result.data)
      } else {
        itoastr('success', 'Successfully created!')

        setTimeout(function () {
          window.location.href = '/admin/website/list'
        }, 1000)
      }
    },
    error: function (e) {
      console.log(e)
    }
  })
})

function previewTemplate(img, slug) {
  $('.preview_template').html(
    "<div class='text-right'><a href='/templates/" + slug + "' target='_blank'>Preview <i class='fa fa-external-link-alt'></i></a></div><img src='" + img + "' class='w-100'>"
  )
}
$('#submit_domain').click(function () {
  var domain = $('#connect_domain').val()
  if (domain === '') {
    itoastr('error', 'Please input domain name.')
  } else {
    $(this).html("<i class='fa fa-spinner fa-spin fa-2x fa-fw'></i>").attr('disabled', true)

    $.ajax({
      url: '/admin/website/list/connectDomain',
      type: 'post',
      data: { _token: $('meta[name="csrf-token"]').attr('content'), domain: domain },
      success: function (result) {
        console.log(result)
        $('#submit_domain').html("<i class='la la-arrow-right'></i>").attr('disabled', false)
        if (result.status === 1) {
          $('#connect_domain').val('')
          loadCustom()
        } else {
          dispErrors(result.data)
        }
      },
      error: function (e) {
        console.log(e)
      }
    })
  }
})
function loadCustom() {
  $.ajax({
    url: '/admin/website/list/loadCustom',
    type: 'get',
    success: function (result) {
      console.log(result)
      if (result.status === 1) {
        $('.connected_domains').html(result.data)
      }
    },
    error: function (e) {
      console.log(e)
    }
  })
}
$(document).on('click', '.checknow', function () {
  var id = $(this).data('id')

  $.ajax({
    url: '/admin/website/list/checkDns',
    type: 'get',
    data: { id: id },
    success: function (result) {
      console.log(result)
      if (result.status === 1) {
        itoastr('success', 'Domain is connected to server. Successfully verified.')
        loadCustom()
      } else {
        dispErrors(result.data)
      }
    },
    error: function (e) {
      console.log(e)
    }
  })
})

$('input[type=radio][name=domain_type]').change(function () {
  var area = $(this).prop('checked', true).data('area')
  console.log(area)
  $('.domain_area').addClass('d-none')
  $('.' + area).removeClass('d-none')
})
$('#credentials').change(function () {
  if ($(this).prop('checked') === true) {
    $('.custom_credential').addClass('d-none')
  } else {
    $('.custom_credential').removeClass('d-none')
  }
})
