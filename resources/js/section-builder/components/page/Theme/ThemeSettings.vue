<template>
  <div class="custom-scroll-h theme_area z-index-999" :class="{ active: activeSlider === 'theme' }">
    <div class="pb-2 px-3 pt-4">
      <div class="row align-items-center">
        <div class="col-10 d-flex align-self-center">
          <h5 class="mb-0 text-dark"><b>Theme</b></h5>
        </div>
        <div class="col-2 text-right">
          <div class="bz-close-section-area text-dark cursor-pointer" @click="handleCloseSlider">
            <i class="mdi mdi-close"></i>
          </div>
        </div>
      </div>
    </div>
    <div class="px-3">
      <theme-item :theme-item="currentTheme" :theme-name="`Current Theme (${currentTheme.name})`" :show-control="false" :show-labels="true" />
    </div>
    <div v-if="activeSlider === 'theme'" class="w-100" style="height: calc(100% - 62px)">
      <div class="px-3">
        <div v-if="[modes.default, modes.applyTheme, modes.createNewTheme].includes(mode)" class="w-100 more-colors px-2">
          <div class="d-flex align-items-center justify-content-between py-2 cursor-pointer" @click="toggleShowThemes('system')">
            Bizinabox Themes
            <bz-arrow-up-icon v-if="showMoreColors" />
            <bz-arrow-down-icon v-else />
          </div>
          <template v-if="showThemes === 'system'">
            <themes v-model="editTheme" type="system" @select="handleThemeSelect" @edit="handleEdit" />
          </template>
        </div>
        <template v-if="[modes.default, modes.applyTheme, modes.createNewTheme].includes(mode) && isWebsite">
          <div class="w-100 more-colors px-2">
            <div class="d-flex align-items-center justify-content-between py-2 cursor-pointer" @click="toggleShowThemes('user')">
              My Themes
              <bz-arrow-up-icon v-if="showMoreColors" />
              <bz-arrow-down-icon v-else />
            </div>
            <template v-if="showThemes === 'user'">
              <hr />
              <themes v-model="editTheme" type="user" @select="handleThemeSelect" />
            </template>
          </div>
        </template>
        <div class="d-flex justify-content-end mt-2 tw-gap-x-1">
          <button v-if="[modes.editTheme, modes.default, modes.createNewTheme].includes(mode)" class="btn bz-btn-default tw-text-sm" @click="handleClickEditCurrentTheme">
            Edit Current Theme
          </button>
          <button v-if="[modes.editTheme, modes.default, modes.applyTheme].includes(mode)" class="btn btn-success tw-text-sm" @click="handleClickCreateNewTheme">
            Create New Theme
          </button>
          <button v-if="mode === modes.applyTheme" class="btn btn-primary tw-text-sm" @click="applyTheme">Apply Theme</button>
          <button v-if="mode === modes.applyTheme" class="btn btn-danger tw-text-sm" @click="resetTheme()">Cancel</button>
        </div>
        <CreateNewTheme
          v-if="[modes.editTheme, modes.editCurrentTheme, modes.createNewTheme].includes(mode)"
          ref="createThemeRef"
          v-model="editTheme"
          @cancel="handleCancelChanges"
          @create="handleCreateNewTheme"
          @update="handleUpdateTheme"
        />
      </div>
    </div>
  </div>
</template>

<script>
import fonts from '../../../data/fonts'
import builderMixin from '../../../mixins/builderMixin'
import { cloneDeep } from 'lodash'
import BzArrowDownIcon from '../../icons/ArrowDown.vue'
import BzArrowUpIcon from '../../icons/ArrowUp.vue'
import Themes from './Themes.vue'
import CreateNewTheme from './CreateNewTheme.vue'
import ThemeItem from './ThemeItem.vue'
import { storeTheme, updateTheme, updateTemplateTheme } from '../../../apis'

