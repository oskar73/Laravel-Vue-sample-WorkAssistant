import { mapMutations } from 'vuex'
import { cloneDeep, merge, pick } from 'lodash'
import themeMixin from './themeMixin'
import appMixin from './appMixin'
import templateMixin from './templateMixin'
import { addNewSectionToTemplate } from '@/section-builder/apis'

import eventBus from '@/public/eventBus'
import 'vue3-toastify/dist/index.css'

export default {
  mixins: [appMixin, templateMixin, themeMixin],
  computed: {
    showEmptySection() {
      return this.$store.state.showEmptySection
    },
    activeSlider: {
      get() {
        return this.$store.state.activeSlider
      },
      set(value) {
        this.$store.commit('setStore', {
          path: 'activeSlider',
          value
        })
      }
    },
    viewMode: {
      get() {
        return this.$store.state.viewMode
      },
      set(value) {
        this.$store.commit('setStore', {
          path: 'viewMode',
          value
        })
      }
    },
    edit() {
      return this.$store.state.edit
    },
    allCategories() {
      return this.$store.state.allCategories
    },
    moduleCategories() {
      return this.$store.state.modules.moduleCategories || []
    },
    systemPalettes: {
      get() {
        return this.$store.state.systemPalettes
      },
      set(value) {
        return this.$store.commit('updateSystemPalettes', value)
      }
    },
    userPalettes() {
      return this.$store.state.userPalettes
    },
    themes() {
      return this.$store.state.themes
    },
    headerSections() {
      const headerCategory = this.allCategories.find((category) => category.name === 'Header')
      return headerCategory?.sections || []
    },
    footerSections() {
      const footerCategory = this.allCategories.find((category) => category.name === 'Footer')
      if (footerCategory) {
        return footerCategory?.sections
      }
      return []
    },
    // (store) theme is in preview mode
    themePreview: {
      get() {
        return this.$store.state.themePreview
      },
      set(value) {
        this.$store.commit('updateThemePreview', value)
      }
    },
    // Where the (store) theme is applied to
    appliedTo: {
      get() {
        return this.$store.state.appliedTo
      },
      set(value) {
        this.$store.commit('setAppliedTo', value)
      }
    },
    isActiveSection: {
      get() {
        return this.$store.state.isActiveSection
      },
      set(value) {
        this.$store.commit('updateIsActiveSection', value)
      }
    },
    paletteAppliedPages: {
      get() {
        return this.$store.state.paletteAppliedPages
      },
      set(value) {
        this.$store.commit('updatePaletteAppliedPages', value)
      }
    },
    paletteAppliedSections: {
      get() {
        return this.$store.state.paletteAppliedSections
      },
      set(value) {
        this.$store.commit('updatePaletteAppliedSections', value)
      }
    },
    isNewPaletteMode: {
      get() {
        return this.$store.state.isNewPaletteMode
      },
      set(value) {
        this.$store.commit('updateIsNewPaletteMode', value)
      }
    },
    isOpenSettingPanel() {
      return this.$store.state.isOpenSettingPanel
    },
    isFixedSettingPanel() {
      return this.$store.state.isFixedSettingPanel
    },
    settingEditor: {
      get() {
        return this.$store.state.settingEditor
      },
      set(value) {
        this.$store.commit('updateSettingEditor', value)
      }
    }
  },
  methods: {
    async addSection() {
      this.errorMessage = ''
      if (this.category) {
        this.loading = true
        try {
          const res = await addNewSectionToTemplate({
            category: this.category.id,
            position: this.$store.state.addPosition,
            page: this.activePage.id
          })

          const newSection = {
            ...res.data.section,
            category: this.category,
            category_id: this.category.id
          }

          for (let i = 0; i < this.$store.state.template.pages.length; i++) {
            const page = this.$store.state.template.pages[i]
            if (page.id === this.activePage.id) {
              const newSections = []
              page.sections.forEach((s, index) => {
                if (index === this.$store.state.addPosition) {
                  newSections.push({
                    ...newSection,
                    page_id: page.id,
                    data: {
                      ...newSection.data,
                      setting: {
                        ...newSection.data.setting,
                        visible: true
                      }
                    }
                  })
                }
                newSections.push(s)
              })
              if (this.$store.state.addPosition >= page.sections.length) {
                newSections.push({
                  ...newSection,
                  page_id: page.id,
                  data: {
                    ...newSection.data,
                    setting: {
                      ...newSection.data.setting,
                      visible: true
                    }
                  }
                })
              }
              this.$store.state.template.pages[i].sections = newSections
            } else {
              this.$store.state.template.pages[i].sections.push({
                ...newSection,
                page_id: page.id,
                data: {
                  ...newSection.data,
                  setting: {
                    ...newSection.data.setting,
                    visible: false
                  }
                }
              })
            }
          }
          this.removeEmptySection()
          this.category = null
          this.loading = false
        } catch {
          this.errorMessage = 'Adding section failed'
          this.loading = false
        }
      } else {
        this.errorMessage = 'Please select a section category'
        this.loading = false
      }
    },
    appendSection(position) {
      console.log('builderMixin.appendSection: position', position)
      if (this.activePage) {
        if (this.activePosition === 'header') {
          this.activePosition = 0
        } else if (this.activePosition === 'footer') {
          this.activePosition = this.activeSections ? this.activeSections.length - 1 : 0
        } else {
          this.activePosition = this.activePosition + 1
        }
        this.addEmptySection(this.activePosition)
      }
    },
    prependSection(position) {
      if (this.activePage) {
        if (this.activePosition === 'header') {
          this.activePosition = 0
        } else if (this.activePosition === 'footer') {
          this.activePosition = this.activeSections ? this.activeSections.length - 1 : 0
        }
        this.addEmptySection(this.activePosition)
      }
    },
    removeEmptySection() {
      const indexOfNullSection = this.activeSections?.indexOf(null)
      if (indexOfNullSection > -1) {
        this.activeSections.splice(indexOfNullSection, 1)
      }
      eventBus.$emit('EmptySectionRemoved')
    },
    addEmptySection(position) {
      console.log('builderMixin.addEmptySection: position', position)
      this.removeEmptySection()

      if (position !== undefined) {
        this.insertSection({
          position,
          isWebsite: this.isWebsite
        })
      } else {
        if (typeof this.activePosition === 'number' && this.activePosition > -1) {
          this.insertSection({
            position: this.activePosition,
            isWebsite: this.isWebsite
          })
        } else {
          this.insertSection({
            position: 0,
            isWebsite: this.isWebsite
          })
        }
      }

      setTimeout(() => {
        eventBus.$emit('scrollToActiveSection', position)
      }, 800)
    },
    assignSection(section) {
      this.activeSections[this.addPosition] = cloneDeep(section)
      this.$store.commit('updateTemplate')
    },
    openTemplateSlider() {
      if (this.activeSlider !== 'templates') {
        this.enableEdit()
        this.setOpenSlider({ sliderName: 'templates' })
      }
    },
    openPageSlider() {
      if (this.activeSlider !== 'pages') {
        this.enableEdit()
        this.setOpenSlider({ sliderName: 'pages' })
      }
    },
    openThemeSlider() {
      this.addPosition = null
      if (this.activeSlider !== 'theme') {
        this.disableEdit()
        this.setOpenSlider({ sliderName: 'theme' })
        window.document.getElementsByTagName('html')[0].style.overflow = ''
      }
    },
    openMyTemplateSlider() {
      if (this.activeSlider !== 'myTemplates') {
        this.enableEdit()
        this.setOpenSlider({ sliderName: 'myTemplates' })
      }
    },
    openSettingSlider(activeTab, activeSubTab) {
      if (this.activeSlider !== 'settings') {
        this.enableEdit()
        this.setOpenSlider({
          sliderName: 'settings',
          activeTab,
          activeSubTab
        })
      }
    },
    /**
     * sets advanced theme colors from the colors
     * @param colors
     */
    setAdvancedColors(colors) {
      if (colors) {
        this.palette.colors.backgroundColor = colors[0]
        this.palette.colors.buttonColor = colors[1]
        this.palette.colors.socialIconColor = colors[2]
        this.palette.colors.headingColor = colors[3]
        this.palette.colors.boxColor = colors[4]
        this.palette.colors.secondaryColor = colors[5]
      } else {
        console.warn('setAdvancedColors: colors are undefined')
      }
    },
    /**
     * Color is same as the current theme colors
     * @param color
     * @returns {boolean}
     */
    isActiveColor(color) {
      return (
        this.palette.colors.backgroundColor === color.backgroundColor &&
        this.palette.colors.buttonColor === color.buttonColor &&
        this.palette.colors.socialIconColor === color.socialIconColor &&
        this.palette.colors.headingColor === color.headingColor &&
        this.palette.colors.boxColor === color.boxColor &&
        this.palette.colors.secondaryColor === color.secondaryColor
      )
    },
    refreshTheme() {
      eventBus.$emit('refreshSectionTheme')
    },
    /**
     * @deprecated
     * @param theme
     */
    syncTheme(theme) {
      let storeTheme
      if (theme) {
        if (this.appliedTo === 'website') {
          if (this.template) {
            this.template.data.theme = theme
            storeTheme = this.template.data.theme
          } else {
            throw 'template does not exist'
          }
        } else if (this.appliedTo === 'page') {
          for (const pageIndex of this.paletteAppliedPages) {
            const page = this.allPages[pageIndex]
            if (page.data) {
              page.data.theme = theme
            } else {
              page.data = { theme }
            }
          }
          storeTheme = theme
        } else if (this.appliedTo === 'section') {
          if (this.activeSection) {
            const activeSectionThemeId = this.activeSection.data.theme?.themeId
            if (activeSectionThemeId) {
              for (const section of this.activeSections) {
                if (section.data.theme?.themeId === activeSectionThemeId) {
                  section.data.theme = theme
                }
              }
            } else {
              this.activeSection.data.theme = theme
            }
            storeTheme = this.activeSection.data.theme
          }
        }
      } else {
        if (!this.activePage) {
          throw 'active page does not exist'
        }
        if (!this.activeSection) {
          throw 'active section does not exist'
        }
        const templateTheme = this.template.data.theme
        const pageTheme = this.activePage.data?.theme
        const sectionTheme = this.activeSection.data.theme
        if (this.appliedTo === 'website') {
          storeTheme = templateTheme
        } else if (this.appliedTo === 'page') {
          if (pageTheme) {
            storeTheme = pageTheme
          } else {
            storeTheme = cloneDeep({
              ...templateTheme,
              themeId: this.$uuid.v4()
            })
          }
        } else if (this.appliedTo === 'section') {
          if (sectionTheme) {
            storeTheme = cloneDeep(sectionTheme)
          } else {
            storeTheme = cloneDeep({
              ...(pageTheme || templateTheme),
              themeId: this.$uuid.v4()
            })
          }
        }
      }

      if (storeTheme) {
        this.$store.commit('updateTheme', storeTheme)
      }
    },
    mergeSectionSetting(baseSection, sourceSection, exceptColumns = []) {
      const _setting = cloneDeep(baseSection.data.setting)
      const settingKeys = Object.keys(_setting).filter((key) => !exceptColumns.includes(key))
      for (const key of settingKeys) {
        if (typeof _setting[key] === 'object') {
          const picked = pick(sourceSection.data.setting[key], Object.keys(_setting[key]))
          _setting[key] = merge(_setting[key], picked ?? {})
        } else {
          if (sourceSection.data.setting?.[key]) {
            _setting[key] = sourceSection.data.setting[key]
          }
        }
      }
      _setting.visible = true
      return cloneDeep(_setting)
    },
    ...mapMutations({
      setOpenSlider: 'setOpenSlider',
      closeSlider: 'closeSlider',
      setActivePage: 'setActivePage',
      setActiveViewPage: 'setActiveViewPage',
      insertSection: 'insertSection',
      enableEdit: 'enableEdit',
      disableEdit: 'disableEdit',
      setAddPosition: 'setAddPosition',
      setViewMode: 'setViewMode',
      updateBackground: 'updateBackground',
      updateLayout: 'updateLayout',
      addPalette: 'addPalette',
      setPreviewPalette: 'setPreviewPalette',
      toggleSettingPanel: 'toggleSettingPanel'
    })
  }
}
