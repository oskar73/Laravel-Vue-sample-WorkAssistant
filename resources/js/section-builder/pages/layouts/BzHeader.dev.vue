<script>
import { defineComponent } from 'vue'
import { cloneDeep } from 'lodash'
import BzArrowLeftIcon from '@/section-builder/components/icons/ArrowLeft.vue'
import BzCarousel from '@/section-builder/components/page/BzCarousel3d.vue'
import BzArrowRightIcon from '@/section-builder/components/icons/ArrowRight.vue'
import builderMixin from '@/section-builder/mixins/builderMixin'

export default defineComponent({
  name: 'BzHeader',
  components: {
    BzArrowRightIcon,
    BzCarousel,
    BzArrowLeftIcon
  },
  mixins: [builderMixin],
  props: {
    preview: {
      type: Boolean,
      required: false
    },
    viewOnly: {
      type: Boolean,
      required: false
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
      return this.changeSectionInSameCategory && this.activePosition === 'header' && this.carouselHeight && this.carouselWidth
    },
    isActive() {
      return this.activePosition === 'header'
    }
  },
  methods: {
    handleSectionClick() {
      this.activePosition = 'header'
      // clone handleSectionClick logic for section palette applying from PageContent.vue
      if (this.$store.state.previewPalette && this.appliedTo !== 'section') {
        return
      }
      if (this.activeSlider === 'theme' && this.appliedTo === 'section') {
        const position = 'header'
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
    handleAfterHeaderSliderChange(index) {
      const newHeader = this.headerSections[index]
      this.header.data.setting = this.mergeSectionSetting(newHeader, this.header)
      this.header.name = newHeader.name
    }
  }
})
</script>

<template>
  <section v-if="carouselActive" class="cursor-pointer">
    <bz-carousel
      :height="carouselHeight"
      :width="carouselWidth"
      :start-index="startIndexForCarousel"
      :page-data="activePageData"
      :sections="headerSections"
      :position="activePosition"
      :current-section="header"
      :on-main-slide-click="handleSlideClick"
      @after-slide-change="handleAfterHeaderSliderChange"
    />
  </section>
  <section data-position="header" :class="{ active: isActive }" @click="handleSectionClick">
    <component :is="header.name" :page-data="activePageData" position="header" :preview="preview" :edit="!viewOnly" :properties="header" :view-only="viewOnly" />
  </section>
</template>

<style scoped lang="scss">
</style>