export default {
  name: 'ThemeSettings',
  components: {
    ThemeItem,
    CreateNewTheme,
    Themes,
    BzArrowUpIcon,
    BzArrowDownIcon
  },
  mixins: [builderMixin],
  data() {
    return {
      mode: 0,
      modes: {
        default: 0,
        createNewTheme: 1,
        editCurrentTheme: 2,
        editTheme: 3,
        applyTheme: 4
      },
      fonts,
      editor: null,
      fontType: 'title',
      fontStyleView: 'presets',
      showMoreColors: false,
      showSavedThemeColors: false,
      showCreateThemeColors: false,
      applyingThemeSetting: true,
      paletteImage: '',
      isThemeApplied: false,
      editTheme: null,
      isSaveTheme: false,
      showThemes: ''
    }
  },
  computed: {
    theme: {
      get() {
        return this.$store.state.theme
      },
      set(value) {
        this.$store.commit('updateTheme', value)
      }
    },
    themeCategories() {
      return this.$store.state.themeCategories
    },
    currentTheme() {
      return {
        id: this.template.data.theme.id || null,
        categoryId: this.template.data.theme.category_id,
        name: this.template.data.theme.name || 'Current Theme',
        data: this.template.data.theme
      }
    }
  },
  watch: {
    editTheme(v) {
      if (v) {
        if (this.mode !== this.modes.editCurrentTheme) {
          if ((v.data.step ?? 7) < 7) {
            this.mode = this.modes.createNewTheme
          } else {
            this.mode = this.modes.editTheme
          }
        }
        this.setPreviewPalette(null)
      }
    },
    activeSlider(value) {
      if (value === 'theme') {
        this.theme = this.template.data.theme
        this.mode = this.modes.default
      }
    },
    appliedTo(value) {
      if (value === 'website') {
        this.isActiveSection = true
      } else if (value === 'page') {
        this.isActiveSection = true
      } else if (value === 'section') {
        this.isActiveSection = false
        if (this.activeSection) {
          this.paletteAppliedSections[this.indexOfActivePage] = [this.activePosition]
        }
      }
    }
  },
  methods: {
    handleEdit() {
      this.mode = this.modes.editTheme
      this.editTheme = cloneDeep(this.currentTheme)
    },
    handleCreateNewTheme(newTheme) {
      // Create New Theme
      this.$dialog
        .confirm({
          action: ['Save Theme', 'Cancel']
        })
        .then(async (res) => {
          if (res) {
            let res
            if (newTheme.id) {
              res = await updateTheme(newTheme)
            } else {
              res = await storeTheme(newTheme)
            }
            this.$store.commit('updateThemes', res.data.data.theme)
            const _theme = cloneDeep(res.data.data.theme)
            this.applyAndSaveTemplateTheme({
              ..._theme.data,
              id: _theme.data.id,
              categoryId: _theme.data.category_id,
              name: _theme.data.name
            })
            this.resetTheme(true)
          }
        })
    },
    handleUpdateTheme() {
      // edit current theme
      if (this.mode === this.modes.editCurrentTheme) {
        this.$store.commit('openModal', {
          name: 'updateThemeModal',
          value: {
            themeName: this.theme.name,
            categoryId: this.theme.categoryId
          },
          onChange: async (value) => {
            const newTheme = {
              name: value.themeName,
              category_id: value.categoryId,
              data: {
                ...this.theme,
                category_id: value.categoryId,
                name: value.themeName,
                palettes: this.theme.palettes
              }
            }
            storeTheme(newTheme).then((res) => {
              this.$store.commit('addThemes', res.data.data.theme)
              this.applyAndSaveTemplateTheme(
                cloneDeep({
                  ...res.data.data.theme.data,
                  id: res.data.data.theme.id,
                  categoryId: res.data.data.theme.category_id,
                  name: res.data.data.theme.name
                })
              )
              this.resetTheme()
              this.$store.commit('closeModal')
            })
          }
        })
      } else if (this.mode === this.modes.editTheme) {
        // edit selected theme
        this.$store.commit('openModal', {
          name: 'updateThemeModal',
          value: {
            themeName: this.theme.name,
            categoryId: this.theme.categoryId,
            themeId: this.theme.id,
            isUpdate: true
          },
          onChange: (value) => {
            const newTheme = {
              name: value.themeName,
              category_id: value.categoryId,
              id: this.theme.id,
              data: {
                ...this.theme,
                category_id: value.categoryId,
                name: value.themeName,
                palettes: this.theme.palettes
              }
            }
            updateTheme(newTheme).then((res) => {
              this.$store.commit('updateThemes', res.data.data.theme)
              const _theme = cloneDeep({
                ...res.data.data.theme.data,
                id: res.data.data.theme.id,
                categoryId: res.data.data.theme.category_id,
                name: res.data.data.theme.name
              })
              this.applyAndSaveTemplateTheme(_theme)
              this.resetTheme()
              this.$store.commit('closeModal')
            })
          }
        })
      }
    },
    handleThemeSelect(themeItem) {
      this.mode = this.modes.applyTheme
      this.theme = cloneDeep({
        ...themeItem.data,
        id: themeItem.id,
        name: themeItem.name,
        categoryId: themeItem.category_id
      })
      window.applyTheme(this.theme)
    },
    toggleShowThemes(v) {
      this.showThemes = this.showThemes === v ? '' : v
    },
    handleClickCreateNewTheme() {
      this.resetTheme()
      this.mode = this.modes.createNewTheme
    },
    handleCloseSlider() {
      if (this.mode === this.modes.createNewTheme) {
        const newTheme = this.$refs.createThemeRef.getNewTheme()
        if (newTheme && newTheme.data.palettes.length > 0) {
          this.$dialog
            .confirm({
              description: 'Would you like to save theme and edit later?',
              action: ['Yes', 'No']
            })
            .then((res) => {
              if (res) {
                if (newTheme.id) {
                  updateTheme(newTheme).then((res) => {
                    this.$store.commit('updateThemes', res.data.data.theme)
                    this.resetTheme()
                  })
                } else {
                  storeTheme(newTheme).then((res) => {
                    this.$store.commit('addThemes', res.data.data.theme)
                    this.resetTheme()
                  })
                }
              } else {
                this.resetTheme()
              }
            })
        } else {
          this.resetTheme(true)
        }
      } else if (this.mode === this.modes.applyTheme) {
        this.$dialog
          .confirm({
            action: ['Apply Theme', 'Close without applying']
          })
          .then((res) => {
            if (res) {
              this.applyAndSaveTemplateTheme(this.theme)
            }
            this.resetTheme()
          })
      } else {
        this.resetTheme(true)
      }
    },
    applyTheme() {
      this.$dialog
        .confirm({
          action: ['Apply Theme', 'Cancel']
        })
        .then((res) => {
          if (res) {
            this.applyAndSaveTemplateTheme(this.theme)
            this.resetTheme()
          }
        })
    },
    resetTheme(closeSlider) {
      this.mode = this.modes.default
      this.setPreviewPalette(null)
      this.showThemes = null
      this.editTheme = null
      this.theme = null
      this.refreshTheme()
      window.applyTheme(this.template.data.theme)
      if (closeSlider) {
        this.closeSlider()
      }
    },
    applyAndSaveTemplateTheme(theme) {
      delete theme.newTheme
      this.template.data.theme = cloneDeep(theme)
      this.theme = this.template.data.theme
      updateTemplateTheme(this.template.id, theme)
    },
    handleClickEditCurrentTheme() {
      if (this.mode === this.modes.createNewTheme) {
        const newTheme = this.$refs.createThemeRef.getNewTheme()
        if (newTheme && newTheme.data.palettes.length > 0) {
          return this.$dialog
            .confirm({
              description: 'Would you like to save theme and edit later?',
              action: ['Yes', 'No']
            })
            .then((res) => {
              if (res) {
                if (newTheme.id) {
                  updateTheme(newTheme).then(() => {
                    this.editTheme = cloneDeep(this.currentTheme)
                    this.mode = this.modes.editCurrentTheme
                  })
                } else {
                  storeTheme(newTheme).then(() => {
                    this.editTheme = cloneDeep(this.currentTheme)
                    this.mode = this.modes.editCurrentTheme
                  })
                }
              } else {
                this.editTheme = cloneDeep(this.currentTheme)
                this.mode = this.modes.editCurrentTheme
              }
            })
        }
      }
      this.mode = this.modes.editCurrentTheme
      this.editTheme = cloneDeep(this.currentTheme)
    },
    handleCancelChanges() {
      this.$dialog
        .confirm({
          description: 'Are you really sure you want to cancel changes?'
        })
        .then((res) => {
          if (res) {
            this.resetTheme()
          }
        })
    }
  }
}
</script>

