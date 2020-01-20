import { cloneDeep } from 'lodash'
import appMixin from './appMixin'
import templateMixin from './templateMixin'
import themeMixin from './themeMixin'
import eventBus from '@/public/eventBus'

export default {
  mixins: [appMixin, templateMixin, themeMixin],
  props: {
    properties: {
      type: Object,
      default: () => {
        return {}
      }
    },
    edit: {
      type: Boolean,
      default: false
    },
    preview: {
      type: Boolean,
      default: false
    },
    position: {
      type: [Number, String],
      default: undefined
    },
    viewOnly: {
      type: Boolean,
      default: false
    },
    // Fix Theme change for pages and sections
    pageData: {
      type: Object,
      default: null
    }
  },
  data() {
    return {
      type: 'section',
      isSection: true,
      isNewSection: true,
      breakPoint: 'bz-xl',
      previewItemCount: 3,
      section: {
        name: 'section',
        data: {
          items: [
            {
              title: '',
              subtitle: '',
              image: ''
            }
          ]
        },
        setting: {
          listElements: {
            dividerLine: false
          }
        },
        background: {}
      },
      sectionWidth: 0,
      textColor: '#555555'
    }
  },
  computed: {
    data: {
      get() {
        return this.section.data.data
      },
      set(val) {
        eventBus.$emit('section:update')
        this.section.data.data = val
      }
    },
    sectionData() {
      return {
        name: this.section.name
      }
    },
    isActive() {
      if (!this.activeSection) {
        return this.section === null
      }
      return this.activeSection.id === this.section.id
    },
    setting: {
      get() {
        return this.section.data.setting
      },
      set(val) {
        this.section.data.setting = val
      }
    },
    background: {
      get() {
        return this.section.data.background
      },
      set(val) {
        this.section.data.background = val
      }
    },
    fullSize() {
      return this.setting.layouts.fullSize || this.setting.layouts.fullWidth
    },
    sectionSize() {
      if (this.setting.layouts?.sectionSize) {
        return this.setting.layouts.sectionSize
      }
      return 'm'
    },
    scale() {
      return this.$parent.scale
    },
    activeSlider() {
      return this.$store.state.activeSlider
    }
  },
  watch: {
    properties: {
      immediate: true,
      deep: true,
      handler(val) {
        if (this.preview) {
          this.section = cloneDeep(val)
          if (typeof this.section.data.data === 'object') {
            if (Object.prototype.hasOwnProperty.call(val.data.data, 'items')) {
              this.section.data.data.items = val.data.data.items.slice(0, this.previewItemCount)
            }
          }
        } else {
          this.section = val
        }
      }
    },
    data: {
      deep: true,
      immediate: false,
      handler(val) {
        if (this.isWebsite) {
          // if data is changed, keep the section on switching template
          val.changed = true
        }
      }
    },
    palette: {
      deep: true,
      handler() {
        this.updateColorScheme()
      }
    }
  },
  mounted() {
    if (this.$store.state.edit) {
      // this.setFontSize()
    }
    const self = this
    new ResizeObserver(function (entries) {
      const rect = entries[0].contentRect
      const width = rect.width
      self.setBreakPoints(width)
    }).observe(self.$el)

    this.updateColorScheme()

    this.$el.addEventListener('click', () => {
      eventBus.$emit('bzn.section.selected', this)
    })
  },
  methods: {
    /**
     * Update Css variables
     */
    updateColorScheme() {
      // add css variables
      if (this.$el) {
        this.$el.style.setProperty('--bz-c-primary-box', this.primaryBoxColor)
        this.$el.style.setProperty('--bz-c-secondary', this.secondaryColor)
        this.$el.style.setProperty('--bz-c-button', this.secondaryColor)
        this.$el.style.setProperty('--bz-c-box', this.boxColor)
        this.$el.style.setProperty('--bz-c-heading', this.headingColor)
        this.$el.style.setProperty('--bz-c-social-icon', this.socialIconColor)
        this.textColor = this.getColor()
      }
    },
    /**
     *  Returns current assigned theme to the elements.
     *  @deprecated
     */
    getTheme() {
      const templateTheme = this.template.data.theme
      const storeTheme = this.$store.state.theme
      if (this.isBuilder) {
        this.theme = storeTheme || templateTheme
      } else {
        this.theme = templateTheme
      }
      this.textColor = this.getColor(this.backgroundColor, '#555555')
    },
    setFontSize() {
      const root = this.$el
      let rootFontSize = 16
      switch (this.theme && this.theme.fontSize) {
        case 's':
          rootFontSize = 14
          break
        case 'm':
          rootFontSize = 16
          break
        case 'l':
          rootFontSize = 18
          break
      }
      root.style.fontSize = rootFontSize + 'px'
    },
    setBreakPoints(width) {
      this.sectionWidth = width
      if (width < 576) {
        this.breakPoint = 'bz-xs'
      }
      if (width >= 576) {
        this.breakPoint = 'bz-sm'
      }
      if (width >= 768) {
        this.breakPoint += ' bz-md'
      }
      if (width >= 992) {
        this.breakPoint += ' bz-lg'
      }
      if (width >= 1200) {
        this.breakPoint += ' bz-xl'
      }
    }
  }
}
