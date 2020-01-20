import { cloneDeep } from 'lodash'
export const settingMutation = {
  updateBackground(state, payload) {
    if (state.activePosition === 'header') {
      state.template.data.header.data.background = { ...state.template.data.header.data.background, ...payload }
      state.template.data.header = cloneDeep(state.template.data.header)
    } else if (state.activePosition === 'footer') {
      state.template.data.footer.data.background = { ...state.template.data.footer.data.background, ...payload }
      state.template.data.footer = cloneDeep(state.template.data.footer)
    } else {
      if (state.template.pages) {
        state.template.pages[state.indexOfActivePage].sections[state.activePosition].data.background = {
          ...state.template.pages[state.indexOfActivePage].sections[state.activePosition].data.background,
          ...payload
        }
        state.template.pages = cloneDeep(state.template.pages)
      }
    }
  },
  updateTemplateSetting(state, payload) {
    state.setting = payload
  },
  updateOpenSettingPanel(state, payload) {
    state.isOpenSettingPanel = payload
  },
  toggleSettingPanel(state, payload = undefined) {
    if (payload === undefined) {
      state.isOpenSettingPanel = !state.isOpenSettingPanel
    } else {
      state.isOpenSettingPanel = payload
    }
  },
  updateSettingEditor(state, payload) {
    state.settingEditor = payload
  },
  rerenderSettingPanel(state, payload) {
    state.isRerenderSettingPanel = payload
  }
}
