let hasAlreadyAccount = false

$(function () {
  getCartItems()
})

$('input[type=radio][name=payment_method]').change(function () {
  $('.pm_label').removeClass('active')
  $(this).parents('.pm_label').addClass('active')
  if (this.value === 'card') {
    $('.stripe_area').slideDown()
    $('#formAction').removeClass('d-none')
    $('#guest_email_form').addClass('d-none')
  } else {
    $('.stripe_area').slideUp()
    if (!isLoggedIn) {
      $('#formAction').addClass('d-none')
      $('#guest_email_form').removeClass('d-none')
    }
  }
})

$('#guest_email_form').submit(function (event) {
  event.preventDefault()
  if ($('#guest_email').val() === '') return itoastr('error', 'Please input email')

  return askToast.question(
    'Confirm',
    `Please make sure your email is accurate because your login details will be emailed to you. Is your email correct?`,
    'guestEmailHandler'
  )
})

function guestEmailHandler() {
  $('.confirmBtn').append(' <i class=\'fa fa-spinner fa-spin loading_div\'></i>').attr('disabled', true)

  var form = $('#guest_email_form')[0]
  $.post(form.action, new FormData(form), (data) => {

    if(!data.data.hasAccount) {
      itoastr('success', 'Success!')
    }

    $('.confirmBtn').prop('disabled', false)
    $('.loading_div').remove()
    $('#formAction').removeClass('d-none')
    $('.checkout_form').removeClass('d-none')
    $('#guest_email_form').remove()
  })
}

$(document).on('click', '.contact-btn', function (e) {
  $('.contact-div').trigger('click')
  setTimeout(() => {
    $('#chat_biz_area .fa-angle-up').trigger('click');
}, 1000)
})

$(document).on('click', '.c_rm_btn', function (e) {
  e.preventDefault()
  $.ajax({
    url: `/cart/remove`,
    data: { id: $(this).data('id') },
    success: function (result) {
      $('.loading_div').remove()
      if (result.status === 0) {
        dispErrors(result.data)
      } else {
        itoastr('success', 'Successfully removed!')
        $('.header_cart_area').html(result.data)

        getCartItems()
      }
    },
    error: function (e) {
      console.error(e)
    }
  })
})

$(document).on('click', '#emptyCrtBtn', function () {
  askToast.question('Do you want to clear all the cart items?', '', 'emptyCart')
})

function emptyCart () {
  $.ajax({
    url: `/cart/empty`,
    success: function (result) {
      $('.loading_div').remove()
      if (result.status === 0) {
        dispErrors(result.data)
      } else {
        itoastr('success', 'Successfully removed!')
        $('.header_cart_area').html(result.data)

        getCartItems()
      }
    },
    error: function (e) {
      console.log(e)
    }
  })
}

function getCartItems () {
  $.ajax({
    url: '/cart',
    dataType: 'JSON',
    contentType: false,
    cache: false,
    processData: false,
    success: function (result) {
      setTimeout(function () {
        $('.iziToast-close').click()
      }, 3000)
      if (result.status === 1) {
        $('.cart_item_area').html(result.data['table'])
        $('.c_onetotal_price').html(result.data['oneTotal'])
        $('.c_subtotal_price').html(result.data['subTotal'])
        $('.c_total_price').html(result.data['total'])
      }
    },
    error: function (e) {
      console.log(e)
    }
  })
}

$(document).on('click', '.apply_coupon', function (e) {
  e.preventDefault()
  var code = $('#coupon_code').val()
  if (code === '' || code === null) {
    itoastr('error', 'Coupon code is invalid.')
  } else {
    $.ajax({
      url: '/cart/coupon',
      data: { code: code },
      success: function (result) {
        console.log(result)
        if (result.status === 0) {
          dispErrors(result.data)
        } else {
          itoastr('success', 'Successfully applied!')
        }
      },
      error: function (e) {
        console.log(e)
      }
    })
  }
})

$('#redeem_coupon').on('click', () => {
    var code = $('#coupon').val()
    if (code === '' || code === null) {
      itoastr('error', 'Coupon code is invalid.')
    } else {
        window.location.href = window.location.origin + '/cart/coupon?code=' + code
    }
})

$('#has_coupon').on('change', (e) => {
    const hasCoupon = $(e.currentTarget).prop("checked")
    const couponArea = $('#coupon_area')
    if(hasCoupon){
        couponArea.removeClass('d-none')
    } else {
        couponArea.addClass('d-none')
    }
})

$('#pay_btn').click(function () {
  if ($('input[type=radio][name=payment_method]:checked').val() === 'card') {
    $('#stripe_smt_form').submit()
  } else {
    $('#paypal_submit_form').submit()
  }
})
