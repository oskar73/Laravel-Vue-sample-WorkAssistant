import { cloneDeep } from 'lodash'

export const userTemplateMutations = {
  updateTheme: (state, payload) => {
    state.theme = cloneDeep(payload)
  }
}
