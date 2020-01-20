var pageUrl = '/review' + '?page=1'
var model_data = { type: type, model_id: model_id }
$(document).ready(function () {
  $('.lightgallery').lightGallery()
  getReviews(pageUrl)
  $('#rating').rateYo({
    rating: 0,
    ratedFill: '#86bc42',
    fullStar: true,
    onChange: function (rating, rateYoInstance) {
      $(this).next().val(rating)
    }
  })
})
