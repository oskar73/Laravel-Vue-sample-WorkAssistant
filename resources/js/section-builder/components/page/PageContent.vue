<template>
  <div v-if="renderComponent" class="tw-bg-white tw-max-h-screen tw-overflow-auto page-content tw-pb-4"
       @scroll="handleScroll">
    <div v-if="template" class="sections-wrapper bz-page" :class="{ [activeSlider]: true }">
      <div ref="headerSectionRef" class="bz-content-section">
        <bz-header @click="handleHeaderSectionClick" />
      </div>

      <template v-if="activePage">
        <template v-if="visibleSections.length > 0">
          <template v-for="(section, position) in activeSections" :key="position">
            <div ref="sectionRef" class="bz-content-section"
                 :class="{ remove: removePosition === position, active: activePosition === position }">
              <div
                v-if="!section"
                class="empty-section tw-flex tw-flex-col tw-bg-white tw-justify-center tw-items-center tw-text-lg tw-border-blue-500 tw-border-2 tw-my-0.5"
                :class="{ active: showEmptySection, removing: removingEmptySection }"
              >
                <template v-if="isWebsite">
                  <p>To add, click on a section in the sections bar on the left.</p>
                  <button class="btn btn-outline-danger mt-5 tw-text-lg" @click="handleCloseEmptySection">Close</button>
                </template>
                <template v-else>
                  <div>To add, select category from list.</div>
                  <div class="tw-px-3 tw-mt-8 tw-w-80">
                    <bz-select v-model="category" placeholder="Section Category" :options="categories" :search="true">
                      <template #selected="{ selected, placeholder }">
                        <span>{{ selected?.name || placeholder }}</span>
                      </template>
                      <template #option="{ option }">
                        <span>{{ option.name }}</span>
                      </template>
                    </bz-select>
                    <div v-if="!!errorMessage"
                         class="tw-border tw-w-full tw-bg-rose-100 tw-border-rose-600 tw-rounded-md tw-px-4 tw-py-2 tw-my-2">
                      {{ errorMessage }}
                    </div>
                  </div>

                  <div class="w-100 tw-flex tw-justify-center tw-items-center py-2 tw-gap-2 tw-mt-6">
                    <button class="btn btn-outline-danger tw-text-lg" @click="handleCloseEmptySection">Close</button>
                    <button class="btn btn-info tw-text-lg tw-flex tw-items-center" @click="addSection">
                      <spinner v-if="loading" />
                      Add Section
                    </button>
                  </div>
                </template>
              </div>
              <template v-else-if="activePage.url === '/new-page' || isVisibleSection(section)">
                <section v-if="carouselActive(position)" class="bz-component-container cursor-pointer">
                  <bz-carousel
                    :height="carouselHeight"
                    :width="carouselWidth"
                    :start-index="startIndexForCarousel"
                    :sections="getSectionsInCategory(section.category_id)"
                    :current-section="section"
                    :position="activePosition"
                    :page-data="activePageData"
                    :on-main-slide-click="handleSlideClick"
                    @after-slide-change="handleAfterSliderChange"
                  />
                </section>
                <section
                  v-else
                  :ref="'componentContainer' + position"
                  class="bz-component-container"
                  :data-position="position"
                  :class="{ active: activePosition === position }"
                  @click="handleSectionClick(position)"
                >
                  <div class="w-100 h-100" :class="{ 'tw-overflow-hidden': changeSectionInSameCategory || deleting }">
                    <component :is="section.name" :page-data="activePageData" :edit="isSectionEditable"
                               :properties="section" :position="position" />
                  </div>
                  <div
                    v-if="section.data.setting.isNew"
                    class="tw-absolute tw-rounded-full tw-top-2 tw-left-2 tw-border-[#86bc42] tw-bg-[#86bc4211] tw-border tw-text-base tw-px-4 tw-py-2 tw-text-[#86bc42] tw-flex tw-items-center"
                  >
                    New
                  </div>
                </section>
              </template>
            </div>
          </template>
        </template>
        <div v-else class="tw-flex-1 tw-flex tw-flex-col tw-justify-center tw-items-center tw-text-xl">
          <div class="tw-cursor-pointer" @click="activeSlider = 'sections'">
            <i class="mdi mdi-plus-circle-outline tw-text-blue-500"></i>
          </div>
          <p>Choose sections to the left to add</p>
        </div>
      </template>
      <div v-else class="tw-flex-1 tw-flex tw-flex-col tw-justify-center tw-items-center">
        <h4 class="tw-text-4xl tw-font-bold">404</h4>
        Page Not found
      </div>

      <div ref="footerSectionRef" class="bz-content-section">
        <bz-footer @click="handleFooterSectionClick" />
      </div>
    </div>
    <div v-if="activeSection" ref="contentOverLayer" class="active-content-overlay" :style="overlayStyle"
         :class="{ deleting }">
      <div v-if="typeof activePosition === 'number' && !changeSectionInSameCategory" class="control_box">
        <span v-if="activePosition >= 0" class="arrow_top control_item" @click.prevent="upward()">
          <bz-arrow-up-icon fill-color="white" />
        </span>
        <span v-if="activePosition < activeSections.length - 1" class="arrow_bottom mx-1 control_item"
              @click.prevent="downward()">
          <bz-arrow-down-icon fill-color="white" />
        </span>
        <span v-if="activePage.type !== 'new-page'" class="arrow_remove control_item"
              @click.prevent="remove(activePosition)">
          <i class="mdi mdi-delete tw-text-white"></i>
        </span>
        <span v-else class="arrow_remove control_item" @click.prevent="remove(activePosition)">
          <i class="mdi tw-text-white"
             :class="[activeSection.data.setting.visible ? 'mdi-eye-outline' : 'mdi-eye-off-outline']"></i>
        </span>
      </div>
      <template v-if="typeof activePosition === 'number' && !changeSectionInSameCategory">
        <div class="add_section top_add" @click.prevent.stop="prependSection(activePosition)">
          <i class="mdi mdi-plus"></i>
          <span class="add_section_txt">Add Section</span>
        </div>
        <div class="add_section bottom_add" @click.prevent.stop="appendSection(activePosition + 1)">
          <i class="mdi mdi-plus"></i>
          <span class="add_section_txt"> Add Section </span>
        </div>
      </template>
      <span v-if="!changeSectionInSameCategory" class="left_arrow arrow_item" @click.prevent="arrowAction('left')">
        <i class="mdi mdi-chevron-left"></i>
      </span>
      <span v-if="!changeSectionInSameCategory" class="right_arrow arrow_item" @click.prevent="arrowAction('right')">
        <i class="mdi mdi-chevron-right"></i>
      </span>
    </div>
  </div>
