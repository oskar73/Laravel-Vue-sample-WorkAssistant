<script>
import { defineComponent } from 'vue'
import { cloneDeep } from 'lodash'
import BzArrowLeftIcon from '@/section-builder/components/icons/ArrowLeft.vue'
import BzCarousel from '@/section-builder/components/page/BzCarousel3d.vue'
import BzArrowRightIcon from '@/section-builder/components/icons/ArrowRight.vue'
import builderMixin from '@/section-builder/mixins/builderMixin'

export default defineComponent({
  name: 'BzFooter',
  components: {
    BzArrowRightIcon,
    BzCarousel,
    BzArrowLeftIcon
  },
  mixins: [builderMixin],
  props: {
    preview: {
      type: Boolean,
      default: false
    },
    viewOnly: {
      type: Boolean,
      default: false
    }
  },
  emits: ['click'],
  data() {
    return {
      changeSectionInSameCategory: false,
      carouselHeight: null,
      carouselWidth: null,
      startIndexForCarousel: null
    }
  },
  computed: {
    carouselActive() {
      return this.changeSectionInSameCategory && this.activePosition === 'footer' && this.carouselHeight && this.carouselWidth
    },
    isActive() {
      return this.activePosition === 'footer'
    }
  },
  methods: {
    handleSectionClick() {
      this.activePosition = 'footer'
      // clone handleSectionClick logic for section palette applying from PageContent.vue
      if (this.$store.state.previewPalette && this.appliedTo !== 'section') {
        return
      }
      if (this.activeSlider === 'theme' && this.appliedTo === 'section') {
        const position = 'footer'
        if (this.paletteAppliedSections[this.indexOfActivePage]) {
          this.paletteAppliedSections[this.indexOfActivePage].toggle(position)
        } else {
          this.paletteAppliedSections[this.indexOfActivePage] = [position]
        }
        this.paletteAppliedSections = cloneDeep(this.paletteAppliedSections)
      }
      this.$emit('click')
    },
    handleSlideClick() {
      this.changeSectionInSameCategory = false
    },
    handleAfterFooterSliderChange(index) {
      const newFooter = this.footerSections[index]
      this.footer.data.setting = this.mergeSectionSetting(newFooter, this.footer)
      this.footer.name = newFooter.name
    }
  }
})
</script>

<template>
  <section v-if="carouselActive">
    <bz-carousel
      :height="carouselHeight"
      :width="carouselWidth"
      :start-index="startIndexForCarousel"
      :sections="footerSections"
      :current-section="footer"
      :position="activePosition"
      :page-data="activePageData"
      :on-main-slide-click="handleSlideClick"
      @after-slide-change="handleAfterFooterSliderChange"
    />
  </section>
  <template v-else>
    <section :class="{ active: activePosition === 'footer' }" class="cursor-pointer" @click="handleSectionClick('footer')">
      <component :is="footer.name" :page-data="activePageData" position="footer" :preview="preview" :edit="!viewOnly" :properties="footer" />
    </section>
  </template>
</template>

<style scoped lang="scss">
</style>
