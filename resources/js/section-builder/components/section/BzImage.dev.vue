<template>
  <div v-click-outside="handleOutsideClick" class="bz-el--image-root bz-element">
    <div
      v-if="renderComponent"
      class="image-wrapper"
      :class="{ [resizeMode]: true, editing, isDragging }"
      :style="imageWrapperStyle"
      @click="openImageEditor"
      @mouseup="handleMouseUp"
      @mousedown.prevent="handleMouseDown"
    >
      <bz-link :link="imageData.link">
        <div class="image-container" :style="imageContainerStyle" :class="{ 'tw-blur tw-animate-pulse': loadingImage }">
          <img ref="imageRef" :src="imageSrc" class="bz-image" :alt="imageData.altText" :style="imageStyle" @load="handleImageLoad" />
        </div>
      </bz-link>
    </div>
  </div>
</template>

<script>
import { createApp } from 'vue'
import elementMixin from '../../mixins/elementMixin'
import BzLink from './BzLink.vue'
import { cloneDeep, merge } from 'lodash'
import ImageEditor from '@/section-builder/components/editor/ImageEditor.vue'
import ClickOutside from '@/public/directives/ClickOutside'
import { loadImage } from '@/section-builder/utils/helper'
import rerenderMixin from '@/section-builder/mixins/rerenderMixin'

