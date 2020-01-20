<template>
  <div class="bz-el--image-root bz-element">
    <div class="image-wrapper" :class="{ [resizeMode]: true }" :style="imageWrapperStyle">
      <bz-link :link="imageData.link">
        <div class="image-container" :style="imageContainerStyle">
          <img ref="imageRef" :src="imageSrc" class="bz-image" :alt="imageData.altText" :style="imageStyle" />
        </div>
      </bz-link>
    </div>
  </div>
</template>

<script>
import { cloneDeep, merge } from 'lodash'
import elementMixin from '../../mixins/elementMixin'
import BzLink from './BzLink.vue'

export default {
  name: 'BzImage',
  components: {
    BzLink
  },
  mixins: [elementMixin],
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
  data() {
    return {
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
    imageSrc() {
      if (this.imageData.width) {
        return this.imageData.src
      }
      if (this.imageData.thumb) {
        return this.imageData.thumb
      }
      return this.imageData.src
    },
    imageStyle() {
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
    imageWrapperStyle() {
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
    imageContainerStyle() {
      const containerStyle = this.styles.image || {}

      if (this.circle) {
        containerStyle.borderRadius = '10000px'
      }

      return containerStyle
    }
  },
  mounted() {
    this.imageData = cloneDeep(merge(this.imageData, this.data))
    this.translateX = this.imageData.transform.translateX
    this.translateY = this.imageData.transform.translateY
    this.transformScale = this.imageData.transform.scale
    this.updateImageStyle()
  },
  methods: {
    updateImageStyle() {
      if (this.noZoom) {
        this.$refs.imageRef.style.transform = `translate(${Math.round(this.translateX)}px, ${Math.round(this.translateY)}px)`
      } else {
        this.$refs.imageRef.style.transform = `translate(${Math.round(this.translateX)}px, ${Math.round(this.translateY)}px) scale(${this.transformScale})`
      }
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
