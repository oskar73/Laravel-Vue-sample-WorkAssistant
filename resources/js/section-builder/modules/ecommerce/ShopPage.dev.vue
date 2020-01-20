<script>
import { defineComponent } from 'vue'
import BzContainer from '@/section-builder/components/section/BzContainer.vue'
import BzAspectView from '@/section-builder/components/section/BzAspectView.vue'
import PageLayout from '@/section-builder/components/page/PageLayout.vue'
import { getEcommerceModuleCategories, getEcommerceProducts } from '@/section-builder/apis'
import Cart from '@/section-builder/utils/cart'

export default defineComponent({
  name: 'ShopPage',
  components: {
    PageLayout,
    BzAspectView,
    BzContainer
  },
  data() {
    return {
      selectedCategories: [],
      shopCategories: [],
      products: [],
      selectedProducts: []
    }
  },
  created() {
    getEcommerceModuleCategories().then((res) => {
      this.shopCategories = res.data.categories
    })
    getEcommerceProducts().then((res) => {
      this.products = res.data.products
      this.selectedProducts = this.products
    })
  },
  methods: {
    addCart(product) {
      const cart = new Cart(window.websiteId)
      cart.addItem(product)
      this.$router.push({ path: '/product' })
    },
    selectCategory(value) {
      const index = this.selectedCategories.findIndex((v) => v === value)
      if (index === -1) this.selectedCategories.push(value)
      else this.selectedCategories.splice(index, 1)

      if (this.selectedCategories.length) {
        this.selectedProducts = this.products.filter(({ category_id }) => this.selectedCategories.includes(category_id))
      } else {
        this.selectedProducts = this.products
      }
    }
  }
})
</script>

<template>
  <page-layout>
    <div class="tw-py-5">
      <bz-container>
        <div class="tw-flex">
          <div class="tw-w-[320px]">
            <h4 class="tw-text-xl">Categories</h4>
            <div class="tw-mt-3">
              <template v-for="category in shopCategories" :key="category.id">
                <div class="tw-flex tw-my-1 tw-items-center">
                  <input type="checkbox" :value="category.id" @change="selectCategory(category.id)" class="tw-mr-2" />
                  <span>{{ category.name }}</span>
                </div>
              </template>
            </div>
          </div>
          <div class="tw-flex-1">
            <div class="tw-grid tw-grid-cols-4 tw-gap-4">
              <template v-for="product in selectedProducts" :key="product.id">
                <div class="tw-flex tw-flex-col">
                  <router-link :to="`/product/${product.id}`" class="tw-block tw-cursor-pointer">
                    <div class="tw-relative tw-flex tw-bg-white">
                      <bz-aspect-view>
                        <img class="tw-h-full tw-w-full tw-object-contain" :src="product.thumbnail" :alt="product.name" />
                      </bz-aspect-view>
                      <div class="tw-absolute tw-flex tw-h-full tw-w-full tw-items-center tw-justify-center tw-gap-3 tw-opacity-0 tw-duration-150 hover:tw-opacity-100">
                        <router-link :to="`/product/${product.id}`">
                          <span class="tw-flex tw-h-8 tw-w-8 tw-cursor-pointer tw-items-center tw-justify-center tw-rounded-full tw-bg-amber-400">
                            <i class="mdi mdi-magnify tw-text-lg"></i>
                          </span>
                        </router-link>
                        <span class="tw-flex tw-h-8 tw-w-8 tw-cursor-pointer tw-items-center tw-justify-center tw-rounded-full tw-bg-amber-400">
                          <i class="mdi mdi-heart tw-text-lg"></i>
                        </span>
                      </div>
                      <div class="tw-absolute tw-right-1 tw-mt-3 tw-flex tw-items-center tw-justify-center tw-bg-amber-400" v-if="product.standard_price.slashed_price">
                        <p class="tw-px-2 tw-py-2 tw-text-sm">
                          &minus; {{ (((product.standard_price.slashed_price - product.standard_price.price) / product.standard_price.slashed_price) * 100).toFixed() }}&percnt; OFF
                        </p>
                      </div>
                    </div>
                  </router-link>
                  <div class="tw-px-2">
                    <p class="tw-mt-2">
                      {{ product.title }}
                    </p>
                    <p class="tw-font-medium tw-text-violet-900">
                      ${{ product.standard_price.price }}
                      <span class="tw-text-sm tw-text-gray-500 tw-line-through">${{ product.standard_price.slashed_price }}</span>
                    </p>

                    <!-- <div class="tw-flex tw-items-center">
                      <template v-for="star in [1, 2, 3, 4, 5]" :key="star">
                        <i class="mdi mdi-star" :class="star <= (product.review || 5) ? 'tw-text-yellow-400' : 'tw-text-gray-200'"></i>
                      </template>
                      <p class="text-sm text-gray-400"></p>
                    </div> -->
                    <!-- <div>
                      <button class="tw-mt-5 tw-mb-2 tw-h-10 tw-w-full tw-border tw-border-violet-900 tw-bg-violet-900 tw-text-white hover:tw-bg-white hover:tw-text-violet-900" @click="addCart(product)">Add to cart</button>
                    </div>
                    <div>
                      <button class="tw-mb-5 tw-h-10 tw-w-full tw-border tw-border-violet-900 tw-text-violet-900 hover:tw-bg-violet-900 hover:tw-text-white" @click="buyNow(product)">Buy now</button>
                    </div> -->
                  </div>
                </div>
              </template>
            </div>
          </div>
        </div>
      </bz-container>
    </div>
  </page-layout>
</template>

<style scoped lang="scss">
</style>
