$('.auth_button').click(function (e) {
  e.preventDefault()
  $('.auth_button').append('<i class="fa fa-spinner fa-pulse mt-1 float-right"></i>').attr('disabled', true)
  setTimeout(function () {
    $('#submit_form').submit()
  }, 1000)

  setInterval(function () {
    countTime()
  }, 1000)
})
function countTime() {
  var count = 0
  count++
  if (count === 10) {
    window.location.reload()
  }
}
