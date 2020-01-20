<template>
  <div>
    <input ref="inputImageRef" type="file" accept="image/*" hidden @change="handleChange" />
    <div class="d-flex align-items-center mt-3">
      <div @click="handleClick">
        <label v-if="label" class="btn bz-btn-default d-flex align-items-center">
          <bz-spinner v-if="processing || loading" style="margin-right: 5px" />
          {{ label }}
        </label>
        <slot v-else />
      </div>
      <!--      <label v-if="crop && src" class="btn bz-btn-default ml-2" @click="handleCrop">Crop</label>-->
    </div>
    <div v-if="preview && modelValue" style="border: solid 1px #00000012; margin-top: 10px"
         :style="{ width: previewWidth, height: previewHeight }">
      <img :src="modelValue" alt="Image Selector" />
    </div>
  </div>
</template>

<script>
import axios from 'axios'
import { mapMutations } from 'vuex'
import BzSpinner from '../page/BzSpinner.vue'
import { uploadMediaToS3 } from '../../apis'
import { toast } from 'vue3-toastify'
import 'vue3-toastify/dist/index.css'

export default {
  name: 'ImageSelector',
  components: { BzSpinner },
  props: {
    modelValue: {
      type: [String, null],
      default: null
    },
    label: {
      type: String,
      default: 'Upload Image'
    },
    previewWidth: {
      type: String,
      default: '100%'
    },
    previewHeight: {
      type: String,
      default: '100%'
    },
    preview: {
      type: Boolean,
      default: true
    },
    uploadUrl: {
      type: String,
      default: undefined
    },
    crop: {
      type: Boolean,
      default: true
    },
    aspectRatio: {
      type: [Number, Boolean],
      default: false
    },
    fromModal: {
      type: Boolean,
      default: false
    },
    processing: {
      type: Boolean,
      default: false
    },
    encodeBase64: {
      type: Boolean,
      default: true
    },
    syncS3: {
      type: Boolean,
      default: true
    }
  },
  data() {
    return {
      file: null,
      base64: null,
      src: null,
      loading: false,
      cropSrc: null,
      cropShow: false,
      croppedImage: null,
      originalSrc: null,
      requireBase64: false
    }
  },
  methods: {
    updateSrc(src) {
      this.src = src
      this.$emit('update:modelValue', src)
      this.$emit('change', src)
    },
    handleClick() {
      if (this.fromModal) {
        const self = this
        this.openModal({
          name: 'selectImage',
          onChange: ({ url }) => {
            self.updateSrc(url)
            this.$store.commit('closeModal')
          }
        })
      } else {
        this.$refs.inputImageRef.click()
      }
    },
    async handleCrop() {
      if (!this.src.includes('base64')) {
        this.originalSrc = this.src
      }
      if (this.originalSrc) {
        if (this.src.includes('blob:')) {
          this.cropSrc = this.originalSrc
        } else {
          this.cropSrc = this.originalSrc + '?v=' + new Date().getTime()
        }
      } else {
        this.cropSrc = this.src
      }
      // this.showCropper()
    },
    showCropper() {
      // let imageCropper = document.getElementById('bz-image-cropper')
      // if (imageCropper === null) {
      //   imageCropper = document.createElement('div')
      //   imageCropper.setAttribute('id', 'bz-image-cropper-container')
      //   document.body.appendChild(imageCropper)
      //   const self = this
      //   const cropper = createApp(BzCropper, {
      //     cropSrc: self.cropSrc
      //   })
      //   cropper.mount('#bz-image-cropper-container')
      //   cropper.$on('crop', function (cropped) {
      //     self.updateSrc(cropped)
      //     this.$emit('change', cropped)
      //   })
      // }
    },
    async handleChange(e) {
      const input = e.target
      if (!input.files?.length) {
        return false
      }

      this.loading = true
      this.file = input.files[0]
      const maxFileSize = 1024 * 1024 // 1 MB (in bytes)

      if (this.file) {
        if (this.file.size > maxFileSize) {
          toast.error('File size exceeds the limit (1MB). Please select a smaller file.')
          this.file = null
          this.loading = false

          return false
        }

        if (this.syncS3) {
          const res = await uploadMediaToS3(this.file)
          if (res.data.success) {
            this.updateSrc(res.data.url)
          }
        } else {
          const src = URL.createObjectURL(this.file)
          this.updateSrc(src)
        }
        // if (this.crop) {
        //   this.cropSrc = src
        //   this.showCropper()
        // } else {
        //   this.updateSrc(src)
        // }
      }
      this.loading = false
    },
    upload() {
      const self = this
      this.loading = true
      return axios
        .post(this.uploadUrl, { image: this.base64 })
        .then((res) => {
          if (res.status === 200) {
            window.LOG.success('ImageSelector', 'Image Upload Success')
            self.loading = false
            return true
          }
        })
        .catch((err) => {
          window.LOG.error('Image Upload Failed', err)
          return false
        })
    },
    ...mapMutations(['openModal'])
  }
}
</script>

<style lang="scss" scoped>
label {
  width: max-content;
}

img {
  width: 100%;
}
</style>
