<template>
  <div class="bz-el--button-root" :class="{ multiple }" :style="{ width: width || 'max-content' }">
    <bz-link :link="buttonData.link">
      <div class="button" :style="buttonStyle" :class="{ [buttonSize]: true }" @click="showEditor">
        <div class="w-100 h-100 position-relative d-flex align-items-center justify-content-center" style="background: inherit">
          <div class="title" :style="buttonTextStyle" style="background: inherit">
            <i v-if="buttonData.icon" :class="buttonData.icon" style="padding-bottom: 2px" class="mr-1"></i>
            <span :contenteditable="edit" :class="{ edit, 'text-invert': !style.color && !style.outline }" @input="handleTitleEditor">
              {{ title }}
            </span>
          </div>
        </div>
      </div>
    </bz-link>
  </div>
</template>

<script>
import { createApp } from 'vue'
import elementMixin from '../../mixins/elementMixin'
import ButtonEditor from '../editor/ButtonEditor.vue'
import { cloneDeep, merge } from 'lodash'
import BzLink from './BzLink.vue'
import ClickOutside from '@/public/directives/ClickOutside'
import eventBus from '@/public/eventBus'

export default {
  name: 'BzButton',
  components: { BzLink },
  mixins: [elementMixin],
  props: {
    title: {
      type: String,
      default: ''
    },
    multiple: {
      type: Boolean,
      default: false
    },
    last: {
      type: Boolean,
      default: false
    },
    start: {
      type: Boolean,
      default: false
    },
    index: {
      type: Number,
      default: 0
    },
    link: {
      type: Boolean,
      default: true
    },
    width: {
      type: String,
      default: ''
    },
    rounded: {
      type: [Boolean, String, Number],
      default: 4
    },
    bgColor: {
      type: [String],
      default: ''
    },
    customStyle: {
      type: [Object, undefined],
      default: undefined
    }
  },
  data () {
    return {
      titleText: '',
      openColorForm: false,
      buttonData: {
        title: 'Button Title',
        size: 's',
        outline: false
      }
    }
  },
  computed: {
    buttonBackgroundColor () {
      return this.bgColor || this.buttonColor || '#0076df'
    },
    buttonSize () {
      return this.buttonData.size
    },
    style () {
      const buttonTheme = cloneDeep(this.theme.button ?? {})
      const _style = merge(buttonTheme ?? {}, this.customStyle ?? {})

      if (this.buttonData.textColor) {
        _style.color = this.buttonData.textColor
      }

      if (this.buttonData.outline === true) {
        _style.outline = true
      }

      return _style
    },
    buttonStyle () {
      const _bzButtonStyle = {
        background: this.buttonData.outline ? 'transparent' : this.buttonBackgroundColor,
        color: this.getColor(this.buttonBackgroundColor),
        border: 'solid 1px',
        borderRadius: this.rounded === true ? '1000px' : this.rounded + 'px',
        borderColor: this.buttonBackgroundColor,
        ...(this.width ? { width: this.width } : {}),
        '--bz-button-hover-bg-color': window.getHoverColor(this.buttonBackgroundColor)
      }
      const style = this.style
      if (this.buttonData.backgroundColor !== undefined) {
        style.bgColor = this.buttonData.backgroundColor
      }

      if (style?.round) {
        _bzButtonStyle.borderRadius = style?.round + 'px'
      }

      if (style?.color) {
        _bzButtonStyle.color = style?.color
      }

      // with the palette preview mode, button color should show palette's button color.
      if (this.$store.state.previewPalette && !this.buttonData.backgroundColor) {
        _bzButtonStyle.background = this.buttonColor
        _bzButtonStyle.borderColor = this.buttonColor
      } else {
        if (style?.bgColor) {
          if (style?.outline) {
            _bzButtonStyle.background = 'transparent'
          } else {
            _bzButtonStyle.background = style?.bgColor
          }
          _bzButtonStyle.borderColor = style?.bgColor
        }
      }

      if (style?.hoverBgColor) {
        if (style?.hoverOutline) {
          _bzButtonStyle['--bz-button-hover-bg-color'] = 'transparent'
        } else {
          _bzButtonStyle['--bz-button-hover-bg-color'] = style.hoverBgColor
        }
        _bzButtonStyle['--bz-button-hover-border-color'] = style.hoverBgColor
      }

      if (typeof style?.hoverOpacity !== 'undefined') {
        _bzButtonStyle['--bz-button-hover-opacity'] = style.hoverOpacity / 100
      }

      return _bzButtonStyle
    },
    buttonTextStyle () {
      const _bzTextStyle = { color: this._textColor }
      const style = this.style

      if (typeof style?.padding !== 'undefined') {
        _bzTextStyle.padding = style.padding + 'px'
      }

      if (style?.bold) {
        _bzTextStyle.fontWeight = 'bold'
      }

      if (style?.italic) {
        _bzTextStyle.fontStyle = 'italic'
      }

      if (style?.underline) {
        _bzTextStyle.textDecoration = 'underline'
      }

      if (style?.fontSize) {
        _bzTextStyle.fontSize = style.fontSize + 'px'
      }

      if (typeof style?.fontOpacity !== 'undefined') {
        _bzTextStyle.opacity = style.fontOpacity / 100
      }

      if (style?.fontFamily) {
        _bzTextStyle.fontFamily = style.fontFamily
      }

      if (typeof style?.letterSpace !== 'undefined') {
        _bzTextStyle.letterSpacing = style.letterSpace + 'px'
      }

      if (style?.color) {
        _bzTextStyle.color = style.color
      }

      if (style?.opacity) {
        _bzTextStyle.opacity = style.opacity / 100
      }

      if (style?.hoverColor) {
        _bzTextStyle['--bz-button-hover-color'] = style.hoverColor
      }

      return _bzTextStyle
    },
    _textColor () {
      if (this.textColor) {
        return this.textColor
      }
      return window.getColor(this.buttonBackgroundColor)
    }
  },
  watch: {
    buttonData: {
      deep: true,
      handler () {
        if (this.modelValue === this.buttonData) {
          eventBus.$emit('button:update')
        }
        this.$emit('update:modelValue', this.buttonData)
      }
    }
  },
  created () {
    this.buttonData = merge(this.buttonData, this.modelValue ?? {})
    this.titleText = this.buttonData.title
  },
  methods: {
    click () {
      this.$emit('click')
    },
    showEditor (e) {
      if (this.edit) {
        e.target.preventDefault()
        const buttonEditor = document.getElementById('bz-element-editor')

        const iFrame = document.getElementById('bz-page-content-frame')
        const iFrameRect = iFrame.getBoundingClientRect()

        const rect = this.$el.getBoundingClientRect()
        let top = rect.top + iFrameRect.top - 35

        if (top < 360) {
          top += 85
        }

        const left = rect.left + iFrameRect.left
        const self = this
        if (window.bzElementEditor) {
          window.bzElementEditor.unmount()
          window.bzElementEditor = null
        }
        window.bzElementEditor = createApp(ButtonEditor, {
          ...self.$props,
          backgroundColor: self.backgroundColor,
          button: self.$el,
          top,
          left,
          modelValue: self.buttonData,
          theme: this.theme,
          pageData: this.pageData,
          colors: {
            backgroundColor: self.style.bgColor,
            textColor: self.style.color
          },
          onClear: () => {
            console.log('ButtonCustomStyleClearEvent')
            this.buttonData = {
              title: this.buttonData.title
            }
            this.titleText = this.buttonData.title
          }
        })
        window.bzElementEditor.use(self.$store)
        window.bzElementEditor.mixin({
          watch: {
            data (value) {
              self.buttonData = value
            }
          },
          methods: {
            handleAddButton () {
              self.$emit('add', self.index)
            },
            handleRemoveButton () {
              self.$emit('delete', self.index)
              window.bzElementEditor.unmount()
              window.bzElementEditor = null
              self.$el.remove()
            }
          }
        })
        window.bzElementEditor.directive('click-outside', ClickOutside)
        window.bzElementEditor.mount(buttonEditor)
      }
    },
    handleTitleEditor (e) {
      this.buttonData.title = e.target.innerText
    }
  }
}
</script>
<style lang="scss" scoped>
.bz-el--button-root {
  width: max-content;
  display: flex;
  position: relative;
  cursor: pointer;

  &.multiple {
    margin-right: 10px;
    margin-top: 10px;
  }

  .button {
    transition: all linear 0.2s;

    &.s {
      padding: 0 15px;
      font-size: 14px;
    }

    &.m {
      padding: 2px 25px;
      font-size: 16px;
    }

    &.l {
      padding: 4px 35px;
      font-size: 18px;
    }

    &:hover {
      background-color: var(--bz-button-hover-bg-color) !important;
      border-color: var(--bz-button-hover-border-color) !important;
      opacity: var(--bz-button-hover-opacity) !important;
    }

    &:hover {
      .title:first-child {
        span {
          color: var(--bz-button-hover-color) !important;
        }
      }
    }

    .title:first-child {
      position: relative;
      display: flex;
      justify-content: center;
      align-items: center;

      span {
        padding: 2px;
        border: solid 2px transparent;
        border-radius: 2px;

        &.edit {
          cursor: text;

          &:focus,
          &:active,
          &:hover {
            border: solid 2px var(--bz-section-edit-active-color);
            transition: border 0.5s;
          }
        }
      }
    }
  }
}
</style>
