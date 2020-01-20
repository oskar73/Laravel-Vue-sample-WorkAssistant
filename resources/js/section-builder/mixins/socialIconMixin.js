import { merge } from 'lodash'
import elementMixin from './elementMixin'

export default {
  mixins: [elementMixin],
  props: {
    url: {
      type: String,
      default: 'javascript:void(0)'
    },
    options: {
      type: [Object, undefined],
      default: undefined
    }
  },
  computed: {
    socialItemStyle() {
      const _iconOutlineStyle = {
        padding: '4px',
        height: 'max-content',
        width: 'max-content',
        display: 'block'
      }

      const _option = this.getOptions()

      if (_option && !_option.noOutline) {
        if (_option.outlineColor) {
          if (!_option.outlineOnly) {
            _iconOutlineStyle.backgroundColor = _option.outlineColor
          }
          _iconOutlineStyle.border = 'solid 2px'
          _iconOutlineStyle.borderColor = _option.outlineColor
        }

        if (_option.outlineCorner) {
          _iconOutlineStyle.borderRadius = _option.outlineCorner + 'px'
        }
      }

      if (_option.hoverOpacity) {
        _iconOutlineStyle['--bz-hover-opacity'] = _option.hoverOpacity / 100
      }

      return _iconOutlineStyle
    },
    fill() {
      const _option = this.getOptions()

      // with the palette preview mode, social icon color should show palette's social icon.
      if (this.$store.state.previewPalette) {
        return this.socialIconColor
      }

      if (!_option || !_option.iconColor) {
        return this.socialIconColor
      }
      return _option.iconColor
    },
    size() {
      const _option = this.getOptions()
      if (!_option || _option.iconSize === undefined) {
        return 24
      }
      return _option.iconSize
    }
  },
  methods: {
    goToUrl() {
      if (this.url == 'javascript:void(0)') return

      window.open(this.url, '_blank')
    },
    getOptions() {
      let _themeOptions
      if (this.theme.socialIcon?.[this.sectionType]) {
        if (this.theme.socialIcon?.[this.sectionType].individual) {
          if (this.theme.socialIcon?.[this.sectionType][this.name]) {
            _themeOptions = this.theme.socialIcon?.[this.sectionType][this.name]
          } else {
            _themeOptions = this.theme.socialIcon?.[this.sectionType].group
          }
        } else {
          _themeOptions = this.theme.socialIcon?.[this.sectionType].group
        }
      }

      return merge(this.options ?? {}, _themeOptions ?? {})
    }
  }
}
