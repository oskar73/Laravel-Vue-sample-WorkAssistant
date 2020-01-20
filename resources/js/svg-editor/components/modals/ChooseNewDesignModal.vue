<script>
import { Splide, SplideSlide } from '@splidejs/vue-splide'
import { defineComponent } from 'vue'
import Modal from '@/public/Modal.vue'
import appMixin from '@/svg-editor/mixins/app-mixin'
import BzAspectView from '@/section-builder/components/section/BzAspectView.vue'
import Loader from '@/public/Loader.vue'
import { chooseGraphic, chooseNewDesign } from '@/svg-editor/api'

export default defineComponent({
  name: 'ChooseNewDesignModal',
  components: { Loader, BzAspectView, Modal, Splide, SplideSlide },
  mixins: [appMixin],
  props: {
    show: {
      type: [Number, Boolean],
      required: true
    }
  },
  data() {
    return {
      selectedGraphic: null,
      loading: false,
      categories: [],
      activeCategory: null,
      noCategories: false,
      graphicData: {},
      options: {
        rewind: true,
        perPage: 8,
        gap: 10
      }
    }
  },
  computed: {
    designs() {
      return this.activeCategory?.designs ?? []
    }
  },
  watch: {
    show() {
      if (this.show === 2) {
        this.selectedGraphic = this.graphic
        this.selectGraphic()
      } else {
        this.selectedGraphic = null
      }
    }
  },
  methods: {
    async chooseGraphic(graphic) {
      this.selectedGraphic = graphic
      await this.selectGraphic()
    },
    async selectGraphic() {
      if (this.graphicData[this.selectedGraphic.slug]) {
        this.categories = this.graphicData[this.selectedGraphic.slug]
      }
      {
        this.loading = false
        const data = await chooseGraphic(this.selectedGraphic.slug)
        this.categories = data.categories
      }
      if (this.categories.length === 0) {
        this.noCategories = true
      } else {
        this.activeCategory = this.categories[0]
      }
      this.loading = false
    },
    async handleDesignClick(design) {
      this.loadingSvgData = true
      chooseNewDesign(design.hash).then((data) => {
        this.designData = data
        this.loadingSvgData = false
        this.liveView = false
        history.pushState(null, '', this.route('graphics.edit', { designHash: data.hash }))
      })
    }
  }
})
</script>

<template>
  <modal :show="Boolean(show)">
    <div class="tw-bg-white tw-w-full tw-max-w-7xl tw-rounded tw-shadow tw-h-full tw-flex tw-flex-col">
      <div class="tw-px-4 tw-pt-2 tw-flex tw-item-center tw-justify-between">
        <h3 class="tw-text-xl tw-cursor-pointer" @click="selectedGraphic = null">
          <i v-if="selectedGraphic" class="mdi mdi-arrow-left"></i>
          <span v-else>Select New Design</span>
        </h3>
        <div class="tw-cursor-pointer" @click="$emit('close')">
          <i class="mdi mdi-close tw-text-xl"></i>
        </div>
      </div>
      <hr class="tw-my-0" />
      <div class="tw-px-4 tw-py-5 tw-flex-1">
        <div v-if="selectedGraphic" class="tw-flex tw-relative tw-h-full tw-flex-col">
          <loader v-if="loading" />
          <template v-else>
            <div class="tw-w-full">
              <splide :options="options">
                <splide-slide v-for="category in categories" :key="category.id">
                  <div class="tw-w-full tw-cursor-pointer" @click="activeCategory = category">
                    <bz-aspect-view>
                      <img :src="category.image" class="tw-w-full tw-h-full tw-object-cover" alt="Sample" />
                    </bz-aspect-view>
                  </div>
                </splide-slide>
              </splide>
            </div>
            <hr />
            <div class="tw-w-full tw-flex tw-flex-1">
              <div v-if="designs.length > 0" class="tw-grid tw-grid-cols-6 tw-gap-2 tw-w-full">
                <div v-for="design in designs" :key="design.id" class="tw-relative tw-group tw-h-max tw-cursor-pointer" @click="handleDesignClick(design)">
                  <bz-aspect-view class="tw-border">
                    <img :src="design.preview" class="tw-w-full tw-h-full tw-object-cover" alt="Sample" />
                  </bz-aspect-view>
                  <div
                    class="tw-absolute tw-shadow-lg tw-w-48 tw-text-gray-700 tw-bg-white tw-py-2 tw-px-5 tw-hidden group-hover:tw-block tw-top-1/2 tw-left-1/2"
                    style="transform: translate(-50%, -50%)"
                  >
                    EDIT TEMPLATE
                  </div>
                </div>
              </div>
              <div v-else class="tw-text-center tw-w-full tw-py-5 tw-text-gray-400">No Designs</div>
            </div>
          </template>
        </div>
        <div v-else class="tw-grid tw-grid-cols-6 tw-gap-4">
          <div v-for="graphic in graphics" :key="graphic.id" class="tw-cursor-pointer" @click="chooseGraphic(graphic)">
            <bz-aspect-view>
              <img :src="graphic.image" class="tw-w-full tw-h-full tw-object-cover" alt="image" />
            </bz-aspect-view>
            <span>{{ graphic.title }}</span>
          </div>
        </div>
      </div>
    </div>
  </modal>
</template>
<style>
@import '@splidejs/vue-splide/css';
</style>
