import { mapMutations } from 'vuex'
import templateMixin from './templateMixin'
import modulesPageMap from '../data/modulesPageMap'

export default {
  mixins: [templateMixin],
  data() {
    return {
      appURL: '',
      appDomain: '',
      contact: {
        data: {
          firstName: '',
          lastName: '',
          email: '',
          phone: '',
          message: '',
          subject: '',
          address: '',
          date: ''
        },
        submitted: false,
        success: false
      }
    }
  },
  computed: {
    isBuilder() {
      return this.$store.state.isBuilder
    },
    isWebsite() {
      return !this.$config.isTemplate
    },
    isTemplate() {
      return this.$config.isTemplate
    },
    modules() {
      return this.$store.state.modules
    },
    activePage: {
      get() {
        return modulesPageMap(this.allPages, this.$route)
      },
      set(val) {
        this.allPages[this.indexOfActivePage] = val
      }
    },
    activePageData() {
      if (this.activePage) {
        return {
          pageId: this.activePage.id,
          index: this.indexOfActivePage
        }
      } else {
        console.warn('appMixin.activePageData: activePage is undefined')
        return {}
      }
    },
    activeSections: {
      get() {
        if (this.activePage) {
          return this.activePage.sections || []
        }
        return []
      },
      set(value) {
        this.activePage.sections = value
      }
    },
    visibleSections() {
      return this.activeSections.filter((section) => section && (this.activePage.type === 'new-page' || section.data.setting.visible))
    },
    panelArrow() {
      return this.$store.state.panelArrow
    },
    addPosition: {
      get() {
        return this.$store.state.addPosition
      },
      set(value) {
        this.setAddPosition(value)
      }
    },
    activePosition: {
      get() {
        return this.$store.state.activePosition
      },
      set(value) {
        this.setActivatePosition(value)
      }
    },
    // return active section, currently focused section.
    activeSection: {
      get() {
        let activeSection = null
        if (this.activePosition === 'header') {
          activeSection = this.header
        } else if (this.activePosition === 'footer') {
          activeSection = this.footer
        } else {
          activeSection = this.activeSections[this.activePosition]
        }
        return activeSection
      },
      set(value) {
        if (this.activePosition === 'header') {
          this.header = value
        }
        if (this.activePosition === 'footer') {
          this.footer = value
        }
        this.activeSections[this.activePosition] = value
      }
    },
    activeSetting() {
      if (this.activeSection) {
        return this.activeSection.data.setting
      }
      return null
    },
    activeCompanyIndex: {
      get() {
        return this.$store.state.activeCompanyIndex
      },
      set(value) {
        this.$store.commit('setActiveCompanyIndex', value)
      }
    },
    loadingData: {
      get() {
        return this.$store.state.loadingData
      },
      set(value) {
        this.$store.commit('updateLoadingData', value)
      }
    }
  },
  methods: {
    checkTemplate() {
      console.log('template', this.$store.state.template === this.template)
      console.log('pages', this.template.pages === this.allPages)
      console.log('active page', this.activePage === this.allPages[this.indexOfActivePage])
      console.log('sections', this.activePage.sections === this.activeSections)
      console.log('active section', this.activePage.sections[this.activePosition] === this.activeSection)
    },
    s3_asset(path) {
      return window.config.s3AssetBaseUrl + path
    },
    /**
     *  Validates Email address
     */
    validateEmail(mail) {
      return /^\w+([.-]?\w+)*@\w+([.-]?\w+)*(\.\w{2,3})+$/.test(mail)
    },
    removeBusiness(index) {
      this.$dialog.confirm().then((res) => {
        if (res) {
          this.templateSetting.businesses.splice(index, 1)
        }
      })
    },
    pageUrl(url) {
      if (url) {
        if (!url.startsWith('/')) {
          url = `/${url}`
        }
      } else {
        url = '/'
      }
      if (this.$config?.basePath && this.$config.basePath !== '/') {
        if (this.$config.basePath.startsWith('/')) {
          return this.$config.basePath + url
        } else {
          return '/' + this.$config.basePath + url
        }
      }
      return url
    },
    isActiveMenu(url, switchPreview = false) {
      if (switchPreview) {
        return this.$store.state.indexOfActiveViewPage === url
      }
      if (this.edit) {
        return this.activePage?.url === url
      } else {
        return window.location.pathname === this.pageUrl(url)
      }
    },
    componentFromStr(numStr, percent) {
      const num = Math.max(0, parseInt(numStr, 10))
      return percent ? Math.floor((255 * Math.min(100, num)) / 100) : Math.min(255, num)
    },
    /**
     *  Returns text color which is contrast to the background color.
     */
    getColor(bgColor) {
      if (!bgColor) {
        bgColor = this.backgroundColor
      }
      bgColor = bgColor.replace(/ /g, '')
      if (bgColor.includes('rgb')) {
        bgColor = this.rgbToHex(bgColor)
      }
      const color = bgColor.charAt(0) === '#' ? bgColor.substring(1, 7) : bgColor
      const r = parseInt(color.substring(0, 2), 16) // hexToR
      const g = parseInt(color.substring(2, 4), 16) // hexToG
      const b = parseInt(color.substring(4, 6), 16) // hexToB
      return r * 0.299 + g * 0.587 + b * 0.114 > 186 ? '#555555' : '#ffffff'
    },
    rgbToHex(rgb) {
      const rgbRegex = /^rgb\(\s*(-?\d+)(%?)\s*,\s*(-?\d+)(%?)\s*,\s*(-?\d+)(%?)\s*\)$/
      let result
      let r
      let g
      let b
      let hex = ''
      if ((result = rgbRegex.exec(rgb))) {
        r = this.componentFromStr(result[1], result[2])
        g = this.componentFromStr(result[3], result[4])
        b = this.componentFromStr(result[5], result[6])
        hex = '#' + (0x1000000 + (r << 16) + (g << 8) + b).toString(16).slice(1)
      }
      return hex
    },
    getBrightness(color) {
      if (color.includes('rgb')) {
        color = this.rgbToHex(color)
      }
      color = color.replace(/ /g, '').replace('#', '')
      const r = parseInt(color.substring(0, 2), 16)
      const g = parseInt(color.substring(2, 4), 16)
      const b = parseInt(color.substring(4, 6), 16)

      return (299 * r + 587 * g + 114 * b) / 1000
    },
    isBrighter(color1, color2) {
      return this.getBrightness(color1) > this.getBrightness(color2)
    },
    ...mapMutations({
      setActivatePosition: 'setActivatePosition'
    })
  }
}
