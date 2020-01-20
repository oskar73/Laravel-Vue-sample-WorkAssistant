<template>
  <div ref="mainContent" class="main_content_area tw-absolute">
    <div class="main_content_frame" :class="{ [viewMode]: true }">
      <header class="content-header tw-sticky tw-top-0 tw-border-b tw-mx-7">
        <div class="tw-flex tw-bg-white tw-px-4 tw-py-1.5 tw-rounded-t-lg">
          <div class="tw-flex tw-items-center">
            <span class="tw-h-3 tw-w-3 tw-bg-gray-200 tw-rounded-full tw-mr-1"></span>
            <span class="tw-h-3 tw-w-3 tw-bg-gray-200 tw-rounded-full tw-mr-1"></span>
            <span class="tw-h-3 tw-w-3 tw-bg-gray-200 tw-rounded-full tw-mr-2"></span>
          </div>
          <div class="tw-bg-gray-100 tw-rounded tw-flex tw-flex-1 tw-py-1 tw-px-2 tw-whitespace-nowrap tw-overflow-hidden">
            {{ activePageUrl }}
          </div>
          <div class="tw-ml-4 cursor-pointer tw-flex tw-items-center tw-gap-2">
            <a :href="activePageUrl" target="_blank">
              <i class="mdi mdi-open-in-new text-lg"></i>
            </a>
            <div>
              <i class="mdi mdi-arrow-expand-all text-lg"></i>
            </div>
          </div>
        </div>
      </header>
      <div class="tw-flex-1 tw-relative tw-mx-7 tw-bg-white">
        <render-iframe v-if="renderComponent" :store="$store" />
      </div>
    </div>
  </div>
</template>
<script>
import { mapMutations } from 'vuex'
import builderMixin from '../../mixins/builderMixin'
import RenderIframe from '@/section-builder/components/RenderIframe'
import rerenderMixin from '@/section-builder/mixins/rerenderMixin'

export default {
  components: { RenderIframe },
  mixins: [builderMixin, rerenderMixin],
  computed: {
    activePageUrl() {
      if (!this.activePage) {
        if (this.template.domain) {
          return `https://${this.template.domain}${this.$route.path}`
        }
        return `${this.$config.appURL}/admin/template/item/preview/${this.template.slug}${this.$route.path}`
      }

      if (this.activePage.type === 'new-page') {
        return 'New Page Template'
      }

      let url = this.activePage.url
      if (typeof url === 'object') url = ''
      if (this.template.domain) {
        return `https://${this.template.domain}${url || ''}`
      }
      return `${this.$config.appURL}/admin/template/item/preview/${this.template.slug}${url || ''}`
    }
  },
  watch: {
    activeSlider: {
      immediate: true,
      handler() {
        this.animate()
      }
    },
    $route() {
      this.forceRerender()
    }
  },
  methods: {
    animate() {
      this.$nextTick(() => {
        let left = 70
        let right = 0
        if (this.activeSlider) {
          if (this.activeSlider === 'sections') {
            left = 570
          } else if (this.activeSlider === 'pages' || this.activeSlider === 'templates' || this.activeSlider === 'myTemplates') {
            left = 370
          } else if (this.activeSlider === 'theme') {
            left = 470
          }
        }

        if (this.isFixedSettingPanel && this.isOpenSettingPanel) {
          right = 320
        }

        const width = window.innerWidth - left - right
        this.$el.style.width = width + 'px'
        this.$el.style.left = left + 'px'
      })
    },
    ...mapMutations({
      updateLayout: 'updateLayout'
    })
  }
}
</script>

<style lang="scss">
$active: #0076df;
$dark_active: #0067c1;
$danger: darkred;