export default {
  name: 'BzImage',
  components: {
    BzLink
  },
  mixins: [elementMixin, rerenderMixin],
  props: {
    maxHeight: {
      type: Number,
      default: 0
    },
    resizeMode: {
      type: String,
      default: 'cover'
    },
    fitHeight: {
      type: Boolean,
      default: false
    },
    styles: {
      type: Object,
      default: () => {
        return {}
      }
    },
    circle: {
      type: Boolean,
      default: false
    },
    width: {
      type: String,
      default: '100%'
    },
    rounded: {
      type: [Boolean, Number],
      default: false
    },
    noZoom: {
      type: [Boolean],
      default: false
    }
  },
  data () {
    return {
      editor: null,
      editing: false,
      replacing: false,
      isDragging: false,
      timer: null,
      translateX: 0,
      translateY: 0,
      transformScale: 1,
      loadingImage: false,
      isOpenEditor: false,
      imageData: {
        zoomMin: 0.1,
        altText: '',
        maskImage: '',
        link: {
          type: 'no-link',
          value: 'javascript:void(0)'
        },
        transform: {
          scale: 1,
          translateX: 0,
          translateY: 0
        }
      }
    }
  },
  computed: {
    imageSrc () {
      if (this.imageData.width) {
        return this.imageData.src
      }
      if (this.imageData.thumb) {
        return this.imageData.thumb
      }
      return this.imageData.src
    },
    imageStyle () {
      const _imgStyle = {}
      if (this.noZoom) {
        _imgStyle.width = '100%'
      }

      let isPortrait = false
      if (this.imageData.width && this.imageData.height > this.imageData.width) {
        isPortrait = true
      } else if (this.imageData.thumbWidth && this.imageData.thumbHeight > this.imageData.thumbWidth) {
        isPortrait = true
      }

      if (isPortrait) {
        _imgStyle.width = '100%'
        _imgStyle.height = 'auto'
      } else {
        _imgStyle.width = 'auto'
        _imgStyle.height = '100%'
      }

      return _imgStyle
    },
    imageWrapperStyle () {
      let borderRadius = 0
      const wrapperStyle = this.styles.root || {}

      if (this.rounded === true) {
        borderRadius = '100%'
      }

      if (typeof this.rounded === 'number') {
        borderRadius = this.rounded + 'px'
      }

      wrapperStyle.borderRadius = borderRadius

      if (this.maxHeight) {
        wrapperStyle.width = this.width
        wrapperStyle.maxHeight = this.maxHeight + 'px'
      }

      if (this.imageData.maskImage) {
        wrapperStyle['-webkit-mask-image'] = `url(${this.imageData.maskImage})`
      }

      return wrapperStyle
    },
    imageContainerStyle () {
      const containerStyle = this.styles.image || {}

      if (this.circle) {
        containerStyle.borderRadius = '10000px'
      }

      return containerStyle
    }
  },
  watch: {
    'imageData.transform.scale': {
      handler () {
        this.transformScale = this.imageData.transform.scale
        this.updateImageStyle()
      }
    },
    'imageData.src': {
      async handler (value) {
        this.imageData.width = 0
        this.imageData.height = 0
        this.imageData.transform = {
          scale: 1,
          translateX: 0,
          translateY: 0
        }
        this.transformScale = 1
        this.translateX = 0
        this.translateY = 0
        this.loadingImage = true
        await this.forceRerender()
        loadImage(value).then((img) => {
          this.imageData.width = img.width
          this.imageData.height = img.height
          this.loadingImage = false
        })
      }
    }
  },
  mounted () {
    this.imageData = cloneDeep(merge(this.imageData, this.data))
    this.translateX = this.imageData.transform.translateX
    this.translateY = this.imageData.transform.translateY
    this.transformScale = this.imageData.transform.scale
    this.updateImageStyle()
  },
  methods: {
    getWindow () {
      if (this.$el) {
        return this.$el.ownerDocument.defaultView
      }
      return window
    },
    handleImageLoad () {
      const outerRect = this.$el.getBoundingClientRect()
      if (this.$refs.imageRef) {
        const imageRect = this.$refs.imageRef.getBoundingClientRect()
        if (imageRect.height < imageRect.width) {
          if (imageRect.width * this.transformScale < outerRect.width) {
            this.imageData.zoomMin = (imageRect.height * this.transformScale) / outerRect.height
          } else {
            this.imageData.zoomMin = (outerRect.height / imageRect.height) * this.transformScale
          }
        } else {
          if (imageRect.height * this.transformScale < outerRect.height) {
            this.imageData.zoomMin = (imageRect.width * this.transformScale) / outerRect.width
          } else {
            this.imageData.zoomMin = (outerRect.width / imageRect.width) * this.transformScale
          }
        }
        if (this.transformScale < this.imageData.zoomMin) {
          this.transformScale = this.imageData.zoomMin
          this.updateImageStyle()
        }
        this.fitImageToContainer()
        this.updateData()
      }
    },
    updateData () {
      this.imageData.transform = {
        translateX: this.translateX,
        translateY: this.translateY,
        scale: this.transformScale
      }
      this.$emit('update:modelValue', this.imageData)
    },
    updateImageStyle () {
      if (this.noZoom) {
        this.$refs.imageRef.style.transform = `translate(${Math.round(this.translateX)}px, ${Math.round(this.translateY)}px)`
      } else {
        this.$refs.imageRef.style.transform = `translate(${Math.round(this.translateX)}px, ${Math.round(this.translateY)}px) scale(${this.transformScale})`
      }
    },
    fitImageToContainer () {
      const outerRect = this.$el.getBoundingClientRect()
      if (this.$refs.imageRef) {
        const innerRect = this.$refs.imageRef.getBoundingClientRect()

        if (outerRect.right > innerRect.right) {
          this.translateX += (outerRect.right - innerRect.right)
        }
        if (outerRect.left < innerRect.left) {
          this.translateX += (outerRect.left - innerRect.left)
        }
        if (outerRect.bottom > innerRect.bottom) {
          this.translateY += (outerRect.bottom - innerRect.bottom)
        }
        if (outerRect.top < innerRect.top) {
          this.translateY += (outerRect.top - innerRect.top)
        }
        this.updateImageStyle()
      }
    },
    openImageEditor () {
      if (this.edit && !this.isOpenEditor) {
        const buttonEditor = document.getElementById('bz-element-editor')

        if (window.bzElementEditor) {
          window.bzElementEditor.unmount()
          window.bzElementEditor = null
        }

        const ownerDocument = this.$el.ownerDocument
        const self = this

        ownerDocument.addEventListener('mouseup', self.handleMouseUp.bind(self), false)
        ownerDocument.addEventListener('mousemove', self.handleMouseMove.bind(self), false)

        const iFrame = document.getElementById('bz-page-content-frame')
        const iFrameRect = iFrame.getBoundingClientRect()

        const rect = this.$el.getBoundingClientRect()
        const top = rect.top + iFrameRect.top - 85
        const left = rect.left + iFrameRect.left

        window.bzElementEditor = createApp(ImageEditor, {
          top,
          left,
          noZoom: self.noZoom,
          modelValue: self.imageData,
          'onUpdate:modelValue': (value) => {
            this.imageData = value
          }
        })
        window.bzElementEditor.use(this.$store)
        window.bzElementEditor.mixin({
          unmounted () {
            ownerDocument.removeEventListener('mouseup', self.handleMouseUp.bind(self))
            ownerDocument.removeEventListener('mousemove', self.handleMouseMove.bind(self))
            self.isOpenEditor = false
          },
          methods: {
            asset: this.asset
          }
        })
        window.bzElementEditor.directive('click-outside', ClickOutside)
        window.bzElementEditor.mount(buttonEditor)
        this.isOpenEditor = true
      }
    },
    handleMouseMove (e) {
      if (this.isDragging) {
        this.translateX += e.movementX
        this.translateY += e.movementY
        this.updateImageStyle()
      }
    },
    handleMouseDown () {
      this.translateX = this.imageData.transform.translateX ?? 0
      this.translateY = this.imageData.transform.translateY ?? 0
      this.isDragging = true
    },
    stopDragging () {
      if (this.isDragging) {
        this.isDragging = false
        this.imageData.transform.translateX = this.translateX
        this.imageData.transform.translateY = this.translateY
        if (!this.noZoom) {
          this.fitImageToContainer()
        }
        this.updateData()
      }
    },
    handleMouseUp () {
      this.stopDragging()
    },
    handleOutsideClick () {
      this.editing = false
    }
  }
}
</script>
<style lang="scss">
.bz-el--image-root {
  height: 100%;
  width: 100%;
  display: flex;
  align-items: center;
  position: relative;

  .bz-image-editor-container {
    width: 0;
    height: 0;
    position: absolute;
    top: 0;
    left: 0;
  }

  .image-wrapper {
    width: 100%;
    position: relative;
    cursor: pointer;
    z-index: 1;
    display: flex;
    align-items: center;
    justify-content: center;
    height: inherit;
    overflow: hidden;

    -webkit-mask-repeat: no-repeat;
    -webkit-mask-size: contain;
    -webkit-mask-position: top center;

    &.full {
      height: 100%;
    }

    &.editing {
      cursor: move;
    }

    &.isDragging {
      opacity: 0.7;
    }

    .image-container {
      min-height: 100%;
      min-width: 100%;
      overflow: hidden;
      height: 1px;
      width: 1px;
      display: flex;
      justify-content: center;
      align-items: center;
    }

    .bz-image {
      transform-origin: center;
      position: relative;
      min-height: 100%;
      min-width: 100%;
      max-width: none !important;
      flex-shrink: 0;
      object-fit: cover;
    }
  }
}
</style>
