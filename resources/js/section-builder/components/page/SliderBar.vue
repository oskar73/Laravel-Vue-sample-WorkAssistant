<template>
  <section>
    <div class="sidebar bg-white d-flex">
      <ul class="mb-0 pl-0 list-style-none flex-column item_list">
        <li class="side-item">
          <div class="item_div" :class="{ active: activeSlider === 'templates' }" @click.prevent="openTemplateSlider">
            <add-circle-icon :fill-color="activeSlider === 'templates' ? '#ffffff' : '#555555'" />
            <div class="text-capitalize mt-1">templates</div>
          </div>
        </li>
        <li v-if="activePage" class="side-item">
          <div class="item_div py-2" :class="{ active: activeSlider === 'myTemplates' }" @click.prevent="openMyTemplateSlider">
            <Bookmark :fill-color="activeSlider === 'myTemplates' ? '#ffffff' : '#555555'" />
            <div class="text-capitalize mt-1 text-center">My Templates</div>
          </div>
        </li>
        <li v-if="activePage" class="side-item">
          <div class="item_div" :class="{ active: activeSlider === 'theme' }" @click.prevent="openThemeSlider">
            <ColorLensIcon :fill-color="activeSlider === 'theme' ? '#ffffff' : '#555555'" />
            <div class="text-capitalize mt-1">themes</div>
          </div>
        </li>
        <li v-if="activePage" class="side-item">
          <div class="item_div" :class="{ active: activeSlider === 'pages' }" @click.prevent="openPageSlider">
            <insert-drive-file-icon :fill-color="activeSlider === 'pages' ? '#ffffff' : '#555555'" />
            <div class="text-capitalize mt-1">pages</div>
          </div>
        </li>
        <li class="side-item">
          <div class="item_div" :class="{ active: activeSlider === 'sections' }" @click.prevent="openSectionSlider">
            <add-circle-icon :fill-color="activeSlider === 'sections' ? '#ffffff' : '#555555'" />
            <div class="text-capitalize mt-1">sections</div>
          </div>
        </li>
        <li v-if="activePage" class="side-item">
          <div :class="{ active: activeSlider === 'settings' }" class="item_div" @click.prevent="openSettingSlider">
            <SettingIcon :fill-color="activeSlider === 'settings' ? '#ffffff' : '#555555'" />
            <div class="text-capitalize mt-1">settings</div>
          </div>
        </li>

        <!-- <hr />

        <li v-if="activePage" class="side-item">
          <div :class="{ active: activeSlider === 'settings' }" class="item_div" @click.prevent="openSectionEditor">
            <view-quilt-icon :fill-color="settingEditor === 'settings' ? '#ffffff' : '#555555'" />
            <small class="text-capitalize mt-1">Edit Section</small>
          </div>
        </li> -->
      </ul>
    </div>
    <template v-if="activePage">
      <template-list />
      <my-template-list />
      <theme-settings />
      <template-settings />
      <template-section-list v-if="isTemplate" />
      <section-list v-else />
      <page-setting />
    </template>
    <div class="close-panel-over-layer" :class="{ open: activeSlider, [activeSlider]: true }" @click="handleOverlayerClick"></div>
  </section>
</template>

<script>
import ColorLensIcon from '../icons/ColorLens.vue'
import Bookmark from '../icons/BookMark.vue'
import SettingIcon from '../icons/Setting.vue'
import AddCircleIcon from '../icons/AddCircle.vue'
import InsertDriveFileIcon from '../icons/InsertDriveFile.vue'
import TemplateSectionList from './TemplateSectionList.vue'
import TemplateSettings from './TemplateSettings.vue'
import ThemeSettings from './Theme/ThemeSettings.vue'
import PageSetting from './PagesSetting.vue'
import builderMixin from '../../mixins/builderMixin'
import TemplateList from './TemplateList.vue'
import MyTemplateList from './MyTemplateList.vue'
import eventBus from '@/public/eventBus'
import SectionList from '@/section-builder/components/page/SectionList.vue'

export default {
  components: {
    SectionList,
    TemplateList,
    MyTemplateList,
    TemplateSectionList,
    PageSetting,
    ThemeSettings,
    TemplateSettings,
    InsertDriveFileIcon,
    AddCircleIcon,
    SettingIcon,
    ColorLensIcon,
    Bookmark
  },
  mixins: [builderMixin],
  mounted() {
    /**
     * adds custom event handlers.
     * this is required to call builder functions from the section.
     * Section or element mixing doesn't include builder-related functions or variables to reduce build size.
     * So, we make the communication between theme through custom events
     */
    eventBus.$on('openSettingSlider', (params) => {
      this.openSettingSlider(...params)
    })
  },
  methods: {
    openSectionSlider() {
      if (this.activeSlider !== 'sections') {
        this.setOpenSlider({ sliderName: 'sections' })
        // this.appendSection()
      }
    },
    handleOverlayerClick() {
      if (this.activeSlider === 'pages' || this.activeSlider === 'sections') {
        this.closeSlider()
      }
    },
    openSectionEditor() {
      this.settingEditor = 'section'
      this.toggleSettingPanel(true)
    }
  }
}
</script>
<style lang="scss">
$active: #0076df;
.close-panel-over-layer {
  height: 100vh;
  position: fixed;
  top: 0;
  content: '';
  display: none;
  z-index: 5;
  cursor: pointer;

  &.open {
    display: block;
  }

  &.sections {
    left: 570px;
    width: calc(100vw - 570px);
    display: none;
  }

  &.pages {
    left: 370px;
    width: calc(100vw - 370px);
  }

  &.theme {
    left: 470px;
    width: calc(100vw - 470px);
    display: none;
  }

  &.settings {
    left: 970px;
    width: calc(100vw - 970px);
  }
}

.sidebar {
  height: 100vh;
  width: 70px;
  position: fixed;
  top: 60px;
  left: 0;
  box-sizing: border-box;
  z-index: 52;

  .item_list {
    box-shadow: rgb(0 0 0 / 10%) 1px 0 0 !important;
    width: 100%;

    .side-item {
      width: 100%;

      .item_div {
        height: 70px;
        display: flex;
        color: #555555;
        background-color: transparent;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        font-size: 12px;
        width: 100%;
        cursor: pointer;

        i {
          font-size: 18px;
        }

        &.active {
          background-color: $active;
          color: white;
        }
      }
    }
  }
}
</style>
