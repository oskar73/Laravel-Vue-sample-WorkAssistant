<template>
  <div class="image-editor w-100 d-flex">
    <div class="side-bar">
      <div class="side-menu">
        <hr />
        <div class="menu-item" :class="{ active: tab === 'tune' }" @click.prevent="tab = 'tune'">
          <filter-icon />
          <span class="pt-1">Tune</span>
        </div>
        <hr />
        <div class="menu-item" :class="{ active: tab === 'crop' }" @click.prevent="tab = 'crop'">
          <crop-icon />
          <span class="pt-1">Crop</span>
        </div>
        <hr />
      </div>
      <div class="side-panel" :class="{ active: tab === 'tune' }">
        <div class="d-flex align-items-center my-1" @click.prevent="turnFilter()">
          <b class="px-2">Tune</b>
          <turn-icon />
        </div>
        <div class="label">Saturation</div>
        <input v-model="saturation" type="range" :min="0" :max="2" :step="0.01" @input="handleFilterChange" />

        <div class="label">Brightness</div>
        <input v-model="brightness" type="range" :min="0" :max="2" :step="0.01" @input="handleFilterChange" />

        <div class="label">Contrast</div>
        <input v-model="contrast" type="range" :min="0" :max="2" :step="0.01" @input="handleFilterChange" />

        <div class="label">Hue</div>
        <input v-model="hue" type="range" :min="0" :max="6.28" :step="0.01" @input="handleFilterChange" />

        <div class="label">Intensity</div>
        <input v-model="intensity" type="range" :min="0" :max="1" :step="0.01" @input="handleFilterChange" />
      </div>
      <div class="side-panel" :class="{ active: tab === 'crop' }">
        <div class="d-flex align-items-center my-1">
          <b class="px-2">Sizing</b>
        </div>
        <div class="row mt-3">
          <div class="col-4">
            <div class="d-flex flex-column align-items-center crop-item" :class="{ active: sizing === 'original' }" @click.prevent="sizing = 'original'">
              <CropOriginalIcon />
              <span>Original</span>
            </div>
          </div>
          <div class="col-4">
            <div class="d-flex flex-column align-items-center crop-item" :class="{ active: sizing === 'square' }" @click.prevent="sizing = 'square'">
              <CropSquareIcon />
              <span>Square</span>
            </div>
          </div>
          <div class="col-4">
            <div class="d-flex flex-column align-items-center crop-item" :class="{ active: sizing === 'portrait' }" @click.prevent="sizing = 'portrait'">
              <CropPortraitIcon />
              <span>Portrait</span>
            </div>
          </div>
          <div class="col-4">
            <div class="d-flex flex-column align-items-center crop-item" :class="{ active: sizing === '32' }" @click.prevent="sizing = '32'">
              <Crop32Icon />
              <span>3:2</span>
            </div>
          </div>
          <div class="col-4">
            <div class="d-flex flex-column align-items-center crop-item" :class="{ active: sizing === '169' }" @click.prevent="sizing = '169'">
              <Crop169Icon />
              <span>16:9</span>
            </div>
          </div>
          <div class="col-4">
            <div class="d-flex flex-column align-items-center crop-item" :class="{ active: sizing === '75' }" @click.prevent="sizing = '75'">
              <Crop75Icon />
              <span>7:5</span>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div v-if="editingImage" class="editor-body" :class="{ [tab]: true }">
      <div v-if="tab === 'crop'" class="zoomArea">
        <ZoomOut :fill-color="'#555555'" />
        <input :value="zoom" type="range" :min="1" :max="3" step="any" @input="handleZoomInput" />
        <ZoomIn :fill-color="'#555555'" />
      </div>
      <Cropper
        ref="cropper"
        :src="editingImage.base64"
        :stencil-props="{
          movable: false,
          aspectRatio: aspectRatio
        }"
        class="cropper"
        :auto-zoom="true"
        :resize-image="{ adjustStencil: false }"
        image-restriction="stencil"
        :default-position="{ left: 0, top: 0 }"
        :stencil-size="stencilSize"
        :default-boundaries="defaultBoundaries"
        :default-size="defaultSize"
        :debounce="1"
        @change="handleChange"
        @ready="handleReady"
      />
      <div v-if="tab === 'crop'" class="rotateArea">
        <input v-model="rotate" type="range" min="0.01" max="180" step="0.01" @input="handleRotateChange" />
      </div>
    </div>
  </div>
</template>

