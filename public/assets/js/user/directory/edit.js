var i = 0
var j = 0
var k = 0

$(document).ready(function () {
  $('.selectpicker').selectpicker()
  addTinymce()
  $('#category').select2({
    width: '100%',
    placeholder: 'Choose Category',
    minimumResultsForSearch: -1
  })
  $('#tag').select2({
    width: '100%',
    placeholder: 'Choose Tags',
    minimumInputLength: 1
  })

  $('#expire_date').datepicker({
    format: 'yyyy-mm-dd',
    startDate: '0d',
    todayHighlight: !0,
    autoclose: !0
  })
  $('#customer').select2(ajaxSelect2(`/admin/selectUser?user=user`, 'Search user by name or email', 'id', 'nameEmail'))
})

$('#category').change(function (event) {
  var $tags = $(this).find(':selected').attr('data-tags')
  $('#tag').val(JSON.parse($tags)).trigger('change.select2')
})

function addTinymce() {
  tinymce.init({
    selector: '#description', // change this value according to the HTML
    inline: false,
    placeholder: 'Description',
    plugins: 'link autolink emoticons wordcount paste autoresize lists',
    toolbar: 'bold link unlink emoticons blockquote | styleselect  fontselect fontsizeselect forecolor backcolor | alignleft aligncenter alignright bullist numlist outdent indent',
    menubar: false,
    statusbar: false
  })
}
$(document).on('click', '#addImage', function () {
  $('#image_area').append(
    '<tr><td><input type="file" accept="image/*" name=\'images[]\' class="form-control m-input--square uploadImageBox" data-target=\'image-' +
      i +
      "'></td><td><img id='image-" +
      i +
      "' class='w-150px' /></td><td><button class='btn btn-danger btn-sm delBtn'>X</button></td></tr>"
  )
  i++
})
$(document).on('click', '#addVideo', function () {
  $('#video_area').append(
    '<tr><td><input type="file" accept="video/*" name=\'videos[]\' class="form-control m-input--square"></td><td><button class=\'btn btn-danger btn-sm delBtn\'>X</button></td></tr>'
  )
  j++
})
$(document).on('click', '#addLink', function () {
  $('#link_area').append(
    '<tr><td><input type="url" name=\'links[]\' class="form-control m-input--square"></td><td><button class=\'btn btn-danger btn-sm delBtn\'>X</button></td></tr>'
  )
  k++
})
$(document).on('click', '.delBtn', function () {
  $(this).parent().parent().remove()
})

$('#submit_form').on('submit', function (event) {
  event.preventDefault()
  tinyMCE.triggerSave()
  var formData = new FormData(this)

  btnLoading()
  $.ajax({
    url: $(this).attr('action'),
    method: 'POST',
    data: formData,
    dataType: 'JSON',
    contentType: false,
    cache: false,
    processData: false,
    success: function (result) {
      console.log(result)
      btnLoadingStop()
      clearError()

      if (result.status === 0) {
        dispValidErrors(result.data)
        dispErrors(result.data)
      } else {
        itoastr('success', 'Success!')

        redirectAfterDelay('/account/directory')
      }
    },
    error: function (e) {
      console.log(e)
    }
  })
})
