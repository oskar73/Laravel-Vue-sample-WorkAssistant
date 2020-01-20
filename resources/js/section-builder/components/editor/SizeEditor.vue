<template>
  <div id="pane" :class="{ isResizing, edit, [direction]: true }" :style="getStyle" @mousedown="onMouseDown">
    <slot />
    <div class="resize-handler"></div>
  </div>
</template>
<script>
import elementMixin from '../../mixins/elementMixin'

const MARGINS = 4
export default {
  name: 'SizeEditor',
  mixins: [elementMixin],
  props: {
    modelValue: {
      type: [Object, Array],
      default: () => {
        return {
          width: undefined,
          height: undefined
        }
      }
    },
    minWidth: {
      type: Number,
      default: 60
    },
    minHeight: {
      type: Number,
      default: 40
    },
    initialWidth: {
      type: String,
      default: '100%'
    },
    direction: {
      type: String,
      default: 'right-bottom'
    }
  },
  data() {
    return {
      clicked: null,
      onRightEdge: false,
      onBottomEdge: false,
      onLeftEdge: false,
      onTopEdge: false,
      b: 0,
      x: 0,
      y: 0,
      redraw: false,
      preSnapped: null,
      e: null,
      isResizing: false,
      width: undefined,
      height: undefined
    }
  },
  computed: {
    getStyle() {
      const style = {}
      if (this.width) {
        if (typeof this.width === 'string') {
          style.width = this.width + '!important'
        } else {
          style.width = this.width + 'px !important'
        }
      }
      if (this.height) {
        style.height = this.height + 'px !important'
      }
      return style
    }
  },
  mounted() {
    if (this.edit) {
      document.addEventListener('mousemove', this.onMouseMove)
      document.addEventListener('mouseup', this.onMouseUp)
    }
    this.width = this.modelValue.width || this.initialWidth
    this.height = this.modelValue.height
  },
  methods: {
    onMouseDown(e) {
      this.onDown(e)
      e.preventDefault()
    },
    onDown(e) {
      this.calc(e)

      this.isResizing = this.onRightEdge || this.onBottomEdge || this.onTopEdge || this.onLeftEdge

      this.clicked = {
        x: this.x,
        y: this.y,
        cx: e.clientX,
        cy: e.clientY,
        w: this.b.width,
        h: this.b.height,
        isResizing: this.isResizing,
        onTopEdge: this.onTopEdge,
        onLeftEdge: this.onLeftEdge,
        onRightEdge: this.onRightEdge,
        onBottomEdge: this.onBottomEdge
      }
    },
    calc(e) {
      this.b = this.$el.getBoundingClientRect()

      let newWidth = e.clientX - this.b.left
      const newHeight = e.clientY - this.b.top

      if (this.direction === 'left-bottom') {
        newWidth = this.b.right - e.clientX
      }

      this.x = newWidth / this.scale
      this.y = newHeight / this.scale

      this.onTopEdge = this.y < MARGINS
      this.onLeftEdge = Math.abs(e.clientX - this.b.left) < MARGINS
      this.onRightEdge = this.x >= this.b.width - MARGINS
      this.onBottomEdge = this.y >= this.b.height - MARGINS
    },
    onMouseMove(e) {
      if (this.edit) {
        this.calc(e)
        this.redraw = true
        this.animate(e)
      }
    },
    onMouseUp(e) {
      this.$emit('update:modelValue', { width: this.width, height: this.height })
      this.calc(e)
      this.clicked = null
      this.isResizing = false
    },
    animate(e) {
      requestAnimationFrame(this.animate)
      if (!this.redraw) return
      this.redraw = false

      if (this.clicked && this.clicked.isResizing) {
        switch (this.direction) {
          case 'right-bottom': {
            if (this.clicked.onRightEdge && this.clicked.onBottomEdge) {
              this.width = Math.max(this.x, this.minWidth)
              this.height = Math.max(this.y, this.minHeight)
            }
            break
          }
          case 'left-bottom': {
            if (this.clicked.onLeftEdge && this.clicked.onBottomEdge) {
              this.width = Math.max(this.x, this.minWidth)
              this.height = Math.max(this.y, this.minHeight)
            }
            break
          }
          default:
            break
        }

        // if (this.clicked.onBottomEdge) {
        //   this.height = Math.max(this.y, this.minHeight)
        // }
        // if (this.clicked.onLeftEdge) {
        //   const currentWidth = Math.max(this.clicked.cx - e.clientX + this.clicked.w, this.minWidth)
        //   if (currentWidth > this.minWidth) {
        //     this.width = currentWidth
        //   }
        // }
        //
        // if (this.clicked.onTopEdge) {
        //   const currentHeight = Math.max(this.clicked.cy - e.clientY + this.clicked.h, this.minHeight)
        //   if (currentHeight > this.minHeight) {
        //     this.height = currentHeight
        //   }
        // }
      }

      let cursor = 'default'

      if (this.direction === 'right-bottom' && this.onRightEdge && this.onBottomEdge) {
        cursor = 'nwse-resize'
      }

      if (this.direction === 'left-bottom' && this.onLeftEdge && this.onBottomEdge) {
        cursor = 'nesw-resize'
      }

      this.$el.style.cursor = cursor
      // if ((this.onRightEdge && this.onBottomEdge) || (this.onLeftEdge && this.onTopEdge)) {
      //   this.$el.style.cursor = 'nwse-resize'
      // } else if ((this.onRightEdge && this.onTopEdge) || (this.onBottomEdge && this.onLeftEdge)) {
      //   this.$el.style.cursor = 'nesw-resize'
      // } else if (this.onRightEdge || this.onLeftEdge) {
      //   this.$el.style.cursor = 'ew-resize'
      // } else if (this.onBottomEdge || this.onTopEdge) {
      //   this.$el.style.cursor = 'ns-resize'
      // } else {
      //   this.$el.style.cursor = 'default'
      // }
    }
  }
}
</script>

<style lang="scss">
#pane {
  margin: 0;
  padding: 0;
  z-index: 99;
  position: relative;
  border: solid 1px transparent;
  width: max-content;

  .resize-handler {
    position: absolute;
    width: 12px !important;
    height: 12px !important;
    background-color: white;
    border: solid 1px #808080;
    border-radius: 12px;
    display: none;
  }

  &.edit {
    &.isResizing,
    &:hover {
      border: 1px dashed var(--bz-theme-text-color);

      .resize-handler {
        display: block;
      }
    }
  }

  & > * {
    width: 100% !important;
    height: 100% !important;
    max-width: 100% !important;
  }

  &.right-bottom {
    .resize-handler {
      right: -6px;
      top: calc(100% - 6px);
    }
  }

  &.left-bottom {
    .resize-handler {
      left: -6px;
      top: calc(100% - 6px);
    }
  }
}
</style>
