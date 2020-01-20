<script>
import builderMixin from '../../mixins/builderMixin'
export default {
  mixins: [builderMixin],
  props: {
    setting: {
      type: Object,
      required: true
    }
  },
  methods: {
    getSettingName(text) {
      try {
        const result = text.replace(/([A-Z])/g, ' $1')
        return result.charAt(0).toUpperCase() + result.slice(1)
      } catch (e) {
        window.LOG.error('SettingPanel->getSettingName', e)
        return 'Unknown'
      }
    },
    displayWith(value) {
      return value + '%'
    }
  }
}
</script>
