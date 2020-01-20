import themeMixin from './themeMixin'
import appMixin from './appMixin'

export default {
  mixins: [appMixin, themeMixin],
  props: {
    modelValue: {
      default: undefined
    },
    left: {
      type: Number,
      default: 0
    },
    top: {
      type: Number,
      default: 0
    }
  },
  data() {
    return {
      isIE: null,
      isEdge: null,
      isFF: null,
      isMac: null,
      preview: false
    }
  },
  created() {
    if (this.modelValue) {
      this.data = this.modelValue
    }

    this.isIE =
      navigator.appName === 'Microsoft Internet Explorer' || (navigator.appName === 'Netscape' && /Trident\/.\*rv:([0-9]{1,}[.0-9]{0,})/.exec(navigator.userAgent) !== null)
    this.isEdge = /Edge\/\d+/.exec(navigator.userAgent) !== null
    this.isMac = window.navigator.platform.toUpperCase().indexOf('MAC') >= 0
    this.isFF = navigator.userAgent.toLowerCase().indexOf('firefox') > -1
  },
  methods: {
    handleOutsideClick(event) {
      const target = event.target
      console.log('editorMixin.handleOutsideClick')
      if (!target.closest('.bz-element') && !target.classList.contains('bz-element')) {
        if (window.bzElementEditor) {
          window.bzElementEditor.unmount()
          window.bzElementEditor = null
        }
      }
    }
  }
}
