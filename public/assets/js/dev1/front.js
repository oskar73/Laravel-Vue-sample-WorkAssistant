$('.filter-widget .category-menu li').hover(function (e) {
  $(this).addClass('active')
  e.preventDefault()
})

$('.filter-widget .category-menu li').mouseleave(function (e) {
  $(this).removeClass('active')
  e.preventDefault()
})
var popupSize = {
  width: 780,
  height: 550
}

$(document).on('click', '.social-button', function (e) {
  var verticalPos = Math.floor(($(window).width() - popupSize.width) / 2),
    horisontalPos = Math.floor(($(window).height() - popupSize.height) / 2)

  var popup = window.open(
    $(this).prop('href'),
    'social',
    'width=' +
    popupSize.width +
    ',height=' +
    popupSize.height +
    ',left=' +
    verticalPos +
    ',top=' +
    horisontalPos +
    ',location=0,menubar=0,toolbar=0,status=0,scrollbars=1,resizable=1'
  )

  if (popup) {
    popup.focus()
    e.preventDefault()
  }
})
$(document).on('click', '.navbar-nav li a', function () {
  $('.navbar-nav li a').removeClass('top-menu-active')
  $(this).addClass('top-menu-active')
})

$(document).on('click', '.review_result .pagination a.page-link', function (e) {
  e.preventDefault()
  var pageUrl = $(this).attr('href')
  getReviews(pageUrl)
})

//auth area
$(document).on('submit', '.authentication_form', function () {
  btnLoading('.signupBtn')
})

function countTime () {
  var count = 0
  count++
  if (count === 10) {
    window.location.reload()
  }
}

$(document).on('submit', '#ns_subscribe_form', function (event) {
  $('.ns_smt_btn').html('<i class=\'fa fa-spin fa-spinner\'></i>').prop('disabled', true)
  // $.ajax({
  //     url:"/subscribe",
  //     method:"POST",
  //     data:new FormData(this),
  //     dataType:'JSON',
  //     contentType:false,
  //     cache:false,
  //     processData:false,
  //     success:function(result)
  //     {
  //         $(".ns_smt_btn").html("<i class='fas fa-paper-plane'></i>").prop("disabled", false);
  //         if(result.status===0)
  //         {
  //             dispErrors(result.data);
  //             dispValidErrors(result.data);
  //         }else {
  //             itoastr("success", "Almost done... Please confirm in your mail inbox or spam folder.");
  //         }
  //     }
  // })
})

$(document).on('click', '.h_rm_btn', function (e) {
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
        $('.header_cart_area .cart_fa').click()
      }
    },
    error: function (e) {
      console.log(e)
    }
  })
})

$('#review_form').on('submit', function (e) {
  e.preventDefault()
  $('.smtBtn').html('<i class=\'fa fa-spinner fa-spin fa-fw\'></i>').attr('disabled', true)
  $.post(this.action, new FormData(this), (res) => {
    $('.form-control-feedback').html('')
    itoastr('success', 'Success!')
    getReviews(pageUrl)
  }, (err) => {
    Object.values(err).forEach((e) => {
      itoastr('error', e.toString())
    })
  }).then(() => {
    $('.smtBtn').html('Leave Review').attr('disabled', false)
  })
})

function getReviews (url) {
  $.ajax({
    url: url,
    type: 'get',
    data: model_data,
    success: function (result) {
      console.log(result)
      if (result.status === 1) {
        $('.review_result').html(result.data)
        $('.review_count').html(result.count)
        var rating = $('#review_rating')
        if (result.avgRating < 1 || result.avgRating == null) {
          rating.html('No review yet.')
        } else {
          rating.rateYo().rateYo('destroy')

          rating
            .rateYo({
              rating: parseFloat(result.avgRating).toFixed(2),
              readOnly: true,
              starWidth: '20px',
              ratedFill: '#86bc42'
            })
            .attr('title', parseFloat(result.avgRating).toFixed(2))
        }
        $('.review_rating_item').each(function (index, item) {
          $(this)
            .rateYo({
              rating: parseFloat($(this).data('rating')).toFixed(2),
              readOnly: true,
              starWidth: '20px',
              ratedFill: '#86bc42'
            })
            .attr('title', parseFloat($(this).data('rating')).toFixed(2))
        })
      }
    },
    error: function (e) {
      console.log(e)
    }
  })
}

$(document).on('keyup', '#blog_search_input', function () {
  var $keyword = $(this).val()
  ajaxSearch('/blog/search', $keyword)
})

$(document).on('click', '.search_result_area .pagination a', function (e) {
  e.preventDefault()
  var $keyword = $('#blog_search_input').val()
  ajaxSearch($(this).attr('href'), $keyword)
})

function ajaxSearch ($url, $keyword) {
  $.ajax({
    url: $url,
    method: 'GET',
    data: { keyword: $keyword },
    success: function (result) {
      console.log(result)
      if (result.status === 1) {
        $('.blog_search_remove_section').remove()
        $('.blog_append_section').html(result.data)
      }
    },
    error: function (err) {
      console.log('Error!', err)
    }
  })
}

function getBlogAds ($type, $id = null) {
  $.ajax({
    url: '/blogAds/getData',
    method: 'POST',
    data: {
      _token: token,
      type: $type,
      id: $id
    },
    success: function (result) {
      console.log(result)
      if (result.status === 1) {
        $.each(result.data, function (index, item) {
          if (item !== null) {
            $('.blog-ads-position-111' + item.position_id).html(item.frame)
          }
        })
      }
    },
    error: function (err) {
      console.log('Error!', err)
    }
  })
}

$(document).on('click', '.blogAds-click-funnel', function (e) {
  e.preventDefault()
  var id = $(this).data('id')
  window.open($(this).data('url'), '_blank')
  $.ajax({
    url: '/blogAds/impClick',
    method: 'POST',
    data: {
      _token: token,
      id: id
    },
    success: function (result) {
      console.log(result)
    }
  })
})

document.addEventListener('alpine:init', () => {
  window.Alpine.directive('outside-click', function (el, { expression }, { evaluateLater }) {
    const evaluate = evaluateLater(expression);
    document.body.addEventListener('click', function () {
      if (!el.contains(event.target)) {
        evaluate()
      }
    });
  })
})