<template>
  <div id="bz-image-cropper" class="bz-cropper-root d-flex flex-column">
    <cropper :src="cropSrc" :min-aspect-ratio="0.1" :max-aspect-ratio="10" @change="handleCropChange" />
    <div class="button-container">
      <button class="btn bz-btn-default-outline d-flex align-items-center mr-2" @click="removeCropper">Close</button>
      <button class="btn bz-btn-default d-flex align-items-center ml-3" @click="handleCrop">Save</button>
    </div>
  </div>
</template>

<script>
// TODO: replace
import { Cropper } from 'vue-advanced-cropper'
export default {
  name: 'BzCropper',
  components: { Cropper },
  props: {
    cropSrc: {
      type: String,
      required: true
    },
    minAspectRatio: {
      type: Number,
      default: 0.1
    },
    maxAspectRatio: {
      type: Number,
      default: 10
    }
  },
  data() {
    return {
      croppedImage: null
    }
  },
  methods: {
    handleCropChange({ coordinates, canvas }) {
      if (canvas) {
        this.croppedImage = canvas.toDataURL()
      }
    },
    handleCrop() {
      this.removeCropper()
      this.$emit('crop', this.croppedImage)
    },
    removeCropper() {
      this.$el.remove()
    }
  }
}
</script>
<style lang="scss">
.bz-cropper-root {
  position: fixed;
  left: 0;
  top: 0;
  width: 100vw;
  height: 100vh;
  display: flex;
  justify-content: center;
  align-items: center;
  z-index: 999999;
  background-color: #808080;

  .button-container {
    position: absolute;
    bottom: 10%;
    display: flex;
    justify-content: center;
    align-items: center;
    width: 100%;
  }
}
</style>
