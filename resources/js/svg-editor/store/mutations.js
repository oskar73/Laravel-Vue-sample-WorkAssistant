import _ from 'lodash'

export default {
  // Basic Modal
  openModal(state, payload) {
    state.modals.basic = payload
  },
  closeModal(state) {
    state.modals.basic = {
      name: null,
      data: null,
      onChange: null,
      onClose: null
    }
  },
  setStore(state, { path, value }) {
    _.set(state, path, value)
  }
}
