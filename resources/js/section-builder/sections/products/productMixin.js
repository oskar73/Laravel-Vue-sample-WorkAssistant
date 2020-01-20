import sectionMixin from '@/section-builder/mixins/sectionMixin'
import { getEcommerceProducts } from '@/section-builder/apis'
import Cart from '@/section-builder/utils/cart'

export default {
  mixins: [sectionMixin],
  data() {
    return {
      products: []
    }
  },
  created() {
    try {
      getEcommerceProducts().then((res) => {
        if (res?.data) this.products = res.data.products
      })
    } catch {}
  },
  methods: {
    addCart(product) {
      const cart = new Cart(window.websiteId)
      cart.addItem(product)
      this.$router.push({ path: '/cart' })
    },
    createArrayFrom1ToN(n) {
      const result = []
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
    handleAddItem() {
      window.open(this.domain + '/admin/ecommerce/product', '_blank')
    },
    goToPage(product) {
      if (this.isBuilder) return

      if (this.$store.state.modules.ecommerce.page) {
        const product_url = this.$store.state.modules.ecommerce.page.url + '/' + product.slug
        window.open(product_url, '_blank')
      }
    }
  }
}
