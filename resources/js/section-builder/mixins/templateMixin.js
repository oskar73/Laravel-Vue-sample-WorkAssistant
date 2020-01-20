import normalizeUrl from 'normalize-url'
export default {
  computed: {
    template: {
      get () {
        return this.$store.state.template
      },
      set (value) {
        this.$store.commit('updateTemplate', value)
      }
    },
    domain () {
      const protocol = this.$config.appEnv === 'local' ? 'http://' : 'https://'
      return protocol + (this.template.domain || this.$config.appDomain)
    },
    header: {
      get () {
        return this.template.data.header
      },
      set (value) {
        this.template.data.header = value
      }
    },
    footer: {
      get () {
        return this.template.data.footer
      },
      set (value) {
        this.template.data.footer = value
      }
    },
    allPages: {
      get () {
        return this.template?.pages ?? []
      },
      set (value) {
        this.template.pages = value
      }
    },
    indexOfActivePage () {
      return this.allPages.findIndex((page) => this.$route.path === (page.url || '/'))
    },
    templateSetting: {
      get () {
        return this.template.data.setting
      },
      set (val) {
        this.template.data.setting = val
      }
    }
  }
}
