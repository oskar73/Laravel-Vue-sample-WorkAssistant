<script>
import sectionMixin from '../../mixins/sectionMixin'
import { getTextColor } from '@/section-builder/utils/helper'

export default {
  mixins: [sectionMixin],
  computed: {
    lineColor() {
      const color = getTextColor(this.backgroundColor)
      return color.brighten(80)
    }
  },
  watch: {
    textColor: {
      immediate: true,
      handler(value) {
        this.$nextTick(() => {
          this.$el.style.setProperty('--bz-footer-text-color', value)
        })
      }
    }
  },
  methods: {
    handleLogoSizeChange(value) {
      this.setting.logoSize = value
    },
    showMenuItem(page) {
      if (page.type === 'new-page') {
        return false
      }

      if (page.type === 'module') {
        return this.modules.activeModules.includes(page.module_name)
      }

      return true
    }
  }
}
</script>
