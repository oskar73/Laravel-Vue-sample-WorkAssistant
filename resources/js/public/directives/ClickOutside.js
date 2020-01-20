export default  {
  beforeMount: function (el, binding) {
    el.__vueClickEventHandler__ = (event) => {
      if (!el.contains(event.target) && el !== event.target) {
        if (typeof binding.value === 'function') {
          binding.value(event)
        }
      }
    }
    document.body.addEventListener('click', el.__vueClickEventHandler__)
    const iFrame = document.getElementById('bz-page-content-frame')
    if(iFrame) {
      iFrame.contentWindow.document.addEventListener('click', el.__vueClickEventHandler__)
    }
  },
  unmounted: function (el) {
    document.body.removeEventListener('click', el.__vueClickEventHandler__)
    const iFrame = document.getElementById('bz-page-content-frame')
    if(iFrame) {
      iFrame.contentWindow.document.removeEventListener('click', el.__vueClickEventHandler__)
    }
  }
}
