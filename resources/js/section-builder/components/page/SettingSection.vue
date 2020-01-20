<template>
  <div v-if="sectionData" id="m_quick_sidebar_tabs_messenger" class="tab-pane active" role="tabpanel">
    <div class="m-messenger m-messenger--message-arrow m-messenger--skin-light">
      <div class="m-list-timeline">
        <div class="m-list-timeline__group">
          <div class="m-list-timeline__heading text-capitalize mb-0">
            <div class="panel_tab_area">
              <a href="#" class="panel_tab_btn" :class="{ panel_tab_active: nav === 'setting' }" @click.prevent="nav = 'setting'">Settings</a>
              <a href="#" class="panel_tab_btn" :class="{ panel_tab_active: nav === 'bg' }" @click.prevent="nav = 'bg'">Background</a>
              <div class="clearfix" />
            </div>
          </div>
          <div class="panel_tab_content_section">
            <div class="w-100 px-3 pt-3">
              <div v-if="categorySections.length > 1" class="w-100">
                <h6>Layout</h6>
                <div class="layouts-wrapper">
                  <div class="back-button" @click.prevent="panelArrowAction('left')">
                    <arrow-back-icon fill-color="white" />
                  </div>
                  <div class="current-layout">{{ sectionData.data.setting.layout }}/{{ categorySections.length }}</div>
                  <div class="forward-button" @click.prevent="panelArrowAction('right')">
                    <arrow-forward-icon fill-color="white" />
                  </div>
                </div>
              </div>
            </div>

            <div id="setting_area" class="panel_tab_content_area" :class="{ area_active: nav === 'setting' }">
              <div id="setting_accordion" class="m-accordion m-accordion--default m-accordion--solid m-accordion--section m-accordion--toggle-arrow" role="tablist">
                <setting-alignment :setting="sectionData.data.setting" />

                <setting-elements v-if="sectionData.data.setting.hasOwnProperty('elements')" :setting="sectionData.data.setting" />

                <setting-layouts v-if="sectionData.data.setting.hasOwnProperty('layouts')" :setting="sectionData.data.setting" />

                <setting-list-elements v-if="sectionData.data.setting.hasOwnProperty('listElements')" :setting="sectionData.data.setting" />

                <setting-video v-if="sectionData.data.setting.hasOwnProperty('video')" :setting="sectionData.data.setting" />

                <setting-paypal v-if="sectionData.data.setting.hasOwnProperty('paypal')" :setting="sectionData.data.setting" />

                <setting-portfolio-module v-if="sectionData.data.setting.hasOwnProperty('portfolio') && isWebsite" :setting="sectionData.data.setting" />

                <setting-blog-module v-if="sectionData.data.setting.hasOwnProperty('blog') && isWebsite" :setting="sectionData.data.setting" />

                <setting-product-module v-if="sectionData.data.setting.hasOwnProperty('product') && isWebsite" :setting="sectionData.data.setting" />

                <setting-directory-module v-if="sectionData.data.setting.hasOwnProperty('listing') && isWebsite" :setting="sectionData.data.setting" />

                <setting-business-information v-if="sectionData.data.setting.hasOwnProperty('businessInformation')" :setting="sectionData.data.setting" />

                <setting-map v-if="sectionData.data.setting.hasOwnProperty('map')" :setting="sectionData.data.setting" />

                <setting-card v-if="sectionData.data.setting.hasOwnProperty('card')" :setting="sectionData.data.setting" />

                <setting-iframe v-if="sectionData.data.setting.hasOwnProperty('iframe')" :setting="sectionData.data.setting" />

                <setting-promotion v-if="sectionData.data.setting.hasOwnProperty('promotion')" :setting="sectionData.data.setting" />
              </div>
            </div>
            <div v-if="nav === 'bg'" id="background_area" class="panel_tab_content_area" :class="{ area_active: nav === 'bg' }">
              <setting-background v-model="sectionData.data.background" />
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div v-else class="d-flex flex-column align-items-center pb-5">
    <hr class="w-100 mt-0 mb-5" />
    <img :src="asset('assets/img/icons/select-item-to-edit.svg')" alt="select element" class="ml-4" />
    <span>Select an element to edit</span>
  </div>
</template>

<script>
import SettingPortfolioModule from './SettingPortfolioModule.vue'
import SettingDirectoryModule from './SettingDirectoryModule.vue'
import SettingProductModule from './SettingProductModule.vue'
import SettingBlogModule from './SettingBlogModule.vue'
import SettingAlignment from './SettingAlignment.vue'
import SettingPaypal from './SettingPaypal.vue'
import SettingElements from './SettingElements.vue'
import SettingLayouts from './SettingLayouts.vue'
import SettingMap from './SettingMap.vue'
import SettingListElements from './SettingListElements.vue'
import SettingBusinessInformation from './SettingBusinessInformation.vue'
import SettingCard from './SettingCard.vue'
import SettingIframe from './SettingIframe.vue'
import SettingPromotion from './SettingPromotion.vue'
import SettingBackground from './SettingBackground.vue'
import builderMixin from '../../mixins/builderMixin'
import SettingVideo from './SettingVideo.vue'
import ArrowBackIcon from '../icons/ArrowBack.vue'
import ArrowForwardIcon from '../icons/ArrowForward.vue'
import { cloneDeep, merge } from 'lodash'
import eventBus from '@/public/eventBus'

