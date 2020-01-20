export const truncate = (str, len = 48, html = false) => {
  if (!str) return str

  let des = str
  if (html) {
    const section = document.createElement('div')
    section.innerHTML = str
    des = section.innerText
  }
  return des.length > len ? des.slice(0, len) + '...' : des
}

export const loadImage = async (imageUrl) => {
  return new Promise((resolve) => {
    if (imageUrl) {
      const img = new Image()
      img.onload = () => {
        resolve(img)
      }
      img.onerror = () => {
        resolve(false)
      }
      img.src = imageUrl
    } else {
      resolve(false)
    }
  })
}

// Get Text Color from background color.
export const getTextColor = (bgColor, defaultColor = '#ffffff', whiteOrBlack = true) => {
  const bgBrightNess = window.getBrightness(bgColor || defaultColor)

  if (whiteOrBlack) {
    if (bgBrightNess < 190) {
      return '#ffffff'
    } else {
      return '#000000'
    }
  }
  if (bgBrightNess < 50) {
    return '#ffffff'
  }
  if (bgBrightNess > 200) {
    return '#000000'
  }
  if (Math.abs(window.getBrightness(defaultColor) - bgBrightNess) > 50) {
    return defaultColor
  }
  if (Math.abs(window.getBrightness(this.theme[this.themeMode].primaryColor) - bgBrightNess) > 50) {
    return this.theme[this.themeMode].primaryColor
  }
  if (Math.abs(window.getBrightness(this.theme[this.themeMode].secondaryColor) - bgBrightNess) > 50) {
    return this.theme[this.themeMode].secondaryColor
  }
  if (Math.abs(window.getBrightness(this.theme[this.themeMode].darkModeColor) - bgBrightNess) > 50) {
    return this.theme[this.themeMode].darkModeColor
  }
  if (Math.abs(window.getBrightness(this.theme[this.themeMode].lightModeColor) - bgBrightNess) > 50) {
    return this.theme[this.themeMode].lightModeColor
  }
  return window.getColor(bgColor)
}

export const rgbToHex = (rgb) => {
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
