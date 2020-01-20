<template>
  <div
    class="toggle_panel_area m-quick-sidebar m-quick-sidebar--tabbed m-quick-sidebar--skin-light p-0 bz-setting-panel handler tw-w-[320px]"
    :class="{ 'page-list-overlay': showPageListOverlap }"
  >
    <div class="px-3 py-3 d-flex align-items-center justify-content-between cursor-pointer">
      <h5 class="mb-0 tw-text-lg tw-font-bold">Section Settings</h5>
    </div>
    <div v-if="showPageList" class="m-quick-sidebar__content py-1">
      <page-list />
    </div>
    <div v-else class="m-quick-sidebar__content">
      <div v-if="settingEditor === editors.section" class="tab-content">
        <setting-section />
      </div>
    </div>
  </div>
</template>

<script>
import PageList from './PageList.vue'
import builderMixin from '../../mixins/builderMixin'
import SettingSection from './SettingSection.vue'

export default {
  name: 'SettingPanel',
  components: {
    SettingSection,
    PageList
  },
  mixins: [builderMixin],
  data() {
    return {
      editors: {
        section: 'section'
      }
    }
  },
  computed: {
    showPageList() {
      return this.activeSlider === 'theme' || (this.activeSlider === 'sections' && this.isWebsite)
    },
    showPageListOverlap() {
      return this.appliedTo === 'page' && this.activeSlider === 'theme' && this.paletteAppliedPages.length === 0 && this.isNewPaletteMode
    }
  },
  methods: {
    getSectionsInCategory(sectionName) {
      let category = this.allCategories.find((item) => sectionName.toLowerCase().includes(item.name.replaceAll(/(\s+)?(\/)?/gi, '').toLowerCase()))
      if (!category) {
        category = this.moduleCategories.find((item) => sectionName.toLowerCase().includes(item.name.replaceAll(/(\s+)?(\/)?/gi, '').toLowerCase()))
      }
      return category.sections
    }
  }
}
</script>

<style lang="scss">
$active: #0076df;
$activeHover: #1187ef;

.bz-setting-panel {
  position: fixed;
  top: 60px;
  right: 0;
  background-color: white;
  overscroll-behavior: contain;
  height: calc(100vh - 60px);
  overflow-y: auto;
  z-index: 2;

  &::-webkit-scrollbar {
    width: 2px !important;
  }
}

.page-list-overlay {
  outline: 10000px solid #0a0a0a55;
  z-index: 99999;
  height: 100%;
}
</style>
