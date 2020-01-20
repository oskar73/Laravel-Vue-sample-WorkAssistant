export default {
  computed: {
    theme() {
      if (this.$store.state.themePreview) {
        return this.$store.state.themePreview
      }

      const _templateTheme = this.template.data.theme
      const _storeTheme = this.$store.state.theme
      if (this.isBuilder) {
        return _storeTheme || _templateTheme
      } else {
        return _templateTheme
      }
    },
    mainPalette() {
      const _palettes = this.theme?.palettes || this.template.data.theme.palettes
      const websitePalette = _palettes.find((p) => p.appliedTo === 'website')

      if (websitePalette) {
        return websitePalette.colors
      }
      return {
        backgroundColor: '#ffffff',
        primaryColor: '#E07F10',
        buttonColor: '#E07F10',
        socialIconColor: '#11638F',
        headingColor: '#1e7730',
        boxColor: '#939393',
        secondaryColor: '#11638F'
      }
    },
    palette() {
      let _palette = null
      const _previewPalette = this.$store.state.previewPalette
      const _paletteAppliedSections = this.$store.state.paletteAppliedSections
      const _appliedTo = this.$store.state.appliedTo
      const _palettes = this.theme.palettes

      // Switch Template view
      if (this.$store.state.themePreview) {
        const pagePalette = _palettes.find((p) => p.appliedTo === 'page' && (p.applies ?? []).includes(this.$store.state.indexOfActiveViewPage))
        const sectionPalette = _palettes.find((p) => p.appliedTo === 'section' && (p.applies?.[this.$store.state.indexOfActiveViewPage] ?? []).includes(this.position))
        _palette = sectionPalette || pagePalette
      }

      // preview palettes
      if (!_palette && _previewPalette && this.isBuilder) {
        if (_appliedTo === 'section' && this.pageData) {
          if ((_paletteAppliedSections[this.pageData.index] ?? []).includes(this.position)) {
            _palette = _previewPalette
          }
        } else {
          _palette = _previewPalette
        }
      }

      if (!_palette && _palettes) {
        const websitePalette = _palettes.find((p) => p.appliedTo === 'website')

        if (this.pageData) {
          const pagePalette = _palettes.find((p) => p.appliedTo === 'page' && (p.applies ?? []).includes(this.pageData.index))
          const sectionPalette = _palettes.find((p) => p.appliedTo === 'section' && (p.applies?.[this.pageData.index] ?? []).includes(this.position))
          _palette = sectionPalette || pagePalette
        }

        _palette = _palette || websitePalette
      }

      if (_palette) {
        return _palette.colors
      }

      return {
        backgroundColor: '#ffffff',
        primaryColor: '#E07F10',
        buttonColor: '#E07F10',
        socialIconColor: '#11638F',
        headingColor: '#1e7730',
        boxColor: '#939393',
        secondaryColor: '#11638F'
      }
    },
    /**
     * Returns background color
     * @returns {*}
     */
    backgroundColor() {
      return this.palette.backgroundColor
    },
    /**
     *  Returns button background color
     *  With the simple mode, it will be primary color
     */
    buttonColor() {
      return this.palette.buttonColor
    },
    /**
     *  Returns social icon background color
     *  With the simple mode, it will be primary color
     */
    socialIconColor() {
      return this.palette.socialIconColor
    },

    /**
     *  Returns heading background color
     *  With the simple mode, it will be primary color
     */
    headingColor() {
      return this.palette.headingColor
    },

    /**
     *  Returns box color
     */
    boxColor() {
      return this.palette.boxColor
    },
    /**
     *  Box color with primary in simple mode
     */
    primaryBoxColor() {
      return this.palette.boxColor
    },
    /**
     *  Returns heading secondary color
     */
    secondaryColor() {
      return this.palette.secondaryColor
    }
  },
  methods: {
    /**
     * @deprecated
     * detach theme from the all pages and sections
     */
    clearPageThemes() {
      for (const page of this.allPages) {
        if (page.data.theme) {
          page.data.theme = null
        }
        for (const section of page.sections) {
          section.data.theme = null
        }
      }
      this.header.data.theme = null
      this.footer.data.theme = null
    },
    /**
     * @deprecated
     * detach theme from the currently active sections
     */
    clearSectionThemes() {
      for (const section of this.activeSections) {
        section.data.theme = null
      }
      this.header.data.theme = null
      this.footer.data.theme = null
    }
  }
}