.main_content_area {
  padding: 10px 0 15px;
  left: 70px;
  top: 60px;
  overflow-y: auto;
  bottom: 0;
  height: calc(100% - 60px);
  min-height: unset;

  .main_content_frame {
    width: 100%;
    height: 100%;
    display: flex;
    flex-direction: column;
    margin: auto;

    &.tablet {
      max-width: 768px !important;
    }

    &.mobile {
      max-width: 400px !important;
      height: 768px !important;
    }
  }

  .content-header {
    background-color: #d9dee3;
    z-index: 10 !important;
    overflow: hidden;
    text-overflow: ellipsis;

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

  iframe {
    width: 100%;
    height: 100%;
  }

  &.sm_view {
    max-width: 900px;
  }

  &.mobile_view {
    max-width: 500px !important;
  }
}

.bz-component-container {
  position: relative;
  height: 100%;

  [class*='carousel-3d-container'] {
    position: unset !important;
  }

  .carousel-3d-slider {
    display: flex !important;
    align-items: center !important;
    transform: scale(0.8);
  }

  .carousel-3d-slide {
    top: unset !important;
    height: min-content !important;

    &.current {
      border: solid 3px $active !important;
    }
  }
}

@keyframes remove {
  from {
    height: 500px;
  }
  to {
    height: 0;
  }
}

.bz-content-section {
  position: relative;
  height: min-content;

  &.remove {
    animation-name: remove;
    animation-duration: 0.5s;
  }
}

.active-content-overlay {
  position: absolute;
  pointer-events: none;
  z-index: 50;
  top: 0;
  width: calc(100% - 16px);
  max-height: 100%;
  left: 8px;

  &.deleting {
    animation-name: remove;
    animation-duration: 0.5s;
  }

  &:before {
    content: '';
    position: absolute;
    display: block;
    box-shadow: rgb(0 0 0 / 24%) 0 1px 6px 0;
    border: 1px solid white;
    outline: rgb(0, 118, 223) solid 3px;
    height: calc(100% - var(--next-decorator-offset, 4px));
    inset: 0;
    z-index: 2;
  }

  .arrow_item {
    position: absolute;
    top: calc(50% - 8px);
    transform: translate(-50%, 0);
    border-radius: 50%;
    background-color: $active;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    flex-shrink: 0;
    width: 18px;
    height: 18px;
    pointer-events: auto;

    * {
      color: white !important;
    }

    &:hover {
      background-color: $dark_active;
    }

    &.left_arrow {
      left: 35px;
    }

    &.right_arrow {
      right: 18px;
    }
  }

  .add_section {
    position: absolute;
    pointer-events: auto;
    left: 50%;
    z-index: 3;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 100px;
    padding: 2px 4px;
    color: #fff;
    font-weight: bold;
    cursor: pointer;
    background-color: $active;

    .add_section_txt {
      width: 0;
      font-size: 12px;
      font-weight: 400;
      overflow: hidden;
      white-space: nowrap;
      transition: 0.3s;
      color: white;
    }

    &:hover {
      background-color: $dark_active;
    }

    &:hover > .add_section_txt {
      width: 72px;
      margin-left: 4px;
      transition: width 0.3s;
      padding-right: 5px;
    }

    &.top_add {
      top: 0;
      transform: translate(-50%, -50%);
    }

    &.bottom_add {
      bottom: 0;
      transform: translate(-50%, 50%);
    }
  }

  .control_box {
    pointer-events: auto;
    position: absolute;
    top: 10px;
    right: 20px;
    display: flex;
    background-color: $active;
    border-radius: 30px;
    z-index: 9;

    .control_item {
      display: flex;
      align-items: center;
      justify-content: center;
      background-color: $active;
      width: 24px;
      border-radius: 50%;
      height: 24px;
      cursor: pointer;

      &:hover {
        background-color: $dark_active;
      }

      &.arrow_remove {
        background-color: $danger;
      }
    }
  }
}

@keyframes emptySectionFrame {
  from {
    height: 0;
  }
  to {
    height: 350px;
  }
}

@keyframes emptySectionRemoveFrame {
  from {
    height: 350px;
  }
  to {
    height: 0;
  }
}

.empty-section {
  height: 350px;
  animation-name: emptySectionFrame;
  animation-duration: 0.35s;
  overflow: hidden;

  &.removing {
    animation-name: emptySectionRemoveFrame;
  }
}
</style>
