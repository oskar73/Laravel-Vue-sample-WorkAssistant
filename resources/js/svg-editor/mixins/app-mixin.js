import mousetrap from 'mousetrap'
import axios from 'axios'

export default {
  data() {
    return {
      products: {
        image: 'image',
        package: 'package'
      },
      http: {
        statuses: {
          error: 'error',
          success: 'success'
        }
      },
      routes: {
        logout: window.route('logout'),
        login: window.route('login'),
        register: window.route('register')
      }
    }
  },
  computed: {
    designData: {
      get() {
        return this.$store.state.designData
      },
      set(value) {
        this.$store.commit('setStore', {
          path: 'designData',
          value
        })
      }
    },
    designHash() {
      return this.designData?.hash
    },
    graphic() {
      return this.designData?.graphic
    },
    graphics() {
      return this.designData?.graphics
    },
    original_version() {
      return this.designData?.version
    },
    fontUrl() {
      return this.designData?.fontUrl
    },
    graphicMasks() {
      return this.designData?.masks || []
    },
    graphicImages() {
      return this.designData?.images || []
    },
    graphicIcons() {
      return this.designData?.icons || []
    },
    pairs() {
      return this.designData?.pairs || []
    },
    ownerDesign() {
      return this.designData?.ownerDesign
    },
    loadingSvgData: {
      get() {
        return this.$store.state.loadingSvgData
      },
      set(value) {
        this.$store.commit('setStore', {
          path: 'loadingSvgData',
          value
        })
      }
    },
    isSavingDesign: {
      get() {
        return this.$store.state.isSavingDesign
      },
      set(value) {
        this.$store.commit('setStore', {
          path: 'isSavingDesign',
          value
        })
      }
    },
    liveView: {
      get() {
        return this.$store.state.liveView
      },
      set(value) {
        this.$store.commit('setStore', {
          path: 'liveView',
          value
        })
      }
    },
    isOpenPreview: {
      get() {
        return this.$store.state.isOpenPreview
      },
      set(value) {
        this.$store.commit('setStore', {
          path: 'isOpenPreview',
          value
        })
      }
    }
  },
  methods: {
    downloadProtection() {
      if (!location.host.includes('local')) {
        // if (!user.permissions.devtools) {
        //     EventBus.$on('devtoolschange', event => {
        //         if (event.isOpen) {
        //             window.location.href = '/';
        //         }
        //     });
        //
        //     document.oncontextmenu = function() {
        //         return false;
        //     };
        // }
      }

      mousetrap.bind(['command+s', 'ctrl+s'], function (e) {
        e.preventDefault()
        e.stopPropagation()
      })
    },
    isLineSelected() {
      try {
        const selected = this.getFirstSelected()

        return selected.nodeName === 'line'
      } catch (e) {
        return false
      }
    },
    rot13(str, e = false) {
      str = e ? window.btoa(str) : str

      const rotted = str.replace(/[a-zA-Z]/g, function (c) {
        return String.fromCharCode((c <= 'Z' ? 90 : 122) >= (c = c.charCodeAt(0) + 13) ? c : c - 26)
      })

      return e ? rotted : window.atob(rotted)
    },

    clearLogotype(logotype) {
      try {
        // Get snap lines
        const snapLineX = logotype.getElementById('snap_line_x')
        const snapLineY = logotype.getElementById('snap_line_y')

        // Hide snap lines
        snapLineX.style.display = 'none'
        snapLineY.style.display = 'none'
      } catch (e) {
        console.warn(e)
      }
    },

    logoFromStr(xml) {
      // Create logo dom object
      const fakeElem = document.createElement('div')
      fakeElem.innerHTML = xml
      return fakeElem.children[0]
    },

    getUser() {
      return window.user
    },

    goTo(to) {
      window.location.href = to
    },

    goToRoute(routeName, params) {
      params = params || {}

      window.location.href = this.route(routeName, params)
    },

    getUrlByRoute(routeName, params) {
      return this.route(routeName, params)
    },

    /* eslint-disable */
    isMobile() {
      let check = false
      ;(function (a) {
        if (
          /(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|mobile.+firefox|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows ce|xda|xiino/i.test(
            a
          ) ||
          /1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i.test(
            a.substr(0, 4)
          )
        ) {
          check = true
        }
      })(navigator.userAgent || navigator.vendor || window.opera)
      return check
    },
    /* eslint-enable */
    isSafari() {
      return /^((?!chrome|android).)*safari/i.test(navigator.userAgent)
    },

    isIE() {
      const ua = window.navigator.userAgent

      const msie = ua.indexOf('MSIE ')
      if (msie > 0) {
        // IE 10 or older => return version number
        return parseInt(ua.substring(msie + 5, ua.indexOf('.', msie)), 10)
      }

      const trident = ua.indexOf('Trident/')

      if (trident > 0) {
        // IE 11 => return version number
        const rv = ua.indexOf('rv:')
        return parseInt(ua.substring(rv + 3, ua.indexOf('.', rv)), 10)
      }

      // other browser
      return false
    },

    routeIs(routeName) {
      return routeName === this.route().current()
    },

    isEditorPage() {
      return this.route().current() === this.steps.edit.name
    },

    isString(value) {
      return (typeof value === 'string' || value instanceof String) && value.length > 0
    },

    downloadGaEvent(logotype, purchaseType) {
      try {
        const isPurchased = purchaseType === this.purchases.logotype ? this.logoIsPurchased(logotype) : this.packageIsPurchased(logotype)

        const action = isPurchased ? 'download' : 'purchase'
        const eventName = `${purchaseType}.${action}`

        // eslint-disable-next-line
        gtag('event', eventName, {
          event_category: 'purchase',
          event_label: 'Download or purchase logo'
        })
      } catch (e) {
        console.warn('Google analytics is not defined.')
      }
    },

    logoIsPurchased(logotype) {
      return logotype.logoIsPurchased === true
    },

    packageIsPurchased(logotype) {
      return logotype.packageIsPurchased === true
    },
    async saveDesign() {
      return new Promise((resolve) => {
        let svgData = this.getDesign()
        return axios
          .post(this.route('graphics.save', this.designHash), {
            svgData: this.rot13(svgData, true)
          })
          .then((res) => {
            if (res.data.status) {
              this.designData = res.data.data
              return resolve(svgData)
            }
            console.error('saveDesign Error: ', res.data)
            resolve(false)
          })
          .catch((e) => {
            console.error('saveDesign Catch Error', e)
            return resolve(false)
          })
      })
    }
  }
}
