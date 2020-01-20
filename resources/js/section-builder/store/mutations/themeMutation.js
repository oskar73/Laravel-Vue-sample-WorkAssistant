import { cloneDeep } from 'lodash'

export const themeMutation = {
  updateTheme: (state, payload) => {
    state.theme = cloneDeep(payload)
  },
  updateThemePreview: (state, payload) => {
    state.themePreview = payload
  },
  updateThemePreviewPages: (state, payload) => {
    state.themePreviewPages = payload
  },
  updateIsActiveSection: (state, payload) => {
    state.isActiveSection = payload
  },
  updatePaletteAppliedPages(state, payload) {
    state.paletteAppliedPages = payload
  },
  updatePaletteAppliedSections(state, payload) {
    state.paletteAppliedSections = payload
  },
  updateIsNewPaletteMode(state, payload) {
    state.isNewPaletteMode = payload
  },
  addThemes(state, newTheme) {
    state.themes.push(newTheme)
    state.themes = cloneDeep(state.themes)
  },
  updateThemes(state, updatedTheme) {
    const selectedThemeIndex = state.themes.findIndex((t) => t.id === updatedTheme.id)
    if (selectedThemeIndex === -1) {
      state.themes.push(updatedTheme)
    } else {
      state.themes[selectedThemeIndex] = updatedTheme
    }
    state.themes = cloneDeep(state.themes)
  },
  removeTheme: (state, themeId) => {
    state.themes = state.themes.filter((theme) => theme.id !== themeId)
  },
  setPreviewPalette: (state, palette) => {
    state.previewPalette = palette
  },
  addPalette(state, palette) {
    if (state.theme.palettes) {
      // remove old applied palettes
      if (palette.appliedTo === 'website') {
        // remove old website palette
        state.theme.palettes = state.theme.palettes.filter((p) => p.appliedTo !== 'website')
      } else if (palette.appliedTo === 'page') {
        // remove old page palettes
        state.theme.palettes = state.theme.palettes.filter((p) => {
          if (p.appliedTo === 'page') {
            p.applies = p.applies.filter((pp) => !palette.applies.includes(pp))
            return p.applies.length > 0
          }
          return p
        })
      } else if (palette.appliedTo === 'section') {
        // remove old section palettes
        state.theme.palettes = state.theme.palettes.filter((p) => {
          if (p.appliedTo === 'section') {
            for (const page in palette.applies) {
              if (p.applies && p.applies[page]) {
                p.applies[page] = p.applies[page].filter((_p) => !palette.applies[page].includes(_p))
                if (p.applies[page].length === 0) {
                  delete p.applies[page]
                }
              }
            }
            return Object.keys(p.applies).length > 0
          }
          return p
        })
      }
      state.theme.palettes.push(palette)
    } else {
      state.theme.palettes = [palette]
    }
    state.theme = cloneDeep(state.theme)
  },
  setAppliedTo(state, value) {
    state.appliedTo = value
  },
  updateSystemPalettes(state, value) {
    state.systemPalettes = cloneDeep(value)
  },
  updateUserPalettes(state, value) {
    state.userPalettes = cloneDeep(value)
  }
}
