import eventBus from '../../public/eventBus'
import _ from 'lodash'
import { NS } from '../editor/namespaces'

export default {
  data() {
    return {
      selected: [],
      attributes: {
        background: null,
        opacity: {
          value: 1,
          min: 0,
          max: 1,
          interval: 0.01
        },

        blur: {
          value: 0,
          min: 0,
          max: 10,
          interval: 0.01
        },
        color: {
          fill: '#FFFFFF',
          stroke: null
        },
        stroke: {
          value: 1,
          min: 0,
          max: 10,
          interval: 0.01,
          color: '#000000'
        },

        // Text attributes
        text: {
          value: null,
          font: {
            name: null,
            size: 24,
            bold: false,
            italic: false,
            min: 1,
            max: 200,
            interval: 1
          },
          letterSpacing: {
            value: 0,
            min: 0,
            max: this.isFirefox() ? 1000 : 100,
            interval: this.isFirefox() ? 5 : 1
          },
          isUpperCase: false
        }
      },
      isSameProps: {
        blur: false,
        opacity: false,
        stroke: {
          width: false
        }
      }
    }
  },
  computed: {
    canvas() {
      return window.svgEditor.canvas
    },
    isSelected() {
      if (this.selected[0] && this.selected[0].id === 'backgroundRect') {
        return false
      }
      return this.selected[0]
    }
  },
  mounted() {
    eventBus.$on('selected.changed', () => {
      this.selected = this.getSelected()
      if (this.isSelected) {
        this.setPropertiesSame()
        this.updatePanel()
        this.initHoverHandler()
        this.updateGlobalTextInput()
      }
    })
  },
  methods: {
    updatePanel() {
      const elements = this.getSelected()
      elements.forEach((element) => {
        if (element) {
          // Set opacity in panel
          this.attributes.opacity.value = this.getDisplayOpacity(element)

          // Set blur in panel
          this.attributes.blur.value = this.getDisplayBlur(element)

          // Set stroke width in panel
          this.attributes.stroke.value = this.getDisplayStroke(element)

          // Set stroke color
          this.attributes.stroke.color = this.getDisplayStrokeColor(element)
          this.stroke_gradient = this.getDisplayStrokeGradient(element)

          // Set fill color
          this.attributes.color.fill = this.getDisplayFillColor(element)
          if (this.attributes.color.fill === 'none') {
            this.attributes.color.fill = '#000000'
          }

          this.fill_gradient = this.getDisplayFillGradient(element)

          this.fill_type = 'solid'
          if (this.attributes.color.fill) {
            this.fill_type = 'solid'
          } else {
            setTimeout(
              (_this) => {
                _this.fill_type = 'gradient'
              },
              10,
              this
            )
          }

          this.stroke_type = 'solid'

          if (this.attributes.stroke.color) {
            this.stroke_type = 'solid'
          } else {
            setTimeout(
              (_this) => {
                _this.stroke_type = 'gradient'
              },
              10,
              this
            )
          }

          if (this.isTextSelected()) {
            // Set text value
            this.attributes.text.value = this.getDisplayText(element)

            // Set font name in panel
            this.attributes.text.font.name = this.getElementFont(element)

            // Set font size in panel
            this.attributes.text.font.size = this.getElementFontSize(element)

            // Set bold style for text
            this.attributes.text.font.bold = this.textIsBold(element)

            // Set italic style for text
            this.attributes.text.font.italic = this.textIsItalic(element)

            // Set upper-case for text
            this.attributes.text.isUpperCase = this.textIsInUpperCase(element)

            // Set letter-spacing for text
            this.attributes.text.letterSpacing.value = this.getDisplayLetterSpacing(element)
          }
        }
      })

      this.refreshRanges()
    },
    arrayValuesIsSame(elements) {
      return elements.every((val, i, arr) => val === arr[0])
    },
    setText(text) {
      this.canvas.setTextContent(text)
    },
    setPropertiesSame() {
      const self = this

      const blur = []
      const opacity = []
      const stroke = []

      const elements = this.getSelected()

      elements.forEach(function (element) {
        blur.push(self.getElementBlur(element))
        opacity.push(self.getElementOpacity(element))
        stroke.push(self.getElementStroke(element))
      })

      this.isSameProps.blur = this.arrayValuesIsSame(blur)
      this.isSameProps.opacity = this.arrayValuesIsSame(opacity)
      this.isSameProps.stroke.width = this.arrayValuesIsSame(stroke)
    },
    prepareColor(color) {
      const defaultColor = 'none'
      const defaultTextColor = '#3B3B3B'

      if (!color) {
        if (this.isTextSelected()) {
          return defaultTextColor
        }
      }

      // If color is none
      if (!color || color === defaultColor || color === 'undefined' || color === undefined) {
        return defaultColor
      }

      return color
    },
    inRange(x, min, max) {
      return (x - min) * (x - max) <= 0 || x > max
    },
    isNumber: function (evt) {
      evt = evt || window.event
      const value = parseInt(evt.key)

      if (Number.isInteger(value) || evt.key === '.' || evt.key.indexOf('Arrow') > 0 || evt.key === 'Delete' || evt.key === 'Backspace') {
        return true
      } else {
        evt.preventDefault()
        return false
      }
    },
    isMultiselected() {
      return this.getSelected().length > 1
    },
    isFirefox() {
      return navigator.userAgent.toLowerCase().indexOf('firefox') > -1
    },
    isTextSelected() {
      return this.selected[0] && this.selected[0].nodeName === 'text' && this.elementsAreSame()
    },
    getSelected() {
      const items = this.canvas.getSelectedElems()
      return this.cleanItems(items)
    },
    getElementBlur(element) {
      return this.canvas.getBlur(element)
    },
    getElementOpacity(element) {
      return this.canvas.getOpacity(element)
    },
    getElementStroke(element) {
      return this.canvas.getStrokeWidth(element)
    },
    getText(element) {
      return this.canvas.getText()
    },
    getDisplayText(element) {
      return this.getText(element)
    },
    getElementFont(element) {
      return this.canvas.getFontFamily(element)
    },
    getElementFontSize(element) {
      return this.canvas.getFontSize(element)
    },
    textIsBold(element) {
      return this.canvas.getBold(element)
    },
    textIsItalic(element) {
      return this.canvas.getItalic(element)
    },
    // Get display in <input> property value
    getDisplayOpacity(element) {
      if (this.isMultiselected()) {
        if (!this.isSameProps.opacity) return null

        return this.getElementOpacity()
      }

      return this.getElementOpacity(element)
    },
    getDisplayBlur(element) {
      if (this.isMultiselected()) {
        if (!this.isSameProps.blur) return null

        return this.getElementBlur()
      }

      return this.getElementBlur(element)
    },
    getDisplayStroke(element) {
      if (this.isMultiselected()) {
        if (!this.isSameProps.stroke.width) return null

        return this.getElementStroke()
      }

      return this.getElementStroke(element)
    },
    getDisplayStrokeColor(element) {
      if (this.isMultiselected()) {
        return null
      }

      const ret = this.getElementStrokeColor(element)
      if (ret.substr(0, 3) === 'url') {
        return ''
      }

      return ret
    },
    getGradientColor(elem) {
      const grad = {
        type: 'linear',
        degree: 0,
        points: []
      }
      const gradientElem = document.getElementById(elem)
      if (gradientElem.tagName === 'linearGradient') {
        let x1 = parseInt(gradientElem.getAttribute('x1'))
        const x2 = parseInt(gradientElem.getAttribute('x2'))
        let y1 = parseInt(gradientElem.getAttribute('y1'))
        const y2 = parseInt(gradientElem.getAttribute('y2'))

        x1 = x1 - x2
        y1 = y1 - y2
        grad.degree = parseInt((Math.atan2(y1, x1) / Math.PI) * 180 + 270) % 360
      } else {
        grad.type = 'radial'
      }

      const stops = gradientElem.getElementsByTagNameNS(NS.SVG, 'stop')
      if (stops.length > 0) {
        for (let i = 0; i < stops.length; i++) {
          const point = {}
          point.left = parseInt(stops[i].getAttribute('offset'))
          const clr = stops[i].getAttribute('stop-color')
          point.red = parseInt(clr.substr(1, 2), 16)
          point.green = parseInt(clr.substr(3, 2), 16)
          point.blue = parseInt(clr.substr(5, 2), 16)
          point.alpha = parseFloat(stops[i].getAttribute('stop-opacity')) || 1

          grad.points.push(point)
        }
      } else {
        for (let i = 0; i < this.defaultGradient.points.length; i++) {
          grad.points.push(this.defaultGradient.points[i])
        }
      }
      return grad
    },
    textIsInUpperCase(element) {
      if (!element) {
        element = this.getFirstSelected()
      }

      if (this.isTextSelected()) {
        const text = this.getText(element)

        if (text.length > 0) {
          return text === text.toUpperCase()
        }
      }

      return false
    },
    getDisplayLetterSpacing(element) {
      if (this.isMultiselected()) {
        // if (!this.isSameProps.stroke.width) return null;
        return this.getElementLetterSpacing()
      }
      return this.getElementLetterSpacing(element)
    },
    getElementLetterSpacing(element) {
      return this.canvas.getLetterSpacing(element)
    },
    getElementStrokeColor(element) {
      return this.prepareColor(this.canvas.getStrokeColor(element))
    },
    getDisplayFillColor(element) {
      if (this.isMultiselected()) {
        return null
      }
      const ret = this.getElementFillColor(element)
      if (ret.substr(0, 3) === 'url') {
        return ''
      }
      return ret
    },
    getDisplayStrokeGradient(element) {
      if (this.isMultiselected()) {
        return this.defaultGradient
      }

      const ret = this.getElementStrokeColor(element)
      if (ret.substr(0, 3) === 'url') {
        const elementId = ret.substr(5, ret.length - 6)
        return this.getGradientColor(elementId)
      }

      return this.defaultGradient
    },
    getElementFillColor(element) {
      let color = element.getAttribute('fill')
      // Try to find color in <path> element
      if (!color) {
        const paths = window.$(element).find('path')
        paths.each(function (i, item) {
          if (paths.length > 1) {
            if (window.$(item).attr('fill') !== '#FFFFFF') {
              color = window.$(item).attr('fill')
            }
          } else {
            color = window.$(item).attr('fill')
          }
        })
      }

      return this.prepareColor(color)
    },
    getDisplayFillGradient(element) {
      // TODO: If its icon, get first color and return
      if (this.isMultiselected()) {
        return this.defaultGradient
      }

      const ret = this.getElementFillColor(element)

      if (ret.substr(0, 3) === 'url') {
        const elementId = ret.substr(5, ret.length - 6)
        return this.getGradientColor(elementId)
      }

      return this.defaultGradient
    },
    refreshRanges() {
      const self = this

      this.$nextTick(() => {
        Object.keys(this.$refs).forEach((name) => {
          if (name.indexOf('slider') >= 0 && self.$refs[name]) {
            self.$refs[name].$refs.slider.refresh()
          }
        })
      })
    },
    initHoverHandler() {
      const items = this.getVisibleElements()
      window.$(items).on({
        mouseover: function () {
          window.$(items).addClass('not-hover-svg-item')
          window.$(this).addClass('hover-svg-item')
        },
        mouseout: function () {
          window.$(items).removeClass('not-hover-svg-item')
          window.$(this).removeClass('hover-svg-item')
        }
      })
    },
    updateGlobalTextInput() {
      const item = this.getFirstSelected()

      if (item && item.nodeName === 'text') {
        const content = item.textContent
        const textInput = this.getGlobalTextInput()
        textInput.setAttribute('value', content)
      }
    },
    getFirstSelected() {
      return this.getSelected()[0]
    },
    getGlobalTextInput() {
      return document.getElementById('text')
    },
    getVisibleElements() {
      const items = this.canvas.getVisibleElements()

      return _.filter(items, function (item) {
        const excludedIds = ['snap_line_x', 'snap_line_y', 'backgroundRect']

        return !_.includes(excludedIds, item.id)
      })
    },
    cleanItems(items) {
      return this.canvas.cleanItems(items)
    },
    elementsAreSame() {
      const selected = this.getSelected()

      if (selected.length > 1) {
        const nodeName = selected[0].nodeName
        let isSame = true

        selected.forEach((item) => {
          if (item.nodeName !== nodeName) {
            isSame = false
          }
        })

        return isSame
      }

      return true
    },
    getDesign() {
      return this.canvas.svgCanvasToString(false)
    },
    rgbToHex(R, G, B) {
      return this.getHexNumber(R) + this.getHexNumber(G) + this.getHexNumber(B)
    },
    getHexNumber(val) {
      if (val < 0x10) {
        const prefix = '0'
        return prefix + val.toString(16)
      }
      return val.toString(16)
    }
  }
}
