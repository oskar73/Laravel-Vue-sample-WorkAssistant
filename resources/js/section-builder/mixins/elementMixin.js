import themeMixin from './themeMixin'
import appMixin from './appMixin'
import templateMixin from './templateMixin'
import eventBus from '@/public/eventBus'

export default {
  mixins: [appMixin, themeMixin, templateMixin],
  props: {
    modelValue: {
      type: [Object, String, Boolean, Number, Array, Date, undefined],
      default: undefined
    },
    textColor: {
      type: String,
      default: ''
    },
    editMode: {
      type: Boolean,
      default: undefined
    },
    bgColor: {
      type: String,
      default: ''
    },
    viewOnly: {
      type: Boolean,
      default: false
    }
  },
  data() {
    return {
      dividerColor: '#808080',
      isMounted: null,
      color: null
    }
  },
  computed: {
    edit() {
      let edit = this.editMode
      if (typeof edit !== 'undefined') {
        return Boolean(edit)
      }
      let parent = this.$parent
      while (parent) {
        edit = edit || parent.edit
        if (edit !== undefined) return edit
        parent = parent.$parent
      }
      return false
    },
    scale() {
      return this.$parent.scale
    },
    theme() {
      let parent = this.$parent
      let theme
      while (parent) {
        theme = theme || parent.theme
        if (theme) return theme
        parent = parent.$parent
      }
      throw 'elementMixin: theme is undefined'
    },
    pageData() {
      let parent = this.$parent
      let pageData = null
      while (parent) {
        pageData = parent.pageData
        if (pageData) return pageData
        parent = parent.$parent
      }
      return {}
    },
    sectionData() {
      let parent = this.$parent
      let _sd = null
      while (parent) {
        _sd = parent.sectionData
        if (_sd) return _sd
        parent = parent.$parent
      }
      throw 'elementMixin: sectionData is undefined'
    },
    sectionType() {
      if (this.sectionData.name.startsWith('Header')) {
        return 'header'
      } else if (this.sectionData.name.startsWith('Footer')) {
        return 'footer'
      } else {
        return 'main'
      }
    },
    position() {
      let parent = this.$parent
      let position
      while (parent) {
        position = parent.position
        if (position !== undefined) return position
        parent = parent.$parent
      }
    },
    backgroundColor() {
      if (this.bgColor) {
        return this.bgColor
      }

      let parent = this.$parent
      let bgColor
      while (parent) {
        bgColor = parent.backgroundColor
        if (bgColor !== undefined) return bgColor
        parent = parent.$parent
      }

      return this.palette.backgroundColor || '#ffffff'
    },
    data: {
      get() {
        return this.modelValue
      },
      set(val) {
        this.$emit('update:modelValue', val)
      }
    }
  },
  watch: {
    backgroundColor() {
      this.color = this.getTextColor()
    }
  },
  created() {
    const self = this
    eventBus.$on('refreshSectionTheme', () => {
      self.color = self.getTextColor()
    })
  },
  mounted() {
    this.isMounted = true
    this.color = this.getTextColor()
  },
  methods: {
    getTextColor() {
      let _textColor = this.textColor
      let _bgColor = this.backgroundColor
      if (this.isMounted && this.$el) {
        let parentNode = this.$el.parentNode
        let parentBgColor = null
        while (parentNode && !parentBgColor) {
          if (parentBgColor || (parentNode.classList && parentNode.classList.contains('bz-section-container'))) {
            break
          }
          parentBgColor = parentNode.style?.backgroundColor
          parentNode = parentNode.parentNode
        }
        if (parentBgColor) {
          _bgColor = parentBgColor
        }
      }
      _textColor = _textColor || this.getColor(_bgColor)
      _textColor = '#' + window.tinycolor(_textColor).toHex()
      return _textColor
    }
  }
}
