<script>
import { defineComponent } from 'vue'
import PageLayout from '@/section-builder/components/page/PageLayout.vue'
import BzContainer from '@/section-builder/components/section/BzContainer.vue'
import Spinner from '@/public/Spinner.vue'
import { getEcommerceProduct } from '@/section-builder/apis'
import Cart from '@/section-builder/utils/cart'
import { toast } from 'vue3-toastify'
import 'vue3-toastify/dist/index.css'

export default defineComponent({
  name: 'ProductDetail',
  components: { Spinner, BzContainer, PageLayout },
  data() {
    return {
      productId: null,
      product: null,
      loading: true,
      size: null,
      color: null,
      variant: null,
      qnt: 1,
      price: null,
      slashedPrice: null,
      hasCart: false,
      cart: null
    }
  },
  created() {
    this.productId = Number(this.$route.params.product)
    getEcommerceProduct(this.productId).then((res) => {
      this.product = res.data.product
      this.price = this.product.standard_price.price
      this.slashedPrice = this.product.standard_price.standard_price
      this.loading = false
    })
  },
  methods: {
    addCart() {
      if (!this.checkProperty()) return

      const cart = new Cart(window.websiteId)
      let options = {}
      if (this.product.size) {
        options.size = {
          id: this.size,
          name: this.product.sizes.find(({ id }) => id === this.size)?.name
        }
      }
      if (this.product.color) {
        options.color = {
          id: this.color,
          name: this.product.colors.find(({ id }) => id === this.color)?.name
        }
      }
      if (this.product.variant) {
        options.variant = {
          id: this.variant,
          title: this.product.variant_name,
          name: this.product.variants.find(({ id }) => id === this.variant)?.name
        }
      }
      cart.addItem(this.product, parseInt(this.qnt), options, this.price)
      this.cart = cart
    },
    selectProperty(type, value) {
      this[type] = value

      const price = this.product.prices.find((product) => {
        if (this.product.size && this.size !== product.size_id) return false
        if (this.product.color && this.color !== product.color_id) return false
        if (this.product.variant && this.variant !== product.variant_id) return false
        return true
      })
      if (price) {
        this.price = price.price
        this.slashedPrice = price.slashed_price
      }
    },
    updateQnt(element) {
      this.qnt = element.target.value
    },
    checkProperty() {
      if (this.product.size && !this.size) {
        toast.error('Please choose a size for the product')
        return false
      }
      if (this.product.color && !this.color) {
        toast.error('Please choose a color for the product')
        return false
      }
      if (this.product.variant && !this.variant) {
        toast.error(`Please choose a ${this.product.variant_name} for the product`)
        return false
      }
      if (!this.qnt || this.qnt < 1) {
        toast.error('Please choose a quantity for the product')
        return false
      }

      return true
    }
  }
})
</script>

<template>
  <page-layout>
    <div class="tw-w-full tw-my-5">
      <bz-container>
        <div v-if="loading" class="tw-flex tw-justify-center tw-items-center tw-h-36">
          <spinner class="tw-w-8 tw-h-8" />
        </div>
        <div v-else>
          <div class="tw-grid tw-grid-cols-2 tw-gap-4">
            <div>
              <img :src="product.thumbnail" class="tw-w-full" />
            </div>
            <div>
              <h2 class="tw-text-3xl">{{ product.title }}</h2>
              <p class="tw-text-blue-500 tw-font-bold tw-text-2xl tw-my-4">
                ${{ price }} <span class="tw-text-gray-500 tw-line-through tw-font-light tw-text-xl" v-if="slashedPrice">${{ slashedPrice }}</span>
              </p>

              <div v-if="product.size" class="tw-my-3">
                <div class="tw-text-lg tw-font-bold">Size</div>
                <div class="tw-flex tw-gap-2">
                  <button
                    @click="selectProperty('size', item.id)"
                    class="tw-border tw-border-violet-900 tw-rounded-sm tw-px-3"
                    :class="size === item.id ? 'tw-bg-violet-900 tw-text-white' : 'hover:tw-bg-violet-900 hover:tw-text-white tw-text-violet-900'"
                    v-for="item in product.sizes"
                    :key="item.id"
                  >
                    {{ item.name }}
                  </button>
                </div>
              </div>
              <div v-if="product.color" class="tw-my-3">
                <div class="tw-text-lg tw-font-bold">Color</div>
                <div class="tw-flex tw-gap-2">
                  <button
                    @click="selectProperty('color', item.id)"
                    class="tw-border tw-border-violet-900 tw-rounded-sm tw-px-3"
                    :class="color === item.id ? 'tw-bg-violet-900 tw-text-white' : 'hover:tw-bg-violet-900 hover:tw-text-white tw-text-violet-900'"
                    v-for="item in product.colors"
                    :key="item.id"
                  >
                    {{ item.name }}
                  </button>
                </div>
              </div>
              <div v-if="product.variant" class="tw-my-3">
                <div class="tw-text-lg tw-font-bold">{{ product.variant_name }}</div>
                <div class="tw-flex tw-gap-2">
                  <button
                    @click="selectProperty('variant', item.id)"
                    class="tw-border tw-border-violet-900 tw-rounded-sm tw-px-3"
                    :class="variant === item.id ? 'tw-bg-violet-900 tw-text-white' : 'hover:tw-bg-violet-900 hover:tw-text-white tw-text-violet-900'"
                    v-for="item in product.variants"
                    :key="item.id"
                  >
                    {{ item.name }}
                  </button>
                </div>
              </div>
              <div class="tw-my-3">
                <div class="tw-text-lg tw-font-bold">Quantity</div>
                <input v-model="qnt" min="1" type="number" @change="updateQnt" />
              </div>

              <p v-html="product.description"></p>
              <div class="tw-mt-5 tw-flex tw-gap-2">
                <button class="tw-my-5 tw-h-10 tw-w-56 tw-bg-violet-900 tw-text-white tw-border tw-border-violet-900 hover:tw-bg-white hover:tw-text-violet-900" @click="addCart()">
                  Add to cart
                </button>
              </div>
            </div>
          </div>
        </div>
      </bz-container>
    </div>
  </page-layout>
</template>

<style scoped lang="scss">
</style>
