<template>
  <div class="sections_area z-index-999" :class="{ active: activeSlider === 'sections' }">
    <div class="section_heading py-2 px-3">
      <div class="tw-flex tw-items-center">
        <div class="col-4">
          <h5 class="mb-0 text-dark"><b>Sections</b></h5>
        </div>
        <div class="col-6">
          <input v-model="sectionSearch" type="text" class="form-control tw-w-full" placeholder="Search sections" />
        </div>
        <div class="col-2 text-right">
          <div class="text-dark cursor-pointer" @click.prevent="closeSlider()">
            <i class="mdi mdi-close tw-text-xl"></i>
          </div>
        </div>
      </div>
    </div>
    <div v-if="activeSlider === 'sections'" class="section_content h-100 d-flex">
      <div class="section_category_area w-50 p-3 preview_content_ul custom-scroll-h scroll-w-5" style="overscroll-behavior: contain">
        <div class="tw-flex tw-items-center tw-justify-between tw-pb-2">
          <p class="heading mb-0 fs-18px font-weight-bold">All Sections</p>
          <small class="tw-underline tw-cursor-pointer" @click="handleHideAll">Hide All</small>
        </div>
        <ul class="ml-0 mb-0 list-style-none">
          <!-- <li
            class="tw-flex tw-items-center tw-gap-1 tw-whitespace-nowrap tw-p-1 tw-border tw-rounded tw-bg-blue-50 tw-shadow tw-mb-2 tw-cursor-pointer hover:tw-bg-blue-100"
            @click="handleNewSection"
          >
            <img :src="asset('assets/img/editor/icons/plus.svg')" class="tw-w-4 tw-h-4" />
            <div>New Section</div>
          </li> -->
          <TemplateSectionItem :active="activePosition === 'header'" :visible="true" name="Header" :hidden-visible="true" @click="handleHeaderCategoryClick" />
          <TemplateSectionItem :active="activePosition === 'footer'" :visible="true" name="Footer" :hidden-visible="true" @click="handleFooterCategoryClick" />
          <template v-for="(section, key) in activeSections">
            <TemplateSectionItem
              v-if="section"
              :key="section.id"
              :active="activePosition === key"
              :visible="section.data.setting.visible"
              :name="section.category.name"
              @toggle="toggleVisible(key)"
              @click="handleCategoryClick(key)"
            />
          </template>
          <!-- <li
            class="tw-flex tw-items-center tw-gap-1 tw-whitespace-nowrap tw-p-1 tw-border tw-rounded tw-bg-blue-50 tw-shadow tw-mb-2 tw-cursor-pointer hover:tw-bg-blue-100"
            @click="handleNewSection"
          >
            <img :src="asset('assets/img/editor/icons/plus.svg')" class="tw-w-4 tw-h-4" />
            <div>New Section</div>
          </li> -->
        </ul>
      </div>
      <div class="section_category_preview_area w-100">
        <div class="ml-0 mb-0 p-3 list-style-none preview_content_ul custom-scroll-h scroll-w-5" style="overscroll-behavior: contain">
          <template v-if="categorySections.length">
            <div v-for="(section, key3) in categorySections" :key="key3">
              <div ref="sectionPreView" class="section_preview_item cursor-pointer" :class="{ active: isSectionActive(section) }" @click.prevent.stop="handleSectionClick(section)">
                <div ref="sectionBase" class="section-preview-base bz-page" style="pointer-events: none">
                  <component :is="section.name" :position="activePosition" :preview="true" :page-data="activePageData" :properties="getSectionData(section)" />
                </div>
              </div>
            </div>
          </template>
          <template v-else>
            <li class="w-100">
              <span>No sections</span>
            </li>
          </template>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import builderMixin from '../../mixins/builderMixin'
import TemplateSectionItem from './TemplateSectionItem.vue'
import { cloneDeep, merge } from 'lodash'
import eventBus from '@/public/eventBus'
import 'vue3-toastify/dist/index.css'

