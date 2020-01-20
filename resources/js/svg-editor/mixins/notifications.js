import Toast from 'izitoast'
Toast.settings({
  position: 'topRight'
})

export default {
  mounted() {
    this.showNotification()
  },

  methods: {
    showNotification() {
      const title = this.getParameterByName('type')
      const type = this.getParameterByName('type')
      const message = this.getParameterByName('message')
      const duration = this.getParameterByName('duration')

      if (type && message) {
        setTimeout(() => {
          this.notification({
            title,
            type,
            message,
            duration: duration === null ? 4000 : parseInt(duration)
          })
        }, 1000)

        window.history.replaceState(null, null, window.location.pathname)
      }
    },

    notification(config) {
      const title = config.title ? this.ucFirst(config.title) : null

      if (title) {
        config.title = title
      }
      switch (config.type) {
        case 'success': {
          Toast.success(config)
          break
        }
        case 'error': {
          Toast.error(config)
          break
        }
        case 'info': {
          Toast.info(config)
          break
        }
        default: {
          Toast.show(config)
          break
        }
      }
    },

    getParameterByName(name, url) {
      if (!url) url = window.location.href
      // eslint-disable-next-line
      name = name.replace(/[\[\]]/g, '\\$&')
      const regex = new RegExp('[?&]' + name + '(=([^&#]*)|&|#|$)')
      const results = regex.exec(url)
      if (!results) return null
      if (!results[2]) return ''
      return decodeURIComponent(results[2].replace(/\+/g, ' '))
    },

    ucFirst(string) {
      if (string) {
        return string.charAt(0).toUpperCase() + string.slice(1)
      }

      return string
    }
  }
}
