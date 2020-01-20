let previewCropper
document.addEventListener('alpine:init', () => {
  window.Alpine.data('createDesignData', () => ({
    categories: categories,
    graphics: graphics,
    groups: groups,
    graphic_id: null,
    graphicCategories: [],
    init() {
      this.graphic_id = this.graphics[0].id
      this.graphicCategories = this.categories.filter(item => item.graphic_id == this.graphic_id)
      this.$nextTick(() => {
        $('#graphic_id').selectpicker().on('change', () => {
          this.graphicCategories = this.categories.filter(item => item.graphic_id == this.graphic_id)
          $('#category_ids').select2({
            width: '100%',
            placeholder: 'Select Categories'
          })
        })
        $('#category_ids').select2({
          width: '100%',
          placeholder: 'Select Categories',
          data: () => {
            return {
              results: this.graphicCategories
            }
          }
        })
      })
    }
  }))
})
$(function() {
  let previewImage = $('#preview_image')
  let previewImageUrl = previewImage.data('url')
  previewCropper = new Slim(previewImage[0], {
    ratio: '2:1',
    download: true,
    buttonRemoveTitle: 'upload',
    instantEdit: false,
    maxFileSize: 50,
    label: 'Drop or choose image'
  })
  if (previewImageUrl) {
    previewCropper.load(previewImageUrl + '?' + new Date().getTime())
  }
})

$('#submit_form').submit(function(event) {
  event.preventDefault()
  btnLoading()
  $.post(this.action, new FormData(this)).then((res) => {
    btnLoadingStop()
    if (res.status) {
      itoastr('success', 'Success!')
      redirectAfterDelay('/admin/graphics/design')
    }
  })
})
