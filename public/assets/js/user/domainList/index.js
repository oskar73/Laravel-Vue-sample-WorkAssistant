var switch_action
var checkbox_count
var alone = 0
var selected
var disconnect

$(function () {
  hashUpdate(window.location.hash)
  getUserDomainTable()
  getUserConnectDomainTable()
})

function getUserDomainTable() {
  $.ajax({
    url: '/account/domainList',
    type: 'get',
    data: { type: 'purchased' },
    success: function (result) {
      console.log(result)
      if (result.status === 1) {
        $('.domain_result').html(result.data)
        $('.purchased_count').html(result.count)
        $('.my_domains').dataTable(dataTblSet())
      }
    },
    error: function (e) {
      console.log(e)
    }
  })
}
function getUserConnectDomainTable() {
  $.ajax({
    url: '/account/domainList',
    type: 'get',
    data: { type: 'connected' },
    success: function (result) {
      console.log(result)
      if (result.status === 1) {
        $('.connected_result').html(result.data)
        $('.connected_count').html(result.count)
        $('.connected_domains').dataTable(dataTblSet())
      }
    },
    error: function (e) {
      console.log(e)
    }
  })
}
$(document).on('click', '.disconnect', function () {
  disconnect = $(this).data('id')
  askToast.question('Warning!', 'Are you sure to disconnect this domain?', 'disconnectDomain')
})
function disconnectDomain() {
  console.log(disconnect)
  $.ajax({
    url: '/account/domain/disconnect',
    type: 'get',
    data: { id: disconnect },
    success: function (result) {
      if (result.status === 1) {
        itoastr('success', 'Successfully disconnected!')
        getUserConnectDomainTable()
      } else {
        dispErrors(result.data)
      }
    },
    error: function (e) {
      console.log(e)
    }
  })
}
$(document).on('click', '.renew_btn', function () {
  $.ajax({
    url: '/account/domainList/renew/' + $(this).data('id'),
    success: function (result) {
      if (result.status === 0) {
        dispErrors(result.data)
      } else {
        $('.renew_result').html(result.data)
      }
    }
  })
})
$(document).on('click', '.renew_confirm_btn', function () {
  $.ajax({
    url: '/account/domainList/renewConfirm',
    data: { duration: $(this).data('duration') },
    success: function (result) {
      if (result.status === 0) {
        dispErrors(result.data)
      } else {
        $('.renew_confirm_result').html(result.data)
      }
    }
  })
})
$(document).on('click', '.renewNowBtn', function () {
  var dns
  $('.renewNowBtn').html("<i class='fa fa-spinner fa-spin fa-2x fa-fw'></i>").attr('disabled', true)
  $.ajax({
    url: '/account/domainList/renewNow',
    type: 'post',
    data: { _token: token },
    success: function (result) {
      console.log(result)
      $('.renewNowBtn').html('Renew Now').attr('disabled', false)
      if (result.status === 0) {
        dispErrors(result.data)
      } else {
        itoastr('success', 'Successfully renewed')
        setTimeout(function () {
          window.location.href = '/account/domainList'
        }, 1000)
      }
    },
    error: function (e) {
      console.log(e)
    }
  })
})
