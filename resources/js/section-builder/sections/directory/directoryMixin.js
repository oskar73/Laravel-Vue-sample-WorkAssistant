import sectionMixin from '@/section-builder/mixins/sectionMixin'
import { getDirectoryListings } from '@/section-builder/apis'

export default {
  mixins: [sectionMixin],
  data() {
    return {
      listings: []
    }
  },
  created() {
    try {
      getDirectoryListings().then((res) => {
        if (res?.data) this.listings = res.data.listings
      })
    } catch {}
  },
  computed: {
    filteredItems() {
      if (this.setting.listing.category) {
        return this.listings.filter(({ category_id }) => category_id === this.setting.listing.category)
      }
      return this.listings
    }
  },
  methods: {
    createArrayFrom1ToN(n) {
      let result = []
      for (let i = 1; i <= n; i++) {
        result.push(i)
      }
      return result
    },
    getGridCount(n) {
      if ([1, 2, 3, 4, 6].includes(n)) return n
      if (n > 6) return 6
      return 4
    },
    getImgUrl(listing) {
      if (this.isBuilder) return listing.media || 'https://picsum.photos/200'

      if (!listing.media?.length) return 'https://picsum.photos/200'

      return listing.media[0].original_url
    },
    goToPage() {
      if (this.isBuilder) return

      window.location.href = this.template.module_url['directory'] || this.allPages.find(({ module_name }) => module_name == 'directory')?.url || '/directory'
    },
    detailView(listing) {
      if (this.isBuilder) return

      const root = this.template.module_url['directory'] || this.allPages.find(({ module_name }) => module_name == 'directory')?.url || '/directory'
      window.location.href = `${root}/detail/${listing.slug}`
    },
    stringify(text) {
      if (text.length < 120) return text
      return text.slice(0, 120) + ' ...'
    }
  }
}
