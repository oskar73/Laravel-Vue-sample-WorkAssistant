document.addEventListener('alpine:init', () => {
  window.Alpine.data('createMaskData', () => ({
    loading: false,
    loadingText: false,
    removing_id: null,
    categories: graphics,
    category_slug: graphic.slug,
    masks: masks,
    init () {
      this.$nextTick(() => {
        $('#category').selectpicker()
          .on('change', function () {
            window.location.href = route('admin.graphics.masks.view', { slug: this.value })
          })
      })
    },
    addMask (e) {
      const file = e.target.files[0]
      const data = new FormData()
      data.append('slug', this.category_slug)
      data.append('file', file)
      this.loading = true
      $.post(route('admin.graphics.masks.add'), data, (res) => {
        this.masks.push(res.data.mask)
        if(res.data.message){
            window.itoastr('info', res.data.message)
        }
        this.loading = false
      })
    },
    addFileToFormData(formData, fileContentString, fileName) {
      const blob = new Blob([fileContentString], { type: 'application/octet-stream' });
      const file = new File([blob], fileName, { type: 'application/octet-stream' });
      formData.append('file', file, fileName);
    },
    addMaskFromContent () {
      if(!this.loadingText){
        const fileContentString = $('#content').val()
        const data = new FormData()
        data.append('slug', this.category_slug)
        this.addFileToFormData(data, fileContentString, 'file.svg');
        this.loadingText = true
        $.post(route('admin.graphics.masks.add'), data, (res) => {
            this.masks.push(res.data.mask)
            if(res.data.message){
             window.itoastr('info', res.data.message)
            }
            $('#content').val('')
            this.loadingText = false
        })
      }
    },
    removeMask (mask) {
      const data = new FormData()
      askToast.question('Confirm', 'Do you want to delete this mask?', () => {
        data.append('media_id', mask.id)
        data.append('_method', 'DELETE')
        this.removing_id = mask.uuid
        $.post(route('admin.graphics.media.delete'), data, (res) => {
          this.masks = this.masks.filter(_m => _m.id != mask.id)
          this.removing_id = null
          window.itoastr('success', 'Successfully removed!')
        })
      })
    }
  }))
})
