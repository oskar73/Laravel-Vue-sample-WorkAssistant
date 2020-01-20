import { createApp } from 'vue'
import ImageSelectModal from '@/section-builder/components/modals/ImageSelectModal.vue'

class ImageSelector {
  instance = null

  constructor(options = {}) {
    this.options = options

    this.element = document.getElementById('bz-gallery-image-selector')

    if (!this.element) {
      this.element = document.createElement('div')
      this.element.setAttribute('id', 'bz-gallery-image-selector')
      document.body.appendChild(this.element)
    }

    this.element.innerHTML = ''

    this.mount()
  }

  mount() {
    this.instance = createApp(ImageSelectModal, {
      open: true,
      confirmAction: (image) => {
        if (typeof this.options.onSelect === 'function') this.options.onSelect(image)
        this.close()
      },
      customClose: () => {
        this.close()
        if (typeof this.options.onClose === 'function') this.options.onClose()
      }
    })
    this.instance.mount(this.element)
  }

  close() {
    this.instance.unmount()
    this.instance = null
    this.element.innerHTML = ''
  }
}

;(function () {
  window.ImageSelector = ImageSelector
})()
