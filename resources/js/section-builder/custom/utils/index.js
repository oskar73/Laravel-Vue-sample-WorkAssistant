import applyTheme from './applyTheme'

export default {
  init: function () {
    // String ProtoTypes

    window.isBase64 = (string) => {
      return true
    }

    window.getSelector = (node) => {
      const id = node.getAttribute('id')

      if (id) {
        return '#' + id
      }

      let path = ''

      while (node) {
        let name = node.localName
        const parent = node.parentNode

        if (!parent) {
          return path
        }

        if (node.getAttribute('id')) {
          return '#' + node.getAttribute('id') + ' > ' + path
        }

        const sameTagSiblings = []
        let children = parent.childNodes
        children = Array.prototype.slice.call(children)

        children.forEach(function (child) {
          if (child.localName === name) {
            sameTagSiblings.push(child)
          }
        })

        // if there are more than one children of that type use nth-of-type

        if (sameTagSiblings.length > 1) {
          const index = sameTagSiblings.indexOf(node)
          name += ':nth-of-type(' + (index + 1) + ')'
        }

        if (path) {
          path = name + ' > ' + path
        } else {
          path = name
        }

        node = parent
      }
    }

    window.String.prototype.isURL = function () {
      const pattern = new RegExp(
        '^(https?:\\/\\/)?' + // protocol
          '((([a-z\\d]([a-z\\d-]*[a-z\\d])*)\\.?)+[a-z]{2,}|' + // domain name
          '((\\d{1,3}\\.){3}\\d{1,3}))' + // OR ip (v4) address
          '(\\:\\d+)?(\\/[-a-z\\d%_.~+]*)*' + // port and path
          '(\\?[;&a-z\\d%_.~+=-]*)?' + // query string
          '(\\#[-a-z\\d_]*)?$',
        'i'
      ) // fragment locator
      return pattern.test(this)
    }

    window.String.prototype.dashed = function () {
      return this.replace(/[A-Z]/g, (m) => '-' + m.toLowerCase())
    }

    window.String.prototype.darken = function (p) {
      const originalColor = window.tinycolor(this)
      return originalColor.darken(p).toString()
    }

    window.String.prototype.brighten = function (p) {
      const originalColor = window.tinycolor(this)
      return originalColor.brighten(p).toString()
    }

    window.String.prototype.brighten = function (p) {
      const originalColor = window.tinycolor(this)
      return originalColor.brighten(p).toString()
    }

    // Array Prototypes
    window.Array.prototype.insert = function (index, item) {
      this.splice(index, 0, item)
    }

    window.Array.prototype.swap = function (x, y) {
      const b = this[x]
      this[x] = this[y]
      this[y] = b
      return this
    }

    window.Array.prototype.toggle = function (item) {
      const index = this.indexOf(item)
      if (index === -1) {
        this.push(item)
      } else {
        this.splice(index, 1)
      }
    }

    window.Array.prototype.last = function () {
      return this[this.length - 1]
    }

    window.Array.prototype.first = function () {
      return this[0]
    }

    // Global Functions

    window._get = function (object, path = '', defaultValue = null) {
      // Cache the current object
      let current = object
      const pack = path.split('.')
      // For each item in the path, dig into the object
      for (let i = 0; i < pack.length; i++) {
        let key = pack[i]
        if (Array.isArray(current)) {
          key = parseInt(key)
        }
        // If the item isn't found, return the default (or null)
        if (!current[key]) return defaultValue
        current = current[key]
      }
      return current
    }

    window._update = function (object, newValue, path) {
      const stack = path.split('.')
      let key = stack.shift()
      if (stack.length === 0) {
        if (newValue) {
          if (Array.isArray(object)) {
            key = parseInt(key)
          }
          object[key] = newValue
        } else {
          if (Array.isArray(object)) {
            object.splice(key, 1)
          } else {
            delete object[key]
          }
        }
      } else {
        object[key] = window._update(object[key], newValue, stack.join('.'))
      }
      return object
    }

    window._update = function (object, newValue, path) {
      const stack = path.split('.')
      const key = stack.shift()
      if (stack.length === 0) {
        object[key] = newValue
      } else {
        object[key] = window._update(object[key], newValue, stack.join('.'))
      }
      return object
    }

    window._copy = (value) => {
      let object
      if (typeof object === 'object') {
        return value
      }
      if (Array.isArray(value)) {
        object = []
        value.forEach((item, index) => {
          if (typeof value[index] === 'object') {
            object[index] = window._copy(value[index])
          } else {
            object[index] = value[index]
          }
        })
      } else {
        object = {}
        for (const key in value) {
          if (typeof value[key] === 'object') {
            object[key] = window._copy(value[key])
          } else {
            object[key] = value[key]
          }
        }
      }
      return object
    }
    window.getRandomInt = (max) => {
      return Math.floor(Math.random() * max)
    }

    window._take = (toObject, fromObject, exceptions = []) => {
      if (Array.isArray(fromObject)) {
        fromObject.forEach((item, index) => {
          if (typeof toObject[index] === 'object') {
            toObject[index] = window._take(toObject[index], fromObject[index] || toObject[index])
          } else {
            toObject[index] = fromObject[index] || toObject[index]
          }
        })
      } else {
        for (const key in fromObject) {
          if (exceptions.includes(key)) {
            continue
          }

          if (typeof toObject[key] === 'object') {
            toObject[key] = window._take(toObject[key], fromObject[key])
          } else {
            toObject[key] = fromObject[key] || toObject[key]
          }
        }
      }
      return toObject
    }

    window.getHoverColor = (color) => {
      const brightness = window.getBrightness(color)
      return brightness > 168 ? color.darken(15) : color.brighten(15)
    }

    /***
     * Returns brightness of given color.
     * The brightness will fall between number 0 ~ 255.
     * @param color
     * @returns {number}
     */
    window.getBrightness = (color) => {
      try {
        if (!color) {
          return 0
        }
        if (color.includes('rgb')) {
          color = window.rgbToHex(color)
        }
        color = color.replace(/ /g, '').replace('#', '')
        const r = parseInt(color.substring(0, 2), 16)
        const g = parseInt(color.substring(2, 4), 16)
        const b = parseInt(color.substring(4, 6), 16)

        return (299 * r + 587 * g + 114 * b) / 1000
      } catch (error) {
        console.error('getBrightness Error: ', error)
        console.error('color: ', color)
        return 0
      }
    }

    /**
     * Compare 2 colors in brightness and returns the comparison result of which one is more bright.
     * @param color1
     * @param color2
     * @returns {boolean}
     */
    window.isBrighter = (color1, color2) => {
      return window.getBrightness(color1) > window.getBrightness(color2)
    }

    /**
     * Returns text color which contrast to the given background color in hex.
     * @param bgColor
     * @returns {string}
     */
    window.getColor = (bgColor) => {
      bgColor = bgColor.replace(/ /g, '')
      if (bgColor.includes('rgb')) {
        bgColor = window.rgbToHex(bgColor)
      }
      const color = bgColor.charAt(0) === '#' ? bgColor.substring(1, 7) : bgColor
      const r = parseInt(color.substring(0, 2), 16) // hexToR
      const g = parseInt(color.substring(2, 4), 16) // hexToG
      const b = parseInt(color.substring(4, 6), 16) // hexToB
      return r * 0.299 + g * 0.587 + b * 0.114 > 186 ? '#555555' : '#ffffff'
    }

    /**
     * Returns HEX color from the rgba color.
     * @param numStr
     * @param percent
     * @returns {number}
     */
    window.componentFromStr = (numStr, percent) => {
      const num = Math.max(0, parseInt(numStr, 10))
      return percent ? Math.floor((255 * Math.min(100, num)) / 100) : Math.min(255, num)
    }

    window.rgbToHex = (rgb) => {
      const rgbRegex = /^rgb\(\s*(-?\d+)(%?)\s*,\s*(-?\d+)(%?)\s*,\s*(-?\d+)(%?)\s*\)$/
      let result
      let r
      let g
      let b
      let hex = ''
      if ((result = rgbRegex.exec(rgb))) {
        r = window.componentFromStr(result[1], result[2])
        g = window.componentFromStr(result[3], result[4])
        b = window.componentFromStr(result[5], result[6])
        hex = '#' + (0x1000000 + (r << 16) + (g << 8) + b).toString(16).slice(1)
      }
      return hex
    }

    // left: 37, up: 38, right: 39, down: 40,
    // space bar: 32, pageup: 33, pagedown: 34, end: 35, home: 36
    const keys = {
      37: 1,
      38: 1,
      39: 1,
      40: 1
    }

    window.isScrollable = (element, direction) => {
      if (direction === 'down') {
        return element.scrollHeight > element.clientHeight + element.scrollTop
      }

      return element.scrollTop > 0
    }

    function preventDefault(e) {
      const direction = e.wheelDelta > 0 ? 'up' : 'down'

      if (!window.isScrollable(window.scrollingTarget, direction) || !(window.scrollingTarget === e.target || window.scrollingTarget.contains(e.target))) {
        e.preventDefault()
      }
    }

    function preventDefaultForScrollKeys(e) {
      if (keys[e.keyCode]) {
        preventDefault(e)
        return false
      }
    }

    // modern Chrome requires { passive: false } when adding event
    let supportsPassive = false
    try {
      window.addEventListener(
        'test',
        null,
        Object.defineProperty({}, 'passive', {
          get: function () {
            supportsPassive = true
          }
        })
      )
    } catch (e) {}

    const wheelOpt = supportsPassive ? { passive: false } : false
    const wheelEvent = 'onwheel' in document.createElement('div') ? 'wheel' : 'mousewheel'

    // call this to Disable
    window.disableScroll = (target) => {
      window.scrollingTarget = target
      window.addEventListener('DOMMouseScroll', preventDefault, false) // older FF
      window.addEventListener(wheelEvent, preventDefault, wheelOpt) // modern desktop
      window.addEventListener('touchmove', preventDefault, wheelOpt) // mobile
      window.addEventListener('keydown', preventDefaultForScrollKeys, false)
    }

    // call this to Enable
    window.enableScroll = () => {
      window.removeEventListener('DOMMouseScroll', preventDefault, false)
      window.removeEventListener(wheelEvent, preventDefault, wheelOpt)
      window.removeEventListener('touchmove', preventDefault, wheelOpt)
      window.removeEventListener('keydown', preventDefaultForScrollKeys, false)
    }

    window.getImageLightness = (imageSrc, callback) => {
      const img = document.createElement('img')
      img.src = imageSrc
      img.style.display = 'none'
      document.body.appendChild(img)

      let colorSum = 0

      img.onload = function () {
        // create canvas
        const canvas = document.createElement('canvas')
        canvas.width = this.width
        canvas.height = this.height

        const ctx = canvas.getContext('2d')
        ctx.drawImage(this, 0, 0)

        const imageData = ctx.getImageData(0, 0, canvas.width, canvas.height)
        const data = imageData.data
        let r, g, b, avg

        for (let x = 0, len = data.length; x < len; x += 4) {
          r = data[x]
          g = data[x + 1]
          b = data[x + 2]

          avg = Math.floor((r + g + b) / 3)
          colorSum += avg
        }
        const brightness = Math.floor(colorSum / (this.width * this.height))
        callback(brightness)
      }
    }

    window.LOG = {
      error: (message, ...data) => {
        console.log('%cERROR: ' + message, 'color:Red', ...data)
      },
      success: (message, ...data) => {
        console.log('%cSUCCESS: ' + message, 'color:Green', ...data)
      },
      warn: (message, ...data) => {
        console.log('%cWARNING: ' + message, 'color:#fb8817', ...data)
      },
      info: (...data) => {
        console.log(...data)
      }
    }

    window.getBase64FromUrl = async (url) => {
      const data = await fetch(url)
      const blob = await data.blob()
      return new Promise((resolve) => {
        const reader = new FileReader()
        reader.readAsDataURL(blob)
        reader.onloadend = () => {
          const base64data = reader.result
          resolve(base64data)
        }
      })
    }

    window.applyTheme = applyTheme
  }
}
