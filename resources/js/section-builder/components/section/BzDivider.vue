<template>
  <div class="bz-divider-root" :style="dividerStyle" :class="{ vertical }"></div>
</template>

<script>
import elementMixin from '../../mixins/elementMixin'

export default {
  name: 'BzDivider',
  mixins: [elementMixin],
  props: {
    line: {
      type: Boolean,
      default: false
    },
    height: {
      type: Number,
      default: 30
    },
    thickness: {
      type: Number,
      default: 1
    },
    width: {
      type: [String, Number],
      default: '100%'
    },
    vertical: {
      type: Boolean,
      default: false
    },
    lineColor: {
      type: String,
      default: ''
    }
  },
  computed: {
    theme() {
      let parent = this.$parent
      let theme = null
      while (parent) {
        theme = parent.theme
        if (theme) return theme
        parent = parent.$parent
      }
      throw 'elementMixin: theme is undefined'
    },
    dividerStyle() {
      if (this.vertical) {
        return {
          height: this.height + 'px',
          backgroundColor: this.lineColor || this.dividerColor
        }
      } else {
        let width = this.width
        if (!isNaN(width)) {
          width = width + 'px'
        }

        if (this.line) {
          return {
            height: this.thickness + 'px',
            marginTop: this.height / 2 + 'px',
            marginBottom: this.height / 2 + 'px',
            backgroundColor: this.lineColor || this.dividerColor,
            borderRadius: 100 + 'px',
            width
          }
        }

        return {
          backgroundColor: 'transparent',
          height: this.height + 'px',
          width
        }
      }
    }
  }
}
</script>
<style lang="scss">
.bz-divider-root {
  width: 100%;

  &.vertical {
    width: 1px;
    margin: 0 5px;
  }
}
</style>
