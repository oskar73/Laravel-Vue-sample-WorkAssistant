<template>
  <div :class="`bz-element bz-text-root ${className || ''}`" :style="textStyle">
    <div v-html="modelValue"></div>
  </div>
</template>

<script>
import { defineComponent } from 'vue'
import elementMixin from '../../mixins/elementMixin'
import { merge } from 'lodash'
export default defineComponent({
  name: 'BzText',
  mixins: [elementMixin],
  props: {
    className: {
      type: String,
      default: ''
    },
    modelValue: {
      type: [String, Number],
      default: ''
    },
    shadow: {
      type: Boolean,
      default: false
    },
    size: {
      type: String,
      default: '1em'
    },
    bold: {
      type: Boolean,
      default: false
    },
    customStyle: {
      type: [Object, undefined],
      default: undefined
    }
  },
  data() {
    return {
      fontType: 'description'
    }
  },
  computed: {
    textStyle() {
      const style = merge(this.theme.font?.[this.fontType] ?? {}, this.customStyle ?? {})

      const _bzTextStyle = {
        textShadow: this.shadow && 'rgba(0, 0, 0, 0.7) 0px 1px 3px',
        fontSize: this.size,
        fontWeight: this.bold ? 'bold' : 'normal'
      }

      if (this.color) {
        _bzTextStyle.color = this.color
      }

      if (style.fontFamily) {
        _bzTextStyle.fontFamily = style.fontFamily
      }

      if (typeof style.fontSize !== 'undefined') {
        _bzTextStyle.fontSize = style.fontSize + 'px'
      }

      if (typeof style.letterSpace !== 'undefined') {
        _bzTextStyle.letterSpacing = style.letterSpace + 'px'
      }

      if (style.color && !this.$store.state.previewPalette) {
        _bzTextStyle.color = style.color
      }

      if (typeof style.opacity !== 'undefined') {
        _bzTextStyle.opacity = style.opacity / 100
      }

      if (style.bold) {
        _bzTextStyle.fontWeight = 'bold'
      }

      if (style.italic) {
        _bzTextStyle.fontStyle = 'italic'
      }

      if (style.underline) {
        _bzTextStyle.textDecoration = 'underline'
      }

      return _bzTextStyle
    }
  }
})
</script>