<style lang="scss">
$active: rgb(0, 118, 223);
.theme_area {
  width: 400px;
  height: calc(100vh - 60px);
  position: fixed;
  left: 70px;
  top: 60px;
  background-color: rgb(239, 240, 241);
  overflow: auto;
  max-height: unset;
  z-index: 3;
  transform: translateX(-470px);
  //transition: transform 0.3s linear;
  overscroll-behavior: contain;

  .control-button {
    border-radius: 4px;
    width: 100%;
    background-color: white;
    box-shadow: 0 0 2px 4px rgb(0 0 0 / 7%);
    cursor: pointer;
    position: relative;
    overflow: hidden;
    margin-top: 10px;
    padding: 12px;
    display: flex;
    justify-content: space-between;
    align-items: center;
  }

  .bz-close-section-area {
    font-size: 26px;
  }

  &.active {
    transform: translateX(0);
  }

  .md-tabs {
    height: 100%;

    .md-tabs-container {
      height: 100%;
    }

    .md-tabs-content {
      height: 100% !important;
    }

    .md-button-content {
      text-transform: capitalize;
    }

    .md-tabs-navigation.md-elevation-0 {
      background-color: rgb(239, 240, 241) !important;
      border-bottom: solid 1px #8080803f;
    }

    .md-content.md-theme-default {
      background-color: transparent !important;
      flex-grow: 1;
    }

    .theme-mode {
      display: flex;
      align-items: center;
      justify-content: space-between;

      div {
        padding: 1px;
        border-radius: 5px;
        width: 48%;
        height: 38px;

        &.active {
          border: solid 2px #0076df;
        }

        button {
          width: 100%;
          height: 100%;
          border-radius: 4px;
          border: solid 1px #8080803f;
        }
      }
    }
  }

  .theme-colors-wrap {
    width: 100%;

    .theme-color-labels {
      display: flex;
      width: 100%;
      align-items: center;

      div {
        flex: 1;
        text-align: center;
        display: flex;
        justify-content: center;

        small {
          text-align: center;
        }
      }
    }

    .theme-colors {
      display: flex;
      width: 100%;
      align-items: center;
      justify-content: space-between;
      cursor: pointer;
      position: relative;

      .theme-color-item {
        flex: 1;
        margin: 0 1px;
        text-align: center;

        div {
          height: 34px;
          border-radius: 4px;
          border: solid 1px #8080803f;
          outline: solid 2px transparent;

          &:hover {
            outline: solid 2px rgb(157, 222, 255);
            border: solid 1px white;
          }

          &.active {
            border: solid 1px white;
            outline: solid 2px #0076df;
          }
        }
      }
    }
  }

  .more-colors {
    box-shadow: 0 0 2px 4px rgb(0 0 0 / 7%);
    background-color: white;
    border: solid 1px #8080803f;
    border-radius: 4px;
    margin-top: 10px;

    .md-radio {
      margin: 4px 0;
    }

    .md-tabs {
      .md-tabs-navigation.md-elevation-0 {
        background-color: white !important;
        border-bottom: solid 1px #8080803f;
      }

      .color-panel {
        height: 214px;
        overflow-y: scroll;
      }

      .load-more {
        width: 100%;
        padding: 10px 0;
        color: $active;
        border-top: solid 1px #8080807f;
        cursor: pointer;
      }
    }
  }

  .color-group {
    margin: 4px 0;
    width: 100%;
    height: 30px;
    border: solid 1px #8080803f;
    border-radius: 4px;
    overflow: hidden;
    display: flex;

    &:hover {
      border: solid 2px rgb(123, 236, 214);
    }

    &.active {
      border: solid 2px $active;
    }

    .color-item {
      flex: 1;
      height: 100%;
      cursor: pointer;
      margin-right: 1px;
    }

    .color-item:last-child {
      margin-right: 0;
    }
  }

  #tab-fonts {
    button {
      outline: none !important;
      border: none !important;

      &.active {
        background-color: $active !important;
        color: white !important;
      }
    }

    .font-presets {
      .font-item {
        width: 100%;
        background-color: white;
        padding: 12px;
        border: solid 2px transparent;
        box-shadow: 0 0 2px 1px #00000012;
        margin-top: 10px;
        border-radius: 4px;
        cursor: pointer;

        &:hover {
          border: solid 2px #9ddeff;
        }

        &.active {
          border: solid 2px $active;
        }
      }
    }
  }

  .bz-select {
    width: 100px;
    border-radius: 2px;
    border: solid 1px #80808034;
    padding: 4px;
    background-color: white;
  }

  .theme-control-item {
    color: $active;
    cursor: pointer;

    &:hover {
      color: $active;
    }
  }

  .page-list {
    label {
      display: flex;
      cursor: pointer;
      align-items: center;
      padding: 6px 0;
      width: 100%;

      input {
        margin-right: 10px;
      }

      &:hover {
        color: $active;
      }
    }
  }
}
</style>
