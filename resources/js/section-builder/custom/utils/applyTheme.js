export default (theme, _document) => {
  if (!theme) {
    console.warn('window.applyTheme: template theme does not exist')
    return
  }

  console.log('window.applyTheme: theme data', theme)

  const r = (_document || document).querySelector(':root')

  /**
   * Link Theme
   */
  if (theme.link) {
    const types = ['header', 'footer', 'main']

    for (const type of types) {
      if (!theme.link[type]) {
        return
      }

      // header link font family
      if (theme.link[type].text?.fontFamily) {
        r.style.setProperty(`--bz-theme-link-${type}-font-family`, theme.link[type].text?.fontFamily)
      } else {
        r.style.setProperty(`--bz-theme-link-${type}-font-family`, `inherit`)
      }

      // header link bold setting
      if (theme.link[type].text?.bold) {
        r.style.setProperty(`--bz-theme-link-${type}-font-weight`, `bold`)
      } else {
        r.style.setProperty(`--bz-theme-link-${type}-font-weight`, `normal`)
      }

      // ${type} link italic setting
      if (theme.link[type].text?.italic) {
        r.style.setProperty(`--bz-theme-link-${type}-font-style`, `italic`)
      } else {
        r.style.setProperty(`--bz-theme-link-${type}-font-style`, `normal`)
      }

      // header link underline setting
      if (theme.link[type].text?.underline) {
        r.style.setProperty(`--bz-theme-link-${type}-text-decoration`, `underline`)
      } else {
        r.style.setProperty(`--bz-theme-link-${type}-text-decoration`, `inherit`)
      }

      if (theme.link[type].text?.letterSpace) {
        r.style.setProperty(`--bz-theme-link-${type}-letter-spacing`, `${theme.link[type].text?.letterSpace}px`)
      }

      if (theme.link[type].text?.fontSize) {
        r.style.setProperty(`--bz-theme-link-${type}-font-size`, `${theme.link[type].text?.fontSize}px`)
      }

      // header link color setting
      r.style.setProperty(`--bz-theme-link-${type}-font-color`, theme.link[type].text?.color)

      r.style.setProperty(`--bz-theme-link-${type}-font-opacity`, theme.link[type].text?.opacity / 100)

      if (theme.link[type].text?.hoverOption === `hover_underline`) {
        r.style.setProperty(`--bz-theme-link-${type}-hover-text-decoration`, `underline`)
      } else if (theme.link[type].text?.hoverOption === `hover_overline`) {
        r.style.setProperty(`--bz-theme-link-${type}-hover-text-decoration`, `overline`)
      } else if (theme.link[type].text?.hoverOption === `hover_both_lines`) {
        r.style.setProperty(`--bz-theme-link-${type}-hover-text-decoration`, `underline overline`)
      } else {
        r.style.setProperty(`--bz-theme-link-${type}-hover-text-decoration`, `unset`)
      }

      if (theme.link[type].text?.hoverColor) {
        r.style.setProperty(`--bz-theme-link-${type}-hover-color`, theme.link[type].text.hoverColor)
      }
    }
  }
}
