import sectionMixin from '@/section-builder/mixins/sectionMixin'
import { getPortfolioItems } from '@/section-builder/apis'

export default {
  mixins: [sectionMixin],
  data() {
    return {
      items: []
    }
  },
  created() {
    try {
      getPortfolioItems().then((res) => {
        if (res?.data) this.items = res.data.items
      })
    } catch {}
  },
  computed: {
    filteredItems() {
      if (this.setting.portfolio.category) {
        return this.items.filter(({ category_id }) => category_id === this.setting.portfolio.category)
      }
      return this.items
    },
    nextItems() {
      const items = [...this.filteredItems] || []
      items.splice(0, 4)
      return items
    }
  },
  methods: {
    getImgUrl(item) {
      if (this.isBuilder) return item?.media || 'https://picsum.photos/200'

      return item?.media[0].original_url || 'https://picsum.photos/200'
    }
  }
}
