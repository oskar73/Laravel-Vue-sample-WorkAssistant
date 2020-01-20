export default {
  install(Vue) {
    Vue.prototype.$utils = {
      getTextColor(bgColor, defaultColor = '#ffffff', whiteOrBlack = true) {
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
    }
  }
}
