<template>
  <div class="tw-bg-white">
    <div v-if="template" class="tw-mt-2" :class="{ [activeSlider]: true }">
      <bz-header :preview="preview" />
      <slot />
      <bz-footer :preview="preview" />
    </div>
  </div>
</template>

<script>
import builderMixin from '../../mixins/builderMixin'
import eventBus from '@/public/eventBus'

export default {
  mixins: [builderMixin],
  props: {
    preview: {
      type: Boolean,
      default: false
    },
    view: {
      type: Boolean,
      default: false
    }
  },
  data() {
    return {
      scale: 1,
      zoom: 1
    }
  },
  computed: {
    activePageUrl() {
      if (this.activePage.type === 'new-page') {
        return 'New Page Template'
      }

      let url = this.activePage.url
      if (typeof url === 'object') url = ''
      if (this.template.domain) {
        return `https://${this.template.domain}${url || ''}`
      }
      return `${this.$config.appURL}/preview/${this.template.slug}${url || ''}`
    }
  },
  watch: {
    activeSlider: {
      immediate: true,
      handler() {
        this.animate()
      }
    }
  },
  mounted() {
    // new ResizeObserver((entries) => {
    //   const rect = entries[0].contentRect
    //   const width = rect.width
    //   this.scale = width / document.body.clientWidth
    //   // to use in the sections inside page content
    //   // bounding client pixels changes
    //   window.contentScale = this.scale
    //   document.body.style.setProperty('--bz-content-scale', this.scale)
    // }).observe(this.$el)
  },
  methods: {
    handleScroll() {
      if (window.bzElementEditor) {
        window.bzElementEditor.unmount()
        window.bzElementEditor = null
      }
    },
    sectionsWrapperStyle() {
      if (this.activeSlider !== 'theme') {
        this.zoom = 1
      }
      const translateX = (document.body.clientWidth / 2) * (1 - this.scale)
      return {
        '--window-width': document.body.clientWidth + 'px',
        transform: `translateZ(0px) translateX(${-translateX}px) scale(${this.scale * this.zoom})`
      }
    },
    animate() {
      this.$nextTick(() => {
        let left = 70
        let right = 0
        if (this.activeSlider) {
          if (this.activeSlider === 'sections') {
            left = 570
          } else if (this.activeSlider === 'pages' || this.activeSlider === 'templates') {
            left = 370
          } else if (this.activeSlider === 'theme') {
            left = 470
          }
        }

        if (this.isFixedSettingPanel && this.isOpenSettingPanel) {
          right = 320
        }

        // const width = window.innerWidth - left - right
        // this.$el.style.width = width + 'px'
        // this.$el.style.left = left + 'px'
        setTimeout(() => {
          eventBus.$emit('scrollToActiveSection')
        }, 100)
      })
    }
  }
}
</script>

<style lang="scss">
$active: #0076df;
$dark_active: #0067c1;
$danger: darkred;

.main_content_area {
  background-color: rgb(217, 222, 227);
  padding: 10px 25px;
  display: flex;
  flex-direction: column;
  left: 70px;
  top: 60px;
  overflow-y: auto;
  bottom: 0;
  min-height: 100%;

  .content-header {
    background-color: #d9dee3;
    z-index: 10 !important;

    &::after {
      content: '';
      width: 100%;
      height: 30px;
      background-color: #d9dee3;
      bottom: 100%;
      position: absolute;
      z-index: 1;
    }
  }

  .sections-wrapper {
    --window-width: 100vw;
    width: var(--window-width);
    transform-origin: center top;
  }

  //&::-webkit-scrollbar {
  //  width: 2px;
  //}

  &.sm_view {
    max-width: 900px;
  }

  &.mobile_view {
    max-width: 500px !important;
  }
}
</style>
