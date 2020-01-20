<template>
  <div v-click-outside="handleOutsideClick" class="text edit-mode bz-text-editor-root" :style="{ left: left + 'px', top: top + 'px' }" @mousedown.prevent.stop="">
    <div class="item-wrapper">
      <div class="item" @click="openLink">
        <i class="mdi mdi-link tw-text-base"></i>
      </div>
    </div>
    <div class="divider"></div>

    <div class="item-wrapper" @mousedown.prevent.stop="execAction('bold')">
      <div class="item" :class="{ selected: isBold }">
        <i class="mdi mdi-format-bold"></i>
      </div>
    </div>
    <div class="item-wrapper" @mousedown.prevent.stop="execAction('italic')">
      <div class="item" :class="{ selected: isItalic }"><i class="mdi mdi-format-italic"></i></div>
    </div>
    <div class="item-wrapper" @mousedown.prevent.stop="execAction('underline')">
      <div class="item" :class="{ selected: isUnderline }"><i class="mdi mdi-format-underline"></i></div>
    </div>

    <div class="divider"></div>

    <div class="item-wrapper" @mousedown.prevent.stop="execAction('justifyLeft', true)">
      <div class="item">
        <i class="mdi mdi-format-align-left"></i>
      </div>
    </div>
    <div class="item-wrapper" @mousedown.prevent.stop="execAction('justifyCenter', true)">
      <div class="item">
        <i class="mdi mdi-format-align-center"></i>
      </div>
    </div>
    <div class="item-wrapper" @mousedown.prevent.stop="execAction('justifyRight', true)">
      <div class="item">
        <i class="mdi mdi-format-align-right"></i>
      </div>
    </div>
    <div class="item-wrapper" @mousedown.prevent.stop="execAction('justifyFull', true)">
      <div class="item">
        <i class="mdi mdi-format-align-justify"></i>
      </div>
    </div>

    <div class="divider"></div>
    <div class="item-wrapper" @mousedown.prevent.stop="decreaseFontSize()">
      <div class="item">
        <i class="mdi mdi-minus"></i>
      </div>
    </div>
    <div class="font-size" @mousedown.prevent.stop="">
      <span class="tw-text-sm">{{ fontSize }}px</span>
    </div>

    <div class="item-wrapper" @mousedown.prevent.stop="increaseFontSize()">
      <div class="item">
        <i class="mdi mdi-plus"></i>
      </div>
    </div>

    <div class="divider"></div>

    <div class="item-wrapper">
      <div class="item" @click="openColorPanel = !openColorPanel">
        <bz-format-color-text-icon :size="20" fill-color="#555555" :color="textColor" />
      </div>
    </div>

    <div class="divider"></div>

    <div class="item-wrapper">
      <div class="item" @click="handleClear">
        <i class="mdi mdi-close"></i>
      </div>
    </div>

    <div class="divider"></div>

    <div class="item-wrapper" @click="openIconSelector">
      <div class="item icon">
        <dvr-icon :size="16" :fill-color="'#555555'" />
      </div>
    </div>

    <div v-if="openColorPanel" class="color-panel" :class="{'bottom': top < 360}">
      <sketch :value="textColor" @input="setCustomColor" @update:modelValue="setColor" />
    </div>
  </div>
</template>

<script>
import editorMixin from '../../mixins/editorMixin'
import BzFormatColorTextIcon from '../icons/FormatColorText.vue'
import { Sketch } from '@lk77/vue3-color'
import { mapMutations } from 'vuex'
import DvrIcon from '../icons/Dvr.vue'
import { rgbToHex } from '@/section-builder/utils/helper'

