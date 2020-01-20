var domain_id
var domain_type

$(function () {
  hashUpdate(window.location.hash)
  getDomain()
  $('#modules').select2({
    placeholder: 'Choose Modules',
    width: '100%'
  })
})
$(document).on('click', '.setPrimary', function () {
  domain_id = $(this).data('id')
  domain_type = $(this).data('type')

  askToast.question('Confirm', 'Are you sure to change primary domain?', 'setPrimary')
})
$(document).on('click', '.connectDomain', function () {
  $('#domain_connect_modal').modal('toggle')
})

$(document).on('click', '.custom_domain_edit', function () {
  $('#custom_domain_edit_modal').modal('toggle')
  $('#subdomain').val($(this).data('domain'))
  $('#subdomain_id').val($(this).data('id'))
})

$(document).on('click', '#update_domain_btn', function () {
  var subdomain = $('#subdomain').val()
  var id = $('#subdomain_id').val()
  if (subdomain === '') {
    itoastr('error', 'Please input subdomain name.')
  } else {
    $(this).html("<i class='fa fa-spinner fa-spin fa-2x fa-fw'></i>").attr('disabled', true)

    $.ajax({
      url: '/admin/website/list/updateDomain/' + website_id,
      type: 'post',
      data: { _token: $('meta[name="csrf-token"]').attr('content'), subdomain: subdomain, id: id },
      success: function (result) {
        $('#update_domain_btn').html('Update').attr('disabled', false)
        if (result.status === 1) {
          itoastr('success', 'Successfully updated!')
          $('#custom_domain_edit_modal').modal('toggle')
          getDomain()
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

$('#basic_form').on('submit', function (e) {
  e.preventDefault()
  $('#basic_form .smtBtn').append(" <i class='fa fa-spin fa-spinner'></i>").prop('disabled', true)
  $.ajax({
    url: $(this).attr('action'),
    method: 'POST',
    data: new FormData(this),
    dataType: 'JSON',
    contentType: false,
    cache: false,
    processData: false,
    success: function (result) {
      $('#basic_form .smtBtn').html('Update').prop('disabled', false)
      if (result.status === 0) {
        dispErrors(result.data)
        dispValidErrors(result.data)
      } else {
        itoastr('success', 'Successfully Updated!')
      }
    },
    error: function (e) {
      console.log(e)
    }
  })
})

$('#module_form').on('submit', function (e) {
  e.preventDefault()
  $('#module_form .smtBtn').append(" <i class='fa fa-spin fa-spinner'></i>").prop('disabled', true)
  $.ajax({
    url: $(this).attr('action'),
    method: 'POST',
    data: new FormData(this),
    dataType: 'JSON',
    contentType: false,
    cache: false,
    processData: false,
    success: function (result) {
      $('#module_form .smtBtn').html('Update').prop('disabled', false)
      if (result.status === 0) {
        dispErrors(result.data)
        dispValidErrors(result.data)
      } else {
        itoastr('success', 'Successfully Updated!')
      }
    },
    error: function (e) {
      console.log(e)
    }
  })
})

$('#profileForm').on('submit', function (event) {
  event.preventDefault()
  $('.smtBtn').html("<i class='fa fa-spinner fa-spin fa-2x fa-fw'></i>").attr('disabled', true)
  $.ajax({
    url: '/admin/website/list/profileUpdate/' + website_id,
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
        itoastr('success', 'Successfully updated!')
      }
    },
    error: function (e) {
      console.log(e)
    }
  })
})
$('#connect_domain_btn').click(function () {
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
        $('#connect_domain_btn').html('Connect').attr('disabled', false)
        if (result.status === 1) {
          itoastr('success', 'Successfully connected!')
          $('#connect_domain').val('')
          $('#domain_connect_modal').modal('toggle')
          getDomain()
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
function getDomain() {
  $.ajax({
    url: '/admin/website/list/edit/' + website_id,
    success: function (result) {
      console.log(result)
      if (result.status === 1) {
        $('.domain_result').html(result.data)
      }
    },
    error: function (e) {
      console.log(e)
    }
  })
}
function setPrimary() {
  console.log(domain_id, domain_type)
  $.ajax({
    url: '/admin/website/list/setPrimary/' + website_id,
    data: { id: domain_id, domain_type: domain_type },
    success: function (result) {
      console.log(result)
      if (result.status === 0) {
        dispErrors(result.data)
      } else {
        itoastr('success', 'Successfully updated!')
        getDomain()
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
        getDomain()
      } else {
        dispErrors(result.data)
      }
    },
    error: function (e) {
      console.log(e)
    }
  })
})