</template>
<script>
import { mapMutations } from 'vuex'
import ZoomOut from '../icons/ZoomOut.vue'
import BzCarousel from './BzCarousel3d.vue'
import BzArrowRightIcon from '../icons/ArrowRight.vue'
import BzArrowLeftIcon from '../icons/ArrowLeft.vue'
import BzArrowDownIcon from '../icons/ArrowDown.vue'
import BzArrowUpIcon from '../icons/ArrowUp.vue'
import builderMixin from '../../mixins/builderMixin'
import rerenderMixin from '@/section-builder/mixins/rerenderMixin'
import { cloneDeep } from 'lodash'
import eventBus from '@/public/eventBus'
import BzSelect from '@/public/BzSelect.vue'
import Spinner from '@/public/Spinner.vue'

export default {
  components: {
    BzArrowUpIcon,
    BzArrowDownIcon,
    BzArrowLeftIcon,
    BzArrowRightIcon,
    BzCarousel,
    ZoomOut,
    BzSelect,
    Spinner
  },
  mixins: [builderMixin, rerenderMixin],
  data() {
    return {
      changeSectionInSameCategory: false,
      carouselHeight: 0,
      carouselWidth: 0,
      removePosition: null,
      deleting: false,
      startIndexForCarousel: 0,
      scale: 1,
      zoom: 1,
      settingExceptionFields: ['columns', 'alignments', 'layout', 'column', 'alignment'],
      overlayStyle: {
        display: 'none'
      },
      loading: false,
      category: null,
      errorMessage: '',
      categories: [],
      removingEmptySection: false
    }
  },
  computed: {
    isSectionEditable() {
      return this.activeSlider === null
    },
    activeBackground: {
      get() {
        return this.activeSection.data.background || {}
      },
      set(value) {
        this.$store.commit('updateBackground', value)
      }
    }
  },
  watch: {
    panelArrow(action) {
      if (action && !this.carouselActive(this.activePosition)) {
        this.arrowAction(action)
        this.updateLayout('')
      }
    },
    activePosition() {
      this.updateOverlayStyle()
    },
    activeSlider: {
      immediate: true,
      handler() {
        this.animate()
      }
    },
    visibleSections: {
      immediate: true,
      handler() {
        this.$nextTick(() => {
          this.scrollToActiveSection()
        })
      }
    }
  },
  mounted() {
    eventBus.$on('scrollToActiveSection', (position) => {
      this.scrollToActiveSection(position)
    })
    eventBus.$on('ToggleSectionVisible', (position) => {
      if (this.activeSections[position].data.setting.visible) {
        this.remove(position)
      } else {
        this.activeSections[position].data.setting.visible = true
        this.activePosition = position
        this.scrollToActiveSection(position)
      }
    })
  },
  methods: {
    handleHeaderSectionClick() {
      this.handleCloseEmptySection()
      this.$nextTick(() => {
        this.updateOverlayStyle()
      })
    },
    handleFooterSectionClick() {
      this.handleCloseEmptySection()
      this.$nextTick(() => {
        this.updateOverlayStyle()
      })
    },
    updateOverlayStyle() {
      let activeSection = null
      if (typeof this.activePosition === 'number') {
        activeSection = this.$refs.sectionRef?.[this.activePosition]
      }

      if (this.activePosition === 'header') {
        activeSection = this.$refs.headerSectionRef
      }

      if (this.activePosition === 'footer') {
        activeSection = this.$refs.footerSectionRef
      }

      if (activeSection) {
        const rect = activeSection.getBoundingClientRect()
        const top = Math.max(rect.top, 0)
        const bottom = Math.min(rect.bottom, activeSection.ownerDocument.body.clientHeight)
        const height = Math.abs(top - bottom)
        if (rect.bottom > 0 && height > 20 && top < activeSection.ownerDocument.body.clientHeight - 20) {
          this.overlayStyle.top = top + 'px'
          this.overlayStyle.height = height + 'px'
          this.overlayStyle.display = 'block'
        } else {
          this.overlayStyle.display = 'none'
        }
      }
    },
    handleScroll(e) {
      this.updateOverlayStyle()
      if (window.bzElementEditor) {
        window.bzElementEditor.unmount()
        window.bzElementEditor = null
      }
    },
    animate() {
      this.$nextTick(() => {
        setTimeout(() => {
          eventBus.$emit('scrollToActiveSection')
        }, 100)
      })
    },
    handleSlideClick() {
      this.changeSectionInSameCategory = false
      this.scrollToActiveSection()
    },
    handleSectionClick(position) {
      this.updateOverlayStyle()
      if (this.activePosition !== position) {
        this.activePosition = position
        this.changeSectionInSameCategory = false
      }
      /**
       * Disable applying theme with theme preview mode.
       */
      if (this.$store.state.previewPalette && this.appliedTo !== 'section') {
        return
      }
      if (this.activeSlider === 'theme' && this.appliedTo === 'section') {
        if (this.paletteAppliedSections[this.indexOfActivePage]) {
          this.paletteAppliedSections[this.indexOfActivePage].toggle(position)
        } else {
          this.paletteAppliedSections[this.indexOfActivePage] = [position]
        }
        this.paletteAppliedSections = cloneDeep(this.paletteAppliedSections)
      }

      if (this.activeSections[position].data.setting.isNew) {
        delete this.activeSections[position].data.setting.isNew
      }
    },
    carouselActive(position) {
      return this.changeSectionInSameCategory && this.activePosition === position && this.carouselHeight && this.carouselWidth
    },
    getSectionsInCategory(categoryId) {
      let category = this.allCategories.find((item) => item.id === categoryId)
      if (!category) {
        category = this.moduleCategories.find((item) => item.id === categoryId)
      }
      if (category) {
        return category.sections || []
      } else {
        console.error('Category does not exist')
      }
    },
    arrowAction(action) {
      if (!this.changeSectionInSameCategory) {
        if (typeof this.activePosition === 'number') {
          if (this.activePosition < 0 || this.activePosition >= this.activeSections.length) return

          this.scrollToActiveSection()
          const position = this.activePosition
          const sectionsInCategory = this.getSectionsInCategory(this.activeSection.category_id)

          if (action === 'right') {
            this.startIndexForCarousel = (this.activeSection.data.setting.layout || 1) % sectionsInCategory.length
            this.handleAfterSliderChange(this.startIndexForCarousel)
          } else {
            this.startIndexForCarousel = ((this.activeSection.data.setting.layout || 1) + sectionsInCategory.length - 2) % sectionsInCategory.length
            this.handleAfterSliderChange(this.startIndexForCarousel)
          }

          this.carouselHeight = this.$refs['componentContainer' + position]?.[0]?.clientHeight || 0
          this.carouselWidth = this.$refs['componentContainer' + position]?.[0]?.clientWidth || 0
          if (this.carouselHeight < 800) {
            this.carouselHeight = 800
          }

          this.changeSectionInSameCategory = true
        } else {
          eventBus.$emit('panelArrowAction', action)
        }
      }
    },
    handleAfterSliderChange(index) {
      const categorySections = this.getSectionsInCategory(this.activeSection.category_id)
      const newSection = categorySections[index]
      const newSetting = this.mergeSectionSetting(newSection, this.activeSection, this.settingExceptionFields)
      newSetting.visible = true
      this.activeSection.data.setting = cloneDeep(newSetting)
      this.activeSection.name = newSection.name

      this.$nextTick(() => {
        this.updateOverlayStyle()
      })
    },
    upward() {
      const position = this.activePosition

      if (position === undefined || position <= 0 || typeof position === 'string') {
        return
      }

      const targetPosition = this.getPreviousVisiblePosition(position)
      console.log('upward.activePosition', position)
      console.log('upward.targetPosition', targetPosition)
      const activeSection = this.$refs.sectionRef[position]
      const targetSection = this.$refs.sectionRef[targetPosition]
      this.exchangeSection(activeSection, targetSection)

      this.$refs.contentOverLayer.style.transition = '0.5s'
      this.$refs.contentOverLayer.style.transform = `translateY(-${targetSection?.clientHeight || 0}px)`

      setTimeout(() => {
        this.swapSection([position, targetPosition])
        this.clearTransition(activeSection)
        this.clearTransition(targetSection)

        this.$refs.contentOverLayer.style.transition = ''
        this.$refs.contentOverLayer.style.transform = ''

        this.$nextTick(() => {
          this.scrollToActiveSection()
        })
      }, 510)
    },
    downward() {
      const position = this.activePosition

      if (position === undefined || position >= this.activeSections.length || typeof position === 'string') {
        return
      }

      const targetPosition = this.getNextVisiblePosition(position)
      if (targetPosition > -1) {
        const activeSection = this.$refs.sectionRef[position]
        const targetSection = this.$refs.sectionRef[targetPosition]
        this.exchangeSection(targetSection, activeSection)

        this.$refs.contentOverLayer.style.transition = '0.5s'
        this.$refs.contentOverLayer.style.transform = `translateY(${targetSection?.clientHeight || 0}px)`

        setTimeout(() => {
          this.swapSection([position, targetPosition])
          this.clearTransition(activeSection)
          this.clearTransition(targetSection)

          this.$refs.contentOverLayer.style.transition = ''
          this.$refs.contentOverLayer.style.transform = ''

          this.$nextTick(() => {
            this.scrollToActiveSection()
          })
        }, 510)
      }
    },
    clearTransition(section) {
      section.style.transition = ''
      section.style.transform = ''
    },
    exchangeSection(bottomSection, topSection) {
      bottomSection.style.transition = '0.5s'
      bottomSection.style.transform = `translateY(-${topSection?.clientHeight || 0}px)`
    },
    remove(position) {
      if (position === undefined || typeof position === 'string' || position < 0 || position >= this.activeSections.length) {
        return
      }

      if (this.activePage.type === 'new-page') {
        this.activeSections[position].data.setting.visible = !this.activeSections[position].data.setting.visible
        eventBus.$emit('section:invisible')
        return
      }

      this.deleting = true
      this.removePosition = position

      console.log('remove.removePosition', position)

      setTimeout(() => {
        this.removePosition = null
        this.activeSections[position].data.setting.visible = false
        this.deleting = false
        eventBus.$emit('section:invisible')
        this.activePosition = this.getNextVisiblePosition(position)
      }, 450)
    },
    getNextVisiblePosition(position) {
      if (this.activePage.type === 'new-page') {
        return position + 1
      } else {
        return this.activeSections.findIndex((sec, index) => index > position && sec?.data.setting.visible)
      }
    },
    getPreviousVisiblePosition(position) {
      if (position === 0) return -1
      if (this.activePage.type === 'new-page') {
        return position - 1
      } else {
        return this.activeSections.findLastIndex((sec, index) => index < position && sec?.data.setting.visible)
      }
    },
    scrollToActiveSection(sectionIndex) {
      const position = sectionIndex === undefined ? this.activePosition : sectionIndex
      let element = null
      if (typeof position === 'number') {
        element = this.$refs.sectionRef?.[position]
      } else if (position === 'header') {
        element = this.$refs.headerSectionRef
      } else if (position === 'footer') {
        element = this.$refs.footerSectionRef
      }

      if (element) {
        element.scrollIntoView({
          block: 'center',
          behavior: 'smooth'
        })
      }
    },
    isVisibleSection(section) {
      if (!section.data.setting.visible) return false

      if (section.category.module) {
        return this.modules.activeModules?.includes(section.category.module)
      }

      return true
    },
    handleCloseEmptySection() {
      this.removingEmptySection = true
      setTimeout(() => {
        this.removeEmptySection()
        this.activeSlider = null
        this.removingEmptySection = false
      }, 350)
    },
    ...mapMutations({
      removeSection: 'removeSection',
      swapSection: 'swapSection',
      updateLayout: 'updateLayout'
    })
  }
}
</script>

