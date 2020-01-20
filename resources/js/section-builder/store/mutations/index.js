import _ from 'lodash'
import { appMutation } from './appMutation'
import { sectionMutations } from './sectionMutations'
import { layoutMutations } from './layoutMutations'
import { builderMutation } from './builderMutation'
import { themeMutation } from './themeMutation'
import { settingMutation } from './settingMutations'
import { modalMutation } from './modalMutation'

export default {
  setStore(state, { path, value }) {
    _.set(state, path, value)
  },
  ...appMutation,
  ...sectionMutations,
  ...layoutMutations,
  ...builderMutation,
  ...themeMutation,
  ...settingMutation,
  ...modalMutation
}