export default {
  name: 'SettingSection',
  components: {
    ArrowForwardIcon,
    ArrowBackIcon,
    SettingVideo,
    SettingBackground,
    SettingPromotion,
    SettingIframe,
    SettingCard,
    SettingBusinessInformation,
    SettingListElements,
    SettingMap,
    SettingLayouts,
    SettingElements,
    SettingPaypal,
    SettingAlignment,
    SettingBlogModule,
    SettingProductModule,
    SettingDirectoryModule,
    SettingPortfolioModule
  },
  mixins: [builderMixin],
  data () {
    return {
      nav: 'setting', // 'setting','bg'
      flagSectionSelected: false,
      timer: null,
      updating: false
    }
  },
  computed: {
    categorySections () {
      if (this.activePosition === 'header') {
        return this.headerSections
      }

      if (this.activePosition === 'footer') {
        return this.footerSections
      }

      if (this.activeSection && this.activeSection.category) {
        return this.activeSection.category.sections
      }
      return []
    },
    sectionData: {
      get () {
        return this.activeSection
      },
      set (val) {
        this.activeSection = val
      }
    }
  },
  watch: {
    sectionData: {
      deep: true,
      handler (val, oldVal) {
        if (val === oldVal && !this.updating) {
          if (this.timer) {
            clearTimeout(this.timer)
            this.timer = null
          }
          this.timer = setTimeout(() => {
            eventBus.$emit('section:update.settings')
          }, 100)
        }
      }
    }
  },
  updated () {
    this.updating = true
    setTimeout(() => {
      this.updating = false
    }, 100)
  },
  mounted () {
    eventBus.$on('panelArrowAction', (direction) => {
      this.panelArrowAction(direction)
    })
  },
  methods: {
    getSectionsInCategory (sectionName) {
      let category = this.allCategories.find((item) => sectionName.toLowerCase().includes(item.name.replaceAll(/(\s+)?(\/)?/gi, '').toLowerCase()))
      if (!category) {
        category = this.moduleCategories.find((item) => sectionName.toLowerCase().includes(item.name.replaceAll(/(\s+)?(\/)?/gi, '').toLowerCase()))
      }
      return category.sections
    },
    panelArrowAction (direction) {
      const categorySections = this.getSectionsInCategory(this.sectionData.name)
      const currentSectionIndex = categorySections.findIndex((item) => item.data.setting.layout === this.sectionData.data.setting.layout)
      let newIndex
      if (direction === 'left') {
        newIndex = currentSectionIndex - 1
      } else {
        newIndex = currentSectionIndex + 1
      }

      if (newIndex < 0) {
        newIndex = categorySections.length - 1
      } else if (newIndex > categorySections.length - 1) {
        newIndex = 0
      }
      const newSection = cloneDeep(categorySections[newIndex])
      this.sectionData.data.data = merge(this.sectionData.data.data, newSection.data.data)
      this.sectionData.data.setting = newSection.data.setting
      this.sectionData.data.setting.visible = true
      // this.sectionData.data.setting = this.mergeSectionSetting(newSection, this.sectionData, ['layout', 'columns', 'alignments', 'column', 'alignment'])
      this.sectionData.name = newSection.name

      eventBus.$emit('SectionLayoutChanged')
    }
  }
}
</script>

<style lang="scss">
$active: #0076df;
$activeHover: #1187ef;

.layouts-wrapper {
  margin-top: 10px;
  margin-bottom: 10px;
  display: flex;
  align-items: center;

  .forward-button,
  .back-button {
    height: 30px;
    background-color: $active;
    width: 30px;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;

    &:hover {
      background-color: $activeHover;
    }
  }

  .forward-button {
    border-top-right-radius: 4px;
    border-bottom-right-radius: 4px;
  }

  .back-button {
    border-top-left-radius: 4px;
    border-bottom-left-radius: 4px;
  }

  .current-layout {
    height: 30px;
    width: calc(100% - 60px);
    background-color: #00000012;
    display: flex;
    justify-content: center;
    align-items: center;
  }
}

.alignment-wrapper {
  height: 35px;
  display: table;
  align-items: center;
  flex: 1;
  width: 100%;

  .align-left,
  .align-right,
  .align-alternate,
  .align-center {
    display: table-cell;
    background-color: #00000012;
    vertical-align: middle;
    text-align: center;

    &.active {
      background-color: $active;

      svg {
        color: white;
      }
    }
  }

  .align-left {
    border-top-left-radius: 4px;
    border-bottom-left-radius: 4px;
  }

  .align-right {
    border-top-right-radius: 4px;
    border-bottom-right-radius: 4px;
  }
}

.column-wrapper {
  height: 35px;
  display: table;
  align-items: center;
  flex: 1;
  width: 100%;

  .column-item {
    display: table-cell;
    background-color: #00000012;
    vertical-align: middle;
    text-align: center;
    cursor: pointer;

    &:first-child {
      border-top-left-radius: 4px;
      border-bottom-left-radius: 4px;
    }

    &:last-child {
      border-top-right-radius: 4px;
      border-bottom-right-radius: 4px;
    }

    &.active {
      background-color: $active;
      color: white;
    }
  }
}

.background-type {
  button {
    outline: none !important;
    border: none !important;
    background-color: #0000001a !important;

    &.active {
      background-color: $active !important;
      color: white !important;
    }
  }
}

.background-effect {
  background-color: #0000001a;
  padding: 18px;
  border-top: solid 1px #8080803f;
  border-bottom: solid 1px #8080803f;
}

.background-pattern {
  .col-3 {
    padding: 5px;
  }

  .pattern-item {
    border: solid 1px #8080803f;
    border-radius: 4px;
    cursor: pointer;

    &.active {
      border: solid 2px $active;
    }
  }
}

.background-video {
  .col-6 {
    padding: 5px;
  }

  .video-item {
    border: solid 1px #8080803f;
    border-radius: 4px;
    cursor: pointer;

    &.active {
      border: solid 2px $active;
    }
  }
}
</style>
