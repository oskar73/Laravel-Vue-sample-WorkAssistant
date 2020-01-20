<template>
  <div class="bz-section-container bz-sec--carousel-1-root"
       :class="{ [breakPoint]: true, [sectionSize]: true, full: setting.layouts.fullSize }"
       @click="$refs.slider.restartCarousel()"
  >
    <bz-background :setting="background">
      <carousel
        v-model="currentSlide"
        class="tw-w-full tw-h-full"
        :items-to-show="1"
        :wrap-around="true"
        :autoplay="setting.elements.autoPlay ? setting.layouts.interval * 1000 : false"
        :mouse-drag="enableMouseDrag"
        ref="slider"
      >
        <slide class="tw-w-full" v-for="(item, key) of data.items" :key="key">
          <div class="tw-w-full tw-h-full tw-relative">
            <bz-image v-model="item.image" resize-mode="full" class="tw-w-full tw-h-full tw-object-cover" />
            <!-- <img :src="item.image.src" class="tw-w-full tw-h-full tw-object-cover" /> -->
            <div v-if="edit" class="tw-absolute tw-bottom-3 tw-left-1/2 -tw-translate-x-1/2 tw-z-10">
              <div class="tw-mb-2 tw-rounded tw-bg-black tw-bg-opacity-50">
                <div class="tw-text-sm tw-text-white tw-px-4 tw-py-2 tw-opacity-100">
                  {{ currentSlide + 1 }} / {{ data.items.length }}
                </div>
              </div>
              <div class="tw-flex tw-gap-3 ">
                <button
                  class="tw-shadow tw-bg-blue-500 hover:tw-bg-blue-400 tw-px-4 tw-py-2 tw-text-sm tw-text-white tw-rounded disabled:tw-opacity-70"
                  :disabled="data.items.length >= 7"
                  @click="addItem(key)"
                >
                  Add Slide
                </button>
                <button
                  class="tw-shadow tw-bg-white hover:tw-bg-blue-100 tw-px-4 tw-py-2 tw-text-sm tw-rounded disabled:tw-opacity-70"
                  :disabled="data.items.length <= 2"
                  @click="removeItem(key)"
                >
                  Remove Slide
                </button>
              </div>
            </div>
            <div v-if="!isImageEdit"
                 class="tw-absolute tw-top-1/2 tw-left-1/2 -tw-translate-x-1/2 -tw-translate-y-1/2 tw-w-full tw-z-10">
              <bz-container>
                <bz-alignment :alignment="setting.layouts.alignment">
                  <bz-title v-if="setting.listElements.title" v-model="data.items[key].title" :edit-mode="edit" />
                  <bz-subtitle v-if="setting.listElements.subtitle" v-model="data.items[key].subtitle"
                               :edit-mode="edit" />
                  <bz-text v-if="setting.listElements.description" v-model="data.items[key].description"
                           :edit-mode="edit" />
                  <bz-button-group v-if="setting.listElements.buttons" v-model="data.items[key].buttons"
                                   :edit-mode="edit" />
                </bz-alignment>
              </bz-container>
            </div>
          </div>
        </slide>

        <template #addons>
          <navigation v-if="setting.elements.navigation" />
        </template>
      </carousel>
    </bz-background>
  </div>
</template>

<script>
import sectionMixin from '../../mixins/sectionMixin'

import BzTitle from '../../components/section/BzTitle.vue'
import BzSubtitle from '../../components/section/BzSubtitle.vue'
import BzContainer from '../../components/section/BzContainer.vue'
import BzAlignment from '../../components/section/BzAlignment.vue'
import BzButtonGroup from '../../components/section/BzButtonGroup.vue'
import BzBackground from '../../components/section/BzBackground.vue'
import { Carousel, Slide, Pagination, Navigation } from 'vue3-carousel'
import 'vue3-carousel/dist/carousel.css'
import eventBus from '@/public/eventBus'

const defaultSlide = {
  title: 'Type your text',
  subtitle: 'Type your text',
  description: 'Type your text',
  buttons: [
    {
      title: 'more',
      size: 's',
      outline: false
    }
  ],
  image: {
    zoomMin: 1,
    altText: {},
    maskImage: {},
    link: {
      type: 'no-link',
      value: 'javascript:void(0)'
    },
    transform: {
      translateX: 0,
      translateY: 0,
      scale: 1
    },
    src: 'https://s3.amazonaws.com/storage.bizinabox.com/assets/img/logo_fill.png',
    width: 600,
    height: 900
  }
}

export default {
  components: {
    BzBackground,
    BzButtonGroup,
    BzAlignment,
    BzContainer,
    BzSubtitle,
    BzTitle,
    Carousel,
    Slide,
    Pagination,
    Navigation
  },
  mixins: [sectionMixin],
  props: {
    enableMouseDrag: { // This prop is always false for the edit builder, but when page is user preview or publish, this props will be true
      type: Boolean,
      default: false
    }
  },
  data() {
    return {
      isImageEdit: false,
      currentSlide: 0
    }
  },
  mounted() {
    eventBus.$on('IframeResized', () => {
      if (this.$refs.slider) {
        this.$refs.slider.restartCarousel()
      }
    })
  },
  methods: {
    addItem(index) {
      if (this.data.items.length < 7) {
        this.data.items = [...this.data.items, defaultSlide]
        this.data = window._copy(this.data)
        this.currentSlide = this.data.items.length - 1
      }
    },
    removeItem(index) {
      if (this.data.items.length > 2) {
        this.data.items.splice(index, 1)
        this.data = window._copy(this.data)
        this.currentSlide = this.data.items.length - 1
      }
    }
  }
}
</script>
<style lang="scss">
.bz-sec--carousel-1-root {
  height: 60vh;

  &.m {
    height: 70vh;
  }

  &.l {
    height: 80vh;
  }

  &.xl {
    height: 90vh;
  }

  &.full {
    height: 100vh;
  }
}
</style>
