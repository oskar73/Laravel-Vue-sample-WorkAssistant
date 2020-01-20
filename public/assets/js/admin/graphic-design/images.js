document.addEventListener('alpine:init', () => {
    window.Alpine.data('createImageData', () => ({
        loading: false,
        removing_id: null,
        categories: categories,
        category_slug: category.slug,
        images: images,
        init () {
            this.$nextTick(() => {
                $('#category').selectpicker()
                  .on('change', function () {
                      window.location.href = route('admin.graphics.images.view', { slug: this.value })
                  })
            })
        },
        addImage  (e) {
            const file = e.target.files[0]
            const data = new FormData()
            data.append('slug', this.category_slug)
            data.append('file', file)
            this.loading = true
            $.post(route('admin.graphics.images.add'), data, (res) => {
                this.images.push(res.data.image)
                this.loading = false
            })
        },
        removeImage (image) {
            const data = new FormData()
            askToast.question('Confirm', 'Do you want to delete this image?', () => {
                data.append('media_id', image.id)
                data.append('_method', 'DELETE')
                this.removing_id = image.id
                $.post(route('admin.graphics.media.delete'), data, () => {
                    this.images = this.images.filter(_m => _m.id != image.id)
                    this.removing_id = null
                    window.itoastr('success', 'Successfully removed!')
                })
            })
        }
    }))
})