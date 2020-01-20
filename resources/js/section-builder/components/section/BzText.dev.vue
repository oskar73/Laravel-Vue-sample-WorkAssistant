<template>
  <div class="bz-element bz-text-root" :class="{ edit }" :style="textStyle" @mouseup="showTextEditor">
    <div ref="contentRef" :contenteditable="edit" data-editor-element="true" :data-empty="!data" :data-refresh="refresh" @input="handleContentChange" v-html="content"></div>
  </div>
</template>

<script>
import TextEditor from '../editor/TextEditor.vue'
import elementMixin from '../../mixins/elementMixin'
import { createApp } from 'vue'
import { merge } from 'lodash'
import ClickOutside from '@/public/directives/ClickOutside'
import eventBus from '@/public/eventBus'

export default {
  name: 'BzText',
  mixins: [elementMixin],
  props: {
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
    mb: {
      type: [Number, String],
      default: '0.5em'
    },
    link: {
      type: [Boolean, Object],
      default: false
    },
    // If this is true, show link button to text editor toolbar, button text editor should not have link
    withLink: {
      type: [Boolean],
      default: false
    },
    customStyle: {
      type: [Object, undefined],
      default: undefined
    }
  },
  data() {
    return {
      content: '',
      fontType: 'description',
      selection: null,
      text: ''
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

      if (this.getTextColor()) {
        _bzTextStyle.color = this.getTextColor()
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
    },
    refresh: {
      get() {
        return this.$store.state.refreshEditor
      },
      set(value) {
        this.$store.commit('setStore', {
          path: 'refreshEditor',
          value
        })
      }
    }
  },
  watch: {
    refresh() {
      this.content = this.modelValue
    },
    indexOfActivePage() {
      this.content = this.modelValue
    },
    modelValue() {
      if (this.text !== this.modelValue) {
        this.text = this.modelValue
        this.$refs.contentRef.innerHTML = this.modelValue
      }
    }
  },
  created() {
    this.content = this.modelValue
  },
  methods: {
    showTextEditor() {
      if (this.edit) {
        const textEditor = document.getElementById('bz-element-editor')

        const iFrame = document.getElementById('bz-page-content-frame')
        const iFrameRect = iFrame.getBoundingClientRect()

        const rect = this.$el.getBoundingClientRect()
        const top = rect.top + iFrameRect.top - 35

        const left = rect.left + iFrameRect.left + 15
        const self = this

        if (window.bzElementEditor) {
          window.bzElementEditor.unmount()
          window.bzElementEditor = null
        }

        window.bzElementEditor = createApp(TextEditor, {
          ...self.$props,
          backgroundColor: self.backgroundColor,
          top,
          left,
          link: this.link,
          theme: this.theme,
          withLink: this.withLink,
          onClear: () => {
            console.log('TextCustomStyleClearEvent')
            const newData = this.data.replace(/<\/?(font|span|u|i|b)+([^>]+>|>)/gi, '')
            console.log(newData)
            this.data = newData
            this.$refs.contentRef.innerHTML = newData
          },
          onChangeLink: (newLink) => {
            console.log('TextChangeLinkEvent')
            this.$emit('update:modelValue', this.$refs.contentRef.innerHTML)
            if (this.withLink) {
              self.$emit('changeLink', newLink)
            }
          }
        })
        window.bzElementEditor.use(self.$store)
        window.bzElementEditor.mixin({
          watch: {
            data(value) {
              self.data = value
            }
          }
        })
        window.bzElementEditor.directive('click-outside', ClickOutside)
        window.bzElementEditor.mount(textEditor)
      }
    },
    handleContentChange(e) {
      this.$nextTick(() => {
        eventBus.$emit('text:update')
        this.text = e.target.innerHTML
        this.$emit('update:modelValue', e.target.innerHTML)
      })
    },
    clearSelection() {
      if (window.getSelection) {
        if (window.getSelection().empty) {
          // Chrome
          window.getSelection().empty()
        } else if (window.getSelection().removeAllRanges) {
          // Firefox
          window.getSelection().removeAllRanges()
        }
      } else if (document.selection) {
        // IE?
        document.selection.empty()
      }
    }
  }
}
</script>
<style lang="scss">
.bz-text-root {
  cursor: text;

  &.edit {
    margin: -4px -8px;
    padding: 4px 8px;

    &:focus,
    &:hover {
      outline: solid 1px #0076dfff !important;
    }

    & > div {
      min-width: 10px;
      cursor: text;

      &[data-empty='true'] {
        width: 120px;
        position: relative;

        &:after {
          content: 'Type your text';
          position: absolute;
          color: grey;
          font-style: italic;
        }
      }
    }
  }
}
</style>
