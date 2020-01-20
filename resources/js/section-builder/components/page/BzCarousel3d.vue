<template>
  <div class="bz-el-carousel-3d-root">
    <carousel
      ref="carousel"
      :start-index="startIndex"
      :animation-speed="600"
      :controls-visible="true"
      :on-main-slide-click="onMainSlideClick"
      :height="height"
      :width="width"
      @after-slide-change="handleSliderChange"
    >
      <slide v-for="(categorySection, index) of sections" :key="index" :index="index">
        <div style="pointer-events: none" :style="{ width: width + 'px' }">
          <component :is="categorySection.name" :page-data="pageData" :properties="getProperties(categorySection)" :preview="true" :position="position" />
        </div>
      </slide>
    </carousel>
  </div>
</template>

<script>
import { Carousel3d as Carousel, Slide } from 'vue3-carousel-3d'
import { mapMutations } from 'vuex'
import { cloneDeep } from 'lodash'
import builderMixin from '../../mixins/builderMixin'
import 'vue3-carousel-3d/dist/index.css'

export default {
  name: 'BzCarousel3d',
  components: {
    Carousel,
    Slide
  },
  mixins: [builderMixin],
  props: {
    onMainSlideClick: {
      type: Function,
      default: () => {}
    },
    height: {
      type: Number,
      default: 0
    },
    width: {
      type: Number,
      default: 0
    },
    startIndex: {
      type: Number,
      default: 0
    },
    sections: {
      type: Array,
      default: () => {
        return []
      }
    },
    pageData: {
      type: Object,
      default: () => {
        return {}
      }
    },
    position: {
      type: [Number, String],
      default: undefined
    },
    currentSection: {
      type: Object,
      default: () => {
        return {}
      }
    }
  },
  computed: {
    sectionContentStyle() {
      return {
        width: this.width + 'px',
        height: this.height + 'px'
      }
    },
    panelArrow() {
      return this.$store.state.panelArrow
    }
  },
  watch: {
    panelArrow(action) {
      if (action) {
        this.nextSlide(action)
        this.updateLayout('')
      }
    }
  },
  methods: {
    getProperties(categorySection) {
      return cloneDeep({
        ...categorySection,
        data: {
          ...categorySection.data,
          data: this.currentSection.data.data,
          setting: this.mergeSectionSetting(categorySection, this.currentSection),
          background: this.currentSection.data.background
        }
      })
    },
    handleSliderChange(e) {
      this.$emit('after-slide-change', e)
    },
    nextSlide(action) {
      if (action === 'right') {
        this.$refs.carousel.goNext()
      } else {
        this.$refs.carousel.goPrev()
      }
    },
    ...mapMutations({
      updateLayout: 'updateLayout'
    })
  }
}
</script>

<style lang="scss" scoped>
.bz-el-carousel-3d-root {
  .section-wrapper {
    pointer-events: none;
    position: relative;
    background-color: rebeccapurple;
    display: flex;
    justify-content: center;
    align-items: center;

    .section-content {
      position: absolute;
    }
  }
}
</style>
