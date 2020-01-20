import confirm from './confirm'

export default {
  install(app) {
    app.config.globalProperties.$dialog = {
      confirm: async ({ title, description, action } = {}) => {
        return confirm({
          title: title || 'Confirmation',
          description: description || 'Are you sure with this action?',
          action: action || ['Yes', 'No']
        })
      }
    }

    app.config.globalProperties.$utils = {

    }
  }
}