export default {
  components: {
    TemplateSectionItem
  },
  mixins: [builderMixin],
  data () {
    return {
      restCategories: [],
      categorySections: [],
      activeCategory: null,
      selectedSection: {},
      sectionSearch: ''
    }
  },
  watch: {
    activeCategory: {
      deep: true,
      handler (val) {
        if (val) {
          this.categorySections = val.sections || []
        }
      }
    },
    categorySections: {
      deep: true,
      handler (value) {
        if (value.length && this.activeSlider) {
          this.renderSections()
        }
      }
    },
    activeSlider (value) {
      if (value === 'sections') {
        this.renderSections()
      }
    },
    // watcher of active section
    activePosition: {
      deep: true,
      handler (value) {
        if (value === 'header') {
          this.handleHeaderCategoryClick()
        }
        if (value === 'footer') {
          this.handleFooterCategoryClick()
        }
        if (value !== undefined && value !== null) {
          this.handleCategoryClick(value)
        }
      }
    }
  },
  mounted () {
    // const recommendedCategories = []
    // const restCategories = []
    // window.$.each(this.allCategories, function (key, item) {
    //   if (item.recommended) {
    //     recommendedCategories.push(item)
    //   } else {
    //     restCategories.push(item)
    //   }
    // })
    // this.recommendedCategories = recommendedCategories
    // this.restCategories = restCategories
    if (this.activeSection) {
      this.activeCategory = this.allCategories.find((cat) => this.activeSection.name.startsWith(cat.name))
    }

    if (!this.activeCategory) {
      this.activeCategory = this.allCategories.find((cat) => cat.name === 'Header')
    }
  },
  methods: {
    toggleVisible (position) {
      // if (this.activeSections[position].category.module && !this.modules.activeModules.includes(this.activeSections[position].category.module)) {
      //   return toast.warning('Please activate the necessary module to access this section!')
      // }
      eventBus.$emit('ToggleSectionVisible', position)
    },
    handleNewSection () {
      this.$store.commit('openModal', {
        name: 'newSectionModal'
      })
    },
    handleHideAll () {
      if (this.visibleSections.length > 0) {
        this.activeSections.forEach((section) => {
          section.data.setting.visible = false
        })
      }
    },
    getSectionData (section) {
      if (this.activeSection) {
        const sectionData = cloneDeep(this.activeSection.data)
        const z = {
          ...section,
          data: {
            ...sectionData,
            items: section.data.data.items,
            setting: merge(sectionData.setting, section.data.setting)
          }
        }
        return z
      }
      return section
    },
    renderSections () {
      this.$nextTick(() => {
        if (typeof this.$refs.sectionBase !== 'undefined') {
          this.$refs.sectionBase.forEach((item, index) => {
            if (item) {
              const sectionPreView = this.$refs.sectionPreView[index]
              new ResizeObserver(function (entries) {
                const rect = entries[0].contentRect
                sectionPreView.style.height = rect.height * 0.214285 + 'px'
              }).observe(item)
            }
          })
        }
      })
    },
    handleHeaderCategoryClick () {
      if (this.activePosition !== 'header') {
        this.activePosition = 'header'
      }
      this.activeCategory = this.allCategories.find((cat) => cat.name === 'Header')
      eventBus.$emit('scrollToActiveSection', 'header')
    },
    handleFooterCategoryClick () {
      if (this.activePosition !== 'footer') {
        this.activePosition = 'footer'
      }
      this.activeCategory = this.allCategories.find((cat) => cat.name === 'Footer')
      eventBus.$emit('scrollToActiveSection', 'footer')
    },
    handleCategoryClick (position) {
      if (
        typeof position === 'string' ||
        position === undefined ||
        position < 0 ||
        position >= this.activeSections.length
      ) {
        return
      }

      console.log('handleTemplateSectionCategoryClick.position', position)

      const section = this.activeSections[position]
      if (section && (section.data.setting.visible || this.activePage.type === 'new-page')) {
        if (this.activePosition !== position) {
          this.activePosition = position
          this.activeCategory = this.allCategories.find((cat) => cat.id === section.category_id)
        }
        this.activePosition = position
        eventBus.$emit('scrollToActiveSection', position)
      }
    },
    isSectionActive (section) {
      if (section.name.includes('Header')) {
        return section.name === this.header.name
      }
      if (section.name.includes('Footer')) {
        return section.name === this.footer.name
      }
      if (this.activeSection) {
        return this.activeSection.name === section.name
      } else {
        return this.selectedSection.name === section.name
      }
    },
    handleSectionClick (section) {
      this.selectedSection = section
      if (this.activeSection) {
        // this.activeSection.data.setting = this.mergeSectionSetting(section, this.activeSection, ['columns', 'alignments', 'layout'])
        // this.activeSection.name = section.name
        const newSection = cloneDeep(section)
        this.activeSection.data.data = merge(this.activeSection.data.data, newSection.data.data)
        this.activeSection.data.setting = newSection.data.setting
        this.activeSection.data.setting.visible = true
        this.activeSection.name = newSection.name
        eventBus.$emit('UpdateSettingPanel')
      }
    }
  }
}
</script>

<style lang="scss" scoped>
.sections_area {
  position: fixed;
  top: 60px;
  height: calc(100vh - 60px);
  background-color: #eff0f1;
  overflow: hidden;
  width: 500px;
  left: 70px;
  z-index: 3;
  transform: translateX(-570px);
  //transition: transform 0.3s linear;

  .bz-close-section-area {
    font-size: 26px;

    &::after {
      width: calc(100vw - 570px);
      height: 100vh;
      position: fixed;
      top: 0;
      left: 570px;
      background-color: red;
      display: none;
    }
  }

  &.active {
    transform: translateX(0);

    .bz-close-section-area {
      &::after {
        display: block;
      }
    }
  }

  .section_heading {
    border-bottom: 1px solid #d7d8d8;
  }

  .section_content {
    .preview_content_ul {
      max-height: calc(100vh - 130px);
    }

    .section_category_area {
      border-right: 1px solid #d7d8d8;

      .side_category_item {
        a {
          color: #000;
          padding: 5px 10px;
          display: block;
          border-radius: 4px;

          &:hover,
          &.active {
            text-decoration: none;
            background-color: #d9dee3;
          }
        }
      }
    }
  }
}

.section_preview_item {
  display: flex;
  border: 2px solid transparent;
  margin-bottom: 10px;
  box-shadow: none;
  transition: 0.3s;
  width: 300px;
  justify-content: center;
  align-items: center;
  overflow: hidden;
  position: relative;
  outline: solid 1px #8080807f;

  &.active {
    outline: solid 2px var(--bz-edit-active);
  }

  .section-preview-base {
    width: 1400px;
    position: absolute;
    display: flex;
    justify-content: center;
    align-items: center;
    transform: scale(0.21428571428571428);
    height: min-content;

    &.bz-page {
      min-height: unset;
    }
  }
}
</style>
