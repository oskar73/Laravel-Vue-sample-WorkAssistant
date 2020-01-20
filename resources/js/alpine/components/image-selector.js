import { createApp } from 'vue'
import ImageSelectModal from '@/section-builder/components/modals/ImageSelectModal.vue'

export const ImageSelector = {
  async selectImage($event) {
    function appendImagePickerRoot() {
      let pickerRoot = document.getElementById('image-picker')
      if (pickerRoot === null) {
        pickerRoot = document.createElement('div')
        pickerRoot.setAttribute('id', 'image-picker')
        document.body.appendChild(pickerRoot)
      }
    }
    appendImagePickerRoot()
    const imagePicker = createApp(ImageSelectModal, {
      open: true,
      confirmAction: (image) => {
        if (typeof this.handleImagePick === 'function') {
          this.handleImagePick(image, $event)
          imagePicker.unmount()
        } else {
          throw 'handleImagePick is not a function'
        }
      }
    })
    imagePicker.mixin({
      methods: {
        customClose() {
          console.log('uhmmm')
          imagePicker.unmount()
        }
      }
    })
    imagePicker.mount('#image-picker')
  }
}

export default () => ImageSelector
