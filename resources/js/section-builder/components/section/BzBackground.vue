<template>
  <div class="bz-el-background-root" :style="backgroundStyle">
    <div v-if="!noImage" class="bz-background-content" :class="{ 'expand-to-header': position === 0 }">
      <template v-if="!setting.connectToSectionBelow">
        <iframe
          v-if="backgroundType === 'video' && backgroundData.isYoutube"
          :src="`https://www.youtube.com/embed/${backgroundData.video}?autoplay=1&loop=1&mute=1&playlist=${backgroundData.video}`"
          allowfullscreen
          allow="autoplay"
          class="w-100"
        ></iframe>
        <video v-if="backgroundType === 'video' && backgroundData.video" ref="backgroundVideo" loop autoplay muted>
          <source :src="backgroundData.video" type="video/mp4" />
        </video>
        <div v-if="backgroundType === 'image' || pattern" class="background-image-base">
          <div v-if="backgroundData.animation === 'parallax'" class="background-parallax-container">
            <div
              class="background-image"
              :class="{ pattern, [backgroundData.patternName]: true, image: backgroundType === 'image' && backgroundData.image }"
              :style="backgroundImageStyle()"
            />
          </div>
          <div v-else class="background-image" :class="backgroundImageClasses" :style="backgroundImageStyle()" />
        </div>
        <div v-if="backgroundData.overlay" class="background-over-layer" :style="backgroundOverlayStyle()" />
      </template>
    </div>
    <div class="w-100 h-100 position-relative">
      <bz-section-size v-if="size" :section-size="size">
        <slot />
      </bz-section-size>
      <slot v-else />
    </div>
  </div>
</template>

<script>
import { defineComponent } from 'vue'
import BzSectionSize from './BzSectionSize.vue'
import elementMixin from '../../mixins/elementMixin'
import { cloneDeep, merge } from 'lodash'

export default defineComponent({
  name: 'BzBackground',
  components: { BzSectionSize },
  mixins: [elementMixin],
  props: {
    setting: {
      type: [Object, Array],
      default: () => {
        return {}
      }
    },
    size: {
      type: String,
      default: ''
    },
    noImage: {
      type: Boolean,
      default: false
    }
  },
  data() {
    return {
      backgroundData: {}
    }
  },
  computed: {
    backgroundType() {
      return this.backgroundData.type || 'color'
    },
    pattern() {
      return this.backgroundType === 'color' && this.backgroundData.pattern && this.backgroundData.patternName
    },
    backgroundImageClasses() {
      return { pattern: this.pattern, [this.backgroundData.animation]: true, [this.backgroundType]: true }
    },
    animationEnabled() {
      return window.animationEnabled
    },
    backgroundColor() {
      return this.backgroundData.color ?? this.palette.backgroundColor
    },
    backgroundStyle() {
      let backgroundColor = this.backgroundColor
      if ((this.sectionData.name.includes('Header') || this.sectionData.name.includes('Footer')) && this.setting.connectToSectionBelow) {
        backgroundColor = 'transparent'
      }
      let height = this.setting.size
      if (typeof height === 'number') {
        height = height + 'px'
      }
      return { backgroundColor, minHeight: height }
    }
  },
  watch: {
    setting: {
      immediate: true,
      deep: true,
      handler() {
        this.backgroundData = cloneDeep(
          merge(
            {
              type: 'color',
              animation: 'none'
            },
            this.setting ?? {}
          )
        )
      }
    },
    backgroundData: {
      deep: true,
      handler(value, oldValue) {
        if (value.video !== oldValue.video) {
          this.$nextTick(function () {
            if (this.$refs.backgroundVideo) {
              this.$refs.backgroundVideo.setAttribute('src', this.backgroundData.video)
              this.$refs.backgroundVideo.load()
              this.$refs.backgroundVideo.play()
            }
          })
        }
      }
    }
  },
  methods: {
    backgroundOverlayStyle() {
      if (this.backgroundData.overlay) {
        const opacity = (this.backgroundData.overlayOpacity || 0) / 100
        const backgroundColor = this.setting.overlayColor || this.backgroundColor
        return { backgroundColor, opacity }
      }
      return null
    },
    backgroundImageStyle() {
      const backgroundColor = this.backgroundColor || this.backgroundColor

      if (this.backgroundType === 'color') {
        let opacity = 1
        opacity = this.backgroundData.patternStrength / 100
        if (this.backgroundData.pattern && this.backgroundData.patternName) {
          return {
            opacity,
            backgroundImage: `url(${this.s3_asset(`builder/pattern/${this.backgroundData.patternName}.svg`)})`
          }
        }
      } else if (this.backgroundType === 'image' && this.backgroundData.image) {
        const url = this.backgroundData.image
        return {
          backgroundImage: `url(${url})`
        }
      }
      return { backgroundColor }
    }
  }
})
</script>

<style lang="scss">
.bz-el-background-root {
  width: 100%;
  height: inherit;
  position: relative;
  display: flex;
  justify-content: center;
  align-items: center;

  .bz-background-content {
    position: absolute;
    width: 100%;
    top: 0;
    left: 0;
    bottom: 0;
    overflow: hidden;

    &.expand-to-header {
      top: calc(-1 * var(--navigate-bar-height, 0px));
    }

    video {
      width: 100%;
      height: 100%;
      object-fit: cover;
      z-index: 0;
    }

    iframe,
    iframe:not(.md-image) {
      height: 100%;
    }

    .background-image-base {
      width: 100%;
      height: 100%;
      z-index: 1;
      overflow: hidden;

      .background-image {
        width: 100%;
        height: 100%;

        &.image {
          background-position: center center;
          background-repeat: no-repeat;

          &.animate {
            background-size: cover;
          }
        }

        &.none {
          background-size: cover;
        }

        &.contain {
          background-size: contain;
        }

        &.fixed {
          background-size: cover;
          background-attachment: fixed;
        }

        &.pattern {
          background-size: unset;
          background-position: unset;
          background-repeat: repeat;

          &.pattern5 {
            background-size: 48px 48px !important;
          }
          &.pattern6 {
            background-size: 16px 16px !important;
          }
        }

        &.animate {
          animation: 60s linear animateImage;
          transform-origin: center center;
          animation-iteration-count: infinite;
        }

        @keyframes animateImage {
          from {
            transform: scale(1);
          }
          to {
            transform: scale(2);
          }
        }
      }

      .background-parallax-container {
        transform: scale(1.4) translate3d(0px, 3.99808%, 0px);
        position: absolute;
        left: 0;
        right: 0;
        top: 0;
        bottom: 0;
        background-repeat: no-repeat;
        background-attachment: scroll;
        background-position: center center;
        background-size: cover;

        .background-image {
          background-attachment: scroll;
          background-position: center center;

          &.image {
            background-size: cover;
          }
        }
      }
    }

    .background-over-layer {
      position: absolute;
      left: 0;
      top: 0;
      width: 100%;
      height: 100%;
      z-index: 0;
    }
  }
}
</style>