export default {
  name: 'BzTextEditor',
  components: {
    DvrIcon,
    BzFormatColorTextIcon,
    Sketch
  },
  mixins: [editorMixin],
  props: {
    link: {
      type: [Boolean, Object],
      default: false
    },
    withLink: {
      type: [Boolean],
      default: false
    }
  },
  data () {
    return {
      elements: [],
      selectionState: null,
      editableElement: null,
      isBold: false,
      isItalic: false,
      isUnderline: false,
      fontSize: 14,
      textColor: '#000000',
      openSketch: false,
      openColorPanel: false,
      blockContainerElementNames: [
        // elements our editor generates
        'p',
        'h1',
        'h2',
        'h3',
        'h4',
        'h5',
        'h6',
        'blockquote',
        'pre',
        'ul',
        'li',
        'ol',
        // all other known block elements
        'address',
        'article',
        'aside',
        'audio',
        'canvas',
        'dd',
        'dl',
        'dt',
        'fieldset',
        'figcaption',
        'figure',
        'footer',
        'form',
        'header',
        'hgroup',
        'main',
        'nav',
        'noscript',
        'output',
        'section',
        'video',
        'table',
        'thead',
        'tbody',
        'tfoot',
        'tr',
        'th',
        'td'
      ]
    }
  },
  mounted () {
    const self = this
    this.getDocument().addEventListener('mouseup', function () {
      self.initialize()
    })
  },
  methods: {
    getDocument () {
      const iFrame = document.getElementById('bz-page-content-frame')
      return iFrame.contentWindow.document
    },
    getWindow () {
      if (this.$el) {
        const iFrame = document.getElementById('bz-page-content-frame')
        return iFrame.contentWindow
      }
      return window
    },
    getSelection () {
      let range
      if (this.getWindow().getSelection) {
        const selection = this.getWindow().getSelection()
        if (selection.getRangeAt && selection.rangeCount) {
          range = selection.getRangeAt(0)
        }
      } else if (this.getDocument().selection && this.getDocument().selection.createRange) {
        range = this.getDocument().selection.createRange()
      }
      if (range) {
        return range
      }
      return null
    },
    initialize () {
      const selection = this.getDocument().getSelection()
      if (selection.anchorNode) {
        const computedStyle = this.getWindow().getComputedStyle(selection.anchorNode.parentElement, null)
        const fontWeight = computedStyle.getPropertyValue('font-weight')
        this.isBold = parseInt(fontWeight) > 400

        const fontStyle = computedStyle.getPropertyValue('font-style')
        this.isItalic = fontStyle === 'italic'

        const decoration = computedStyle.getPropertyValue('text-decoration-line')
        this.isUnderline = decoration === 'underline'

        const fontSize = computedStyle.getPropertyValue('font-size')
        this.fontSize = parseInt(fontSize)

        this.textColor = rgbToHex(computedStyle.getPropertyValue('color'))
      }
    },
    handleClear () {
      this.openColorPanel = false
      this.$emit('clear')
      this.$nextTick(() => {
        this.initialize()
      })
    },
    setCustomColor (color) {
      this.textColor = color.hex8
      this.setColor(this.textColor)
    },
    openTheme () {
      this.$el.remove()
      this.openThemeSlider()
    },
    handleItemClick (color) {
      this.openSketch = false
      this.setColor(color)
    },
    setColor (color) {
      if (color.hex8) color = color.hex8
      this.textColor = color
      const selection = this.exportSelection()
      if (selection.start === selection.end) {
        this.selectAllContents()
      }
      this.getDocument().execCommand('foreColor', false, color)
      const fontElements = this.getDocument().getSelection().focusNode.parentNode
      fontElements.removeAttribute('color')
      fontElements.style.color = color
    },
    increaseFontSize () {
      this.fontSize++
      this.execFontSize()
    },
    decreaseFontSize () {
      this.fontSize--
      this.execFontSize()
    },
    execFontSize () {
      const selection = this.exportSelection()
      if (selection.start === selection.end) {
        this.selectAllContents()
      }
      this.getDocument().execCommand('fontSize', false, '4')
      const fontElements = this.getDocument().getSelection().focusNode.parentNode
      fontElements.removeAttribute('size')
      fontElements.style.fontSize = this.fontSize + 'px'
    },
    removeAllCss (el, property) {
      el.childNodes.forEach((child) => {
        this.removeAllCss(child)
      })
    },
    execAction (action, allSelection = false) {
      const selection = this.exportSelection()
      if (selection.start === selection.end || allSelection) {
        this.selectAllContents()
      }
      this.getDocument().execCommand(action)
    },
    selectAllContents () {
      const currNode = this.getSelectionElement()
      this.selectElement(currNode)
    },
    selectElement: function (element) {
      this.selectNode(element)
      const selElement = this.getSelectionElement()
      selElement.focus()
    },
    selectNode: function (node) {
      const range = this.getDocument().createRange()
      range.selectNodeContents(node)
      this.selectRange(range)
    },
    selectRange: function (range) {
      const selection = this.getDocument().getSelection()
      selection.removeAllRanges()
      selection.addRange(range)
    },
    getSelectionElement () {
      const self = this
      return this.findMatchingSelectionParent(function (el) {
        return self.isEditorElement(el)
      })
    },
    exportSelection () {
      const root = this.getSelectionElement()

      const selection = this.getDocument().getSelection()

      const range = selection.getRangeAt(0)
      const preSelectionRange = range.cloneRange()

      preSelectionRange.selectNodeContents(root)
      preSelectionRange.setEnd(range.startContainer, range.startOffset)

      const start = preSelectionRange.toString().length

      return {
        start,
        end: start + range.toString().length
      }
    },
    isEditorElement: function (element) {
      return element && element.getAttribute && element.getAttribute('data-editor-element')
    },
    findMatchingSelectionParent (testElementFunction) {
      const selection = this.getWindow().getSelection()
      if (selection.rangeCount === 0) {
        return false
      }
      const range = selection.getRangeAt(0)
      const current = range.commonAncestorContainer
      return this.traverseUp(current, testElementFunction)
    },
    traverseUp (current, testElementFunction) {
      if (!current) {
        return false
      }
      do {
        if (current.nodeType === 1) {
          if (testElementFunction(current)) {
            return current
          }
          // do not traverse upwards past the nearest containing editor
          if (this.isEditorElement(current)) {
            return false
          }
        }
        current = current.parentNode
      } while (current)
      return false
    },
    getSelectionRange: function () {
      const selection = this.getDocument().getSelection()
      if (selection.rangeCount === 0) {
        return null
      }
      return selection.getRangeAt(0)
    },
    rangeSelectsSingleNode: function (range) {
      const startNode = range.startContainer
      return startNode === range.endContainer && startNode.hasChildNodes() && range.endOffset === range.startOffset + 1
    },
    getSelectionStart () {
      const node = this.getDocument().getSelection().anchorNode
      return node && node.nodeType === 3 ? node.parentNode : node
    },
    isBlockContainer (element) {
      return element && element.nodeType !== 3 && this.blockContainerElementNames.indexOf(element.nodeName.toLowerCase()) !== -1
    },
    openLink () {
      let link
      if (this.withLink) {
        link = this.link
      }
      this.openModal({
        name: 'attachLinkModal',
        data: link,
        onChange: (newLink) => {
          if (this.withLink) {
            this.$emit('changeLink', newLink)
          } else {
            const selection = this.getSelection()
            if (selection) {
              const link = this.getDocument().createElement('a')
              if (newLink.type === 'web-address') {
                link.href = newLink.webAddress
              } else if (newLink.type === 'page') {
                link.href = this.pageUrl(newLink.page)
              } else if (newLink.type === 'email-address') {
                link.href = `mailto:${newLink.email}?Subject=${newLink.subject || ''}`
              } else if (link.type === 'phone-number') {
                link.href = `tel:${newLink.phoneNumber}`
              }
              link.textContent = selection.toString()
              selection.deleteContents()
              selection.insertNode(link)
            }
            this.$emit('changeLink')
          }
        }
      })
    },
    openIconSelector () {
      this.$el.remove()
      this.$store.commit('openModal', {
        name: 'iconSelector',
        onChange: (icon) => {
          const iconNode = this.getDocument().createElement('i')
          iconNode.className = icon

          const selection = this.getSelection()
          if (selection) {
            selection.insertNode(iconNode)
          } else {
            console.warn('InsertIcon: selection does not exist.')
          }
        }
      })
    },
    ...mapMutations(['openModal'])
  }
}
</script>
<style lang="scss">
.bz-text-editor-root {
  margin: -3px -10px;
  position: fixed;
  height: 30px;
  padding: 3px 5px;
  border-right: 4px;
  background-color: white;
  box-shadow: 0 0 20px 5px #00000012;
  display: flex;
  align-items: center;
  z-index: 10001;

  .divider {
    width: 1px;
    background-color: #777777;
    height: 100%;
    margin: 0 8px;
  }

  .item-wrapper {
    width: 24px;
    height: 24px;
    padding: 4px;
    cursor: pointer;

    &:hover:not(.disabled) {
      background-color: #8080803f;
    }

    &.disabled {
      cursor: not-allowed;
    }

    .item {
      width: 100%;
      height: 100%;
      display: flex;
      align-items: center;
      justify-content: center;

      &.selected {
        background-color: #0a6aa13f;
      }

      &.size {
        text-transform: uppercase;
        font-size: 14px;
        color: #555555;
      }

      .outline,
      .fill {
        width: 10px;
        height: 10px;
        margin: 3px;
        padding: 0;
      }

      .fill {
        border-radius: 2px;
        background-color: var(--bz-section-edit-active-color);
      }

      .outline {
        border-radius: 2px;
        background-color: white;
        border: solid 1px #808080;
      }

      &.color {
        position: relative;

        .before {
          content: ' ';
          position: absolute;
          left: 0;
          top: 0;
          background-color: inherit;
          width: 100%;
          height: 100%;
          border-radius: 50%;
          filter: opacity(30%);
          z-index: 1;
        }
      }

      .color {
        width: 12px;
        height: 12px;
        margin: 2px;
        padding: 0;
        border-radius: 6px;
        z-index: 2;
      }
    }
  }

  .font-size {
    width: 40px;
    text-align: center;

    span {
      margin: 0 -6px;
      padding: 0 4px;

      &:focus {
        outline: solid 1px #0076dfff;
      }
    }
  }

  .color-panel {
    position: absolute;
    bottom: calc(100% + 5px);
    right: 0;

    &.bottom {
      bottom: unset;
      top: calc(100% + 5px);
    }
  }
}
</style>
