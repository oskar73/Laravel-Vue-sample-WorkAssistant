var switchAction
var status

$(function () {
  hashUpdate(window.location.hash)

  $.ajax({
    url: '/admin/domainList/getDetail/' + domain_id,
    success: function (result) {
      console.log(result)
      if (result.error) {
        dispErrors(result.message)
      } else {
        $('.created').html(result.data.Created)
        $('.expires').html(result.data.Expires)
        $('.load-1').remove()
        if (result.data.WhoisGuard == 'ENABLED') {
          $('#whoisguard').prop('checked', true)
        } else {
          $('#whoisguard').prop('checked', false)
        }
        $('#whoisguard').after('<span></span>')
      }
    },
    error: function (e) {
      console.log(e)
    }
  })
  $.ajax({
    url: '/admin/domainList/getContact/' + domain_id,
    success: function (result) {
      console.log(result)
      if (result.error) {
        dispErrors(result.message)
      } else {
        $('#address1').val(result.data.Address1)
        if (typeof result.data.Address2 == 'string') {
          $('#address2').val(result.data.Address2)
        }

        $('#firstName').val(result.data.FirstName)
        $('#lastName').val(result.data.LastName)
        $('#city').val(result.data.City)
        $('#email').val(result.data.EmailAddress)
        $('#country').val(result.data.Country)
        $('#postalCode').val(result.data.PostalCode)
        $('#state').val(result.data.StateProvince)
        $('#phoneCode').val(getPhoneCode(result.data.Phone))
        $('#phoneNumber').val(getPhoneNum(result.data.Phone))

        $('.selectpicker').selectpicker('refresh')
      }
    },
    error: function (e) {
      console.log(e)
    }
  })
  $.ajax({
    url: '/admin/domainList/getHosts/' + domain_id,
    success: function (result) {
      console.log(result)
      if (result.error) {
        $.each(result.message, function (index, value) {
          $('.dns_error').append(
            '<div class="alert alert-danger alert-dismissible fade show   m-alert m-alert--air m-alert--outline" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"></button><strong>Error!</strong>' +
              value +
              '</div>'
          )
        })
      } else {
        $('.load-4').remove()
        $.each(result.data, function (index, item) {
          addRecord(item)
        })
      }
    },
    error: function (e) {
      console.log(e)
    }
  })
  $.ajax({
    url: '/admin/domainList/getLocked/' + domain_id,
    success: function (result) {
      console.log(result)
      if (result.status == 0) {
        dispErrors(result.data)
      } else {
        $('.load-2').remove()
        if (result.data['RegistrarLockStatus'] == 'true') {
          $('#isLocked').prop('checked', true)
        } else {
          $('#isLocked').prop('checked', false)
        }
        $('#isLocked').after('<span></span>')
      }
    },
    error: function (e) {
      console.log(e)
    }
  })
  $.ajax({
    url: '/admin/domainList/getDns/' + domain_id,
    success: function (result) {
      console.log(result)
      if (result.status == 0) {
        dispErrors(result.data)
      } else {
        $('.load-5').remove()
        $.each(result.result, function (index, item) {
          let number = Number(index) + 1
          console.log(number)
          addNsRecord(number, item)
        })
      }
    },
    error: function (e) {
      console.log(e)
    }
  })
})
function addRecord(item) {
  if (item['Type'] === 'MX' || item['Type'] === 'MXE') {
    var address = item['MXPref'] + ' ' + item['Address']
  } else {
    var address = item['Address']
  }
  $('.dns_tbody').append(
    "<tr><td class='type'>" +
      selectType(item['Type']) +
      "</td><td class='host' contenteditable='true'>" +
      item['Name'] +
      "</td><td class='value maxw-300' contenteditable='true'>" +
      address +
      "</td><td class='ttl' contenteditable='true'>" +
      item['TTL'] +
      "</td><td><a href='javascript:void(0);' class='btn btn-danger p-2 removeBtn' >Remove</a></td></tr>"
  )
}
function addNsRecord(index, item) {
  $('.ns_tbody').append("<tr><td class='nameserver'> Nameserver" + index + "</td><td class='nameserver_value' contenteditable='true'>" + item + '</td></tr>')
}
$('.nsaddBtn').click(function () {
  let index = $('.nameserver').length + 1
  if (index <= 12) {
    addNsRecord(index, '')
  }
})
$('.nsresetBtn').click(function () {
  let msg = 'Do you want to set default nameservers?'
  askToast.question('Confirm', msg, 'setDefaultNS')
})
function setDefaultNS() {
  $.ajax({
    url: '/admin/domainList/getDns/' + domain_id,
    data: { _token: token },
    type: 'put',
    success: function (result) {
      console.log(result)
      if (result.status == 0) {
        dispErrors(result.data)
      } else {
        itoastr('success', 'Successfully updated!')

        setTimeout(function () {
          window.location.reload()
        }, 1000)
      }
    },
    error: function (e) {
      console.log(e)
    }
  })
}
$(document).on('click', '.nsupdateBtn', function (e) {
  var values = []
  $(this).html("<i class='fa fa-spinner fa-spin fa-2x fa-fw'></i>").prop('disabled', true)
  var index = 1
  $('td.nameserver_value').each(function () {
    values[index] = $(this).text()
    index++
  })
  console.log(values)
  $.ajax({
    url: '/admin/domainList/getDns/' + domain_id,
    data: { _token: token, nameserver: values },
    type: 'post',
    success: function (result) {
      $('.nsupdateBtn').html('Update').prop('disabled', false)
      if (result.status == 0) {
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

$('.addBtn').click(function () {
  let item = []
  item['Type'] = ''
  item['Name'] = ''
  item['Address'] = ''
  item['TTL'] = '300'
  addRecord(item)
})
$(document).on('click', '.removeBtn', function () {
  $(this).parent().parent().remove()
})

var type = [
  { value: 'A', name: 'A Record' },
  { value: 'AAAA', name: 'AAAA Record' },
  { value: 'ALIAS', name: 'ALIAS Record' },
  { value: 'CAA', name: 'CAA Record' },
  { value: 'CNAME', name: 'CNAME Record' },
  { value: 'MX', name: 'MX Record' },
  { value: 'MXE', name: 'MXE Record' },
  { value: 'NS', name: 'NS Record' },
  { value: 'TXT', name: 'TXT Record' },
  { value: 'URL', name: 'URL Record' },
  { value: 'URL301', name: 'URL Redirect Record' },
  { value: 'FRAME', name: 'FRAME Record' }
]
function selectType(val) {
  var select = "<select class='type form-control'><option selected disabled hidden>Choose Type</option>"

  $.each(type, function (index, item) {
    var selected = val == item.value ? 'selected' : ''
    select += "<option value='" + item.value + "' " + selected + '>' + item.name + '</option>'
  })
  select += '</select>'
  return select
}
$(document).on('click', '.status_checkbox', function (e) {
  e.preventDefault()
  switchAction = $(this).attr('id')
  status = $(this).prop('checked')
  let msg
  if (switchAction === 'isLocked') {
    msg = 'Do you want to switch domain locked status?'
  } else {
    msg = 'Do you want to switch WhoisGuard status?'
  }
  askToast.question('Confirm', msg, 'switchLockedAndWhoisGuard')
})

$('#contact-form').on('submit', function (e) {
  e.preventDefault()
  $('.smtBtn').html("<i class='fa fa-spinner fa-spin fa-2x fa-fw'></i>").attr('disabled', true)

  $.ajax({
    url: '/admin/domainList/getContact/' + domain_id,
    method: 'post',
    data: new FormData(this),
    dataType: 'JSON',
    contentType: false,
    cache: false,
    processData: false,
    success: function (result) {
      console.log(result)
      $('.smtBtn').html('Update Contact').attr('disabled', false)
      if (result.status == 0) {
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
$(document).on('click', '.updateBtn', function (e) {
  var types = []
  var hosts = []
  var values = []
  var ttls = []
  $(this).html("<i class='fa fa-spinner fa-spin fa-2x fa-fw'></i>").prop('disabled', true)
  $('select.type').each(function (index, item) {
    types.push($(this).val())
  })
  $('td.host').each(function (index, item) {
    hosts.push($(this).text())
  })
  $('td.value').each(function (index, item) {
    values.push($(this).text())
  })
  $('td.ttl').each(function (index, item) {
    ttls.push($(this).text())
  })
  $.ajax({
    url: '/admin/domainList/setHosts/' + domain_id,
    data: { _token: token, types: types, hosts: hosts, values: values, ttls: ttls },
    type: 'post',
    success: function (result) {
      $('.updateBtn').html('Update').prop('disabled', false)
      if (result.status == 0) {
        dispErrors(result.message)
      } else {
        itoastr('success', 'Successfully updated!')
      }
    },
    error: function (e) {
      console.log(e)
    }
  })
})
function switchLockedAndWhoisGuard() {
  $.ajax({
    url: '/admin/domainList/switchAction/' + domain_id,
    data: { action: switchAction, status: status },
    success: function (result) {
      if (result.status == 0) {
        dispErrors(result.message)
      } else {
        itoastr('success', 'Successfully updated!')
        if (result.data['RegistrarLockStatus'] == 'true') {
          $('#' + switchAction).prop('checked', true)
        } else {
          $('#' + switchAction).prop('checked', false)
        }
      }
    },
    error: function (e) {
      console.log(e)
    }
  })
}
function getPhoneCode(phone) {
  array = phone.split('.')
  return array[0].replace('+', '')
}
function getPhoneNum(phone) {
  array = phone.split('.')
  return array[1]
}
