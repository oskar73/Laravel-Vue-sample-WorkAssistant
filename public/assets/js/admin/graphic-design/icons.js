document.addEventListener('alpine:init', () => {
    window.Alpine.data('createIconData', () => ({
        loading: false,
        removing_id: null,
        categories: categories,
        category_slug: category.slug,
        icons: icons,
        init () {
            this.$nextTick(() => {
                $('#category').selectpicker()
                  .on('change', function () {
                      window.location.href = route('admin.graphics.icons.view', { slug: this.value })
                  })
            })
        },
        addIcon  (e) {
            const file = e.target.files[0]
            const data = new FormData()
            data.append('slug', this.category_slug)
            data.append('file', file)
            this.loading = true
            $.post(route('admin.graphics.icons.add'), data, (res) => {
                this.icons.push(res.data.icon)
                this.loading = false
            })
        },
        removeIcon (icon) {
            const data = new FormData()
            askToast.question('Confirm', 'Do you want to delete this icon?', () => {
                data.append('media_id', icon.id)
                data.append('_method', 'DELETE')
                this.removing_id = icon.id
                $.post(route('admin.graphics.media.delete'), data, () => {
                    this.icons = this.icons.filter(_m => _m.id != icon.id)
                    this.removing_id = null
                    window.itoastr('success', 'Successfully removed!')
                })
            })
        }
    }))
})