<script>
import { Cropper } from 'vue-advanced-cropper'
import FilterIcon from '../icons/Filter'
import CropIcon from '../icons/Crop'
import TurnIcon from '../icons/Turn'
import CropOriginalIcon from '../icons/CropOriginal'
import CropPortraitIcon from '../icons/CropPortrait'
import CropSquareIcon from '../icons/CropSquare'
import Crop32Icon from '../icons/Crop32'
import Crop75Icon from '../icons/Crop75'
import Crop169Icon from '../icons/Crop169'
import ZoomOut from '../icons/ZoomOut'
import ZoomIn from '../icons/ZoomIn'
import axios from 'axios'

export default {
  name: 'ImageCropper',
  components: {
    Cropper,
    FilterIcon,
    CropIcon,
    TurnIcon,
    CropOriginalIcon,
    CropPortraitIcon,
    CropSquareIcon,
    Crop32Icon,
    Crop75Icon,
    Crop169Icon,
    ZoomOut,
    ZoomIn
  },
  data() {
    return {
      sizing: 'original', // original, square, portrait, 32, 169, 75,
      tab: 'tune',
      editingImage: null
    }
  },
  computed: {
    aspectRatio() {
      switch (this.sizing) {
        case 'original': {
          return this.editingImage.width / this.editingImage.height
        }
        case 'square': {
          return 1
        }
        case 'portrait': {
          return this.editingImage.height / this.editingImage.width
        }
        case '32': {
          return 3 / 2
        }
        case '169': {
          return 16 / 9
        }
        case '75': {
          return 7 / 5
        }
        default:
          return 1
      }
    }
  },
  methods: {
    defaultBoundaries({ cropper, imageSize }) {
      return {
        width: cropper.clientWidth,
        height: cropper.clientHeight
      }
    },
    defaultSize({ imageSize, visibleArea }) {
      return {
        width: (visibleArea || imageSize).width,
        height: (visibleArea || imageSize).height
      }
    },
    stencilSize({ boundaries, aspectRatio }) {
      return {
        height: boundaries.height * 0.6,
        width: boundaries.height * 0.6 * this.aspectRatio
      }
    },
    handleReady() {
      this.initialScale = this.$refs.cropper.image.transforms.scaleX
    },
    handleZoomInput(e) {
      this.zoomInput = true
      if (this.timer) {
        clearTimeout(this.timer)
      }
      const value = e.target.value
      this.timer = setTimeout(() => {
        const currentScale = this.$refs.cropper.image.transforms.scaleX
        const newScale = parseFloat(value) * this.initialScale
        this.$refs.cropper.zoom(newScale / currentScale)
        this.zoomInput = false
        this.timer = null
      }, 10)
    },
    handleRotateChange() {
      if (this.timer) {
        clearTimeout(this.timer)
      }
      this.timer = setTimeout(() => {
        const currentRotate = this.$refs.cropper.image.transforms.rotate
        this.$refs.cropper.rotate(this.rotate - currentRotate)
        this.timer = null
      }, 10)
    },
    handleChange(e) {
      if (this.zoomInput === false) {
        this.zoom = e.image.transforms.scaleX / this.initialScale
      }
      this.applyFilter()
    },
    handleFilterChange() {
      const filter = this.getFilter()
      this.$refs.cropper.$el.getElementsByTagName('img')[0].style.filter = filter
      this.$refs.cropper.$el.getElementsByTagName('img')[1].style.filter = filter
      this.applyFilter()
    },
    turnFilter() {
      this.saturation = 1
      this.brightness = 1
      this.contrast = 1
      this.hue = 3.14
      this.handleFilterChange()
    },
    downloadCroppedImage() {
      const image = this.croppedImage
      const link = document.createElement('a')
      link.href = image
      link.setAttribute('download', 'cropped')
      document.body.appendChild(link)
      link.click()
    },
    saveCroppedImage() {
      this.loading = true
      axios
        .post('/account/uploadStockFiles', { images: [{ url: this.croppedImage }] })
        .then((res) => {
          if (res.data.status) {
            this.updateImage({ url: res.data.data[0] })
            this.onClose()
          }
          this.loading = false
        })
        .catch((e) => {
          console.error(e)
          this.loading = false
        })
    },
    applyFilter() {
      const canvas = this.$refs.cropper.getResult().canvas
      const ctx = canvas.getContext('2d')
      ctx.filter = this.getFilter()
      ctx.drawImage(canvas, 0, 0)
      this.croppedImage = canvas.toDataURL()
    }
  }
}
</script>

<style scoped></style>
