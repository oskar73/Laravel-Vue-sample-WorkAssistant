import { cloneDeep } from 'lodash'
import eventBus from '@/public/eventBus'

export const sectionMutations = {
  insertSection(state, payload = {}) {
    const position = payload.position || 0
    const section = payload.section || null
    state.addPosition = position
    if (!state.template.pages[state.indexOfActivePage].sections) state.template.pages[state.indexOfActivePage].sections = []
    state.showEmptySection = true
    state.template.pages[state.indexOfActivePage].sections.insert(position, section)
    if (!section && payload.isWebsite) {
      state.activeSlider = 'sections'
    }
  },
  setAddPosition(state, payload) {
    state.addPosition = payload
    if (state.addPosition === null && state.template.pages.length) {
      const index = state.template.pages[state.indexOfActivePage].sections.indexOf(null)
      if (index > -1) {
        state.template.pages[state.indexOfActivePage].sections.splice(index, 1)
      }
    }
  },
  swapSection(state, payload) {
    state.template.pages[state.indexOfActivePage].sections.swap(payload[0], payload[1])
    state.template.pages = cloneDeep(state.template.pages)
    state.activePosition = payload[1]
    eventBus.$emit('refresh:sections')
    eventBus.$emit('section:swap')
  },
  removeSection(state, payload) {
    let position = payload
    if (typeof payload === 'undefined') {
      position = state.activePosition
    }
    state.template.pages[state.indexOfActivePage].sections.splice(position, 1)
    if (state.template.pages[state.indexOfActivePage].sections.length === 0) {
      state.activePosition = 'header'
    }
    eventBus.$emit('section:delete')
  },
  setActivatePosition(state, payload) {
    state.activePosition = payload
  }
}