<style lang="scss">
$active: #0076df;
$dark_active: #0067c1;
$danger: darkred;

.page-content::-webkit-scrollbar {
  width: 0;
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

.bz-content-section {
  position: relative;
  height: min-content;

  &.remove {
    animation-name: remove;
    animation-duration: 0.5s;
  }

  @keyframes remove {
    from {
      height: 500px;
    }
    to {
      height: 0;
    }
  }

  &.bz-selected::after {
    content: '';
    display: block;
    position: absolute;
    box-shadow: rgb(0 0 0 / 24%) 0 1px 6px 0;
    pointer-events: none;
    z-index: 10;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    border: 3px solid rgb(0, 118, 223);
  }

  .active-content-overlay {
    &:before {
      content: '';
      position: absolute;
      display: block;
      box-shadow: rgb(0 0 0 / 24%) 0 1px 6px 0;
      pointer-events: none;
      border: 1px solid white;
      outline: rgb(0, 118, 223) solid 3px;
      height: calc(100% - var(--next-decorator-offset, 4px));
      inset: 0;
      z-index: 2;
    }

    .arrow_item {
      position: absolute;
      top: calc(50% - 12px);
      transform: translate(-50%, 0);
      border-radius: 50%;
      background-color: $active;
      display: flex;
      align-items: center;
      justify-content: center;
      cursor: pointer;

      * {
        color: white !important;
      }

      &:hover {
        background-color: $dark_active;
      }

      &.left_arrow {
        left: -20px;
      }

      &.right_arrow {
        right: -45px;
      }
    }

    .add_section {
      position: absolute;
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
      position: absolute;
      top: 10px;
      right: 15px;
      display: flex;
      background-color: $active;
      border-radius: 30px;
      z-index: 9;

      .control_item {
        display: flex;
        align-items: center;
        justify-content: center;
        background-color: $active;
        width: 30px;
        border-radius: 50%;
        height: 30px;
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
}
</style>
