import sectionMixin from './sectionMixin'
import { merge } from 'lodash'
import Cart from '@/section-builder/utils/cart'

export default {
  mixins: [sectionMixin],
  data() {
    return {
      category: 'header',
      openMenu: false,
      defaultSectionData: {
        background: {
          connectToSectionBelow: false
        }
      },
      cart: null,
      isLoggedIn: window.config.auth
    }
  },
  created() {
    if (this.modules.activeModules?.includes('ecommerce')) this.cart = new Cart(window.websiteId)
  },
  computed: {
    headerButtonColor() {
      return this.buttonColor.brighten(30)
    }
  },
  watch: {
    background: {
      deep: true,
      handler() {
        this.setBackground()
      }
    },
    textColor: {
      immediate: true,
      handler(value) {
        this.$nextTick(() => {
          this.$el?.style.setProperty('--bz-header-menu-item-color', value)
        })
      }
    }
  },
  mounted() {
    const self = this
    new ResizeObserver((rect) => {
      self.setBackground()
    }).observe(this.$el)
    this.section = merge(this.section, { data: this.defaultSectionData })
  },
  methods: {
    toggleMenu() {
      if (!this.edit) {
        this.openMenu = !this.openMenu
      }
    },
    setBackground() {
      if (!this.preview) {
        if (this.background.connectToSectionBelow) {
          document.documentElement.style.setProperty('--navigate-bar-height', this.$el.clientHeight + 'px')
        } else {
          document.documentElement.style.setProperty('--navigate-bar-height', '0px')
        }
      }
    },
    handleLogoChange(value) {
      this.setting.logoSize = value
    },
    auth(route) {
      if (window.location.href.includes('/website')) return
      window.location.href = route
    }
  }
}
