import { getUserTemplates, deleteUserTemplate } from '../../apis'
import { cloneDeep } from 'lodash'

export const userTemplateActions = {
  async fetchUserTemplates({ commit }) {
    const { data: { status, data: websites } } = await getUserTemplates()

    console.log('User Templates data loaded', cloneDeep(websites))
    if (status) {
      commit('setUserTemplates', websites)
    } else {
      commit('setUserTemplates', [])
    }
  },
  async deleteUserTemplate({ commit, dispatch }, templateId) {
    await deleteUserTemplate(templateId)

    await dispatch('fetchUserTemplates')
  }
}
