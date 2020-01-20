<script>
import { defineComponent } from 'vue'
import BzContainer from '@/section-builder/components/section/BzContainer.vue'
import BzBackground from '@/section-builder/components/section/BzBackground.vue'
import productMixin from '@/section-builder/sections/products/productMixin'
import BzAspectView from '@/section-builder/components/section/BzAspectView.vue'
import BzAlignment from '@/section-builder/components/section/BzAlignment.vue'
import EmptyProduct from './EmptyProduct.vue'

export default defineComponent({
  name: 'Product1',
  components: {
    BzAlignment,
    BzAspectView,
    BzBackground,
    BzContainer,
    EmptyProduct
  },
  mixins: [productMixin]
})
</script>

<template>
  <div class="bz-section-container bz-sec--usp-1-root" :class="{ [breakPoint]: true }">
    <bz-background :setting="background" :size="sectionSize">
      <bz-container>
        <bz-alignment :alignment="setting.layouts.alignment">
          <bz-title v-if="setting.elements.title" v-model="data.elements.title" />
          <bz-subtitle v-if="setting.elements.subtitle" v-model="data.elements.subtitle" />
          <bz-text v-if="setting.elements.description" v-model="data.elements.description" />
        </bz-alignment>
        <div>
          <div :class="`tw-grid tw-grid-cols-1 sm:tw-grid-cols-2 lg:tw-grid-cols-${getGridCount(setting.product?.productCount || 4)} tw-gap-4 tw-mt-4`">
            <template v-for="product in products" :key="product.id">
              <div class="tw-flex tw-flex-col tw-bg-[#ffffff66]">
                <a @click="goToPage(product)" class="tw-block tw-cursor-pointer">
                  <div class="tw-relative tw-flex tw-bg-white">
                    <bz-aspect-view>
                      <img class="tw-h-full tw-w-full tw-object-contain" :src="product.thumbnail" :alt="product.name" />
                    </bz-aspect-view>
                    <div class="tw-absolute tw-flex tw-h-full tw-w-full tw-items-center tw-justify-center tw-gap-3 tw-opacity-0 tw-duration-150 hover:tw-opacity-100">
                      <span class="tw-flex tw-h-8 tw-w-8 tw-cursor-pointer tw-items-center tw-justify-center tw-rounded-full tw-bg-amber-400">
                        <i class="mdi mdi-magnify tw-text-lg"></i>
                      </span>
                      <span class="tw-flex tw-h-8 tw-w-8 tw-cursor-pointer tw-items-center tw-justify-center tw-rounded-full tw-bg-amber-400">
                        <i class="mdi mdi-heart tw-text-lg"></i>
                      </span>
                    </div>
                    <div v-if="product.standard_price.slashed_price" class="tw-absolute tw-right-1 tw-mt-3 tw-flex tw-items-center tw-justify-center tw-bg-amber-400">
                      <p class="tw-px-2 tw-py-2 tw-text-sm">
                        &minus; {{ (((product.standard_price.slashed_price - product.standard_price.price) / product.standard_price.slashed_price) * 100).toFixed() }}&percnt; OFF
                      </p>
                    </div>
                  </div>
                </a>
                <div class="tw-px-3 tw-py-1">
                  <p class="tw-mt-2">{{ product.title }}</p>
                  <p class="tw-font-medium tw-text-violet-900">
                    ${{ product.standard_price.price }}
                    <span class="tw-text-sm tw-text-gray-500 tw-line-through">${{ product.standard_price.slashed_price }}</span>
                  </p>

                  <!-- <div class="tw-flex tw-items-center">
                    <template v-for="star in [1, 2, 3, 4, 5]" :key="star">
                      <i class="mdi mdi-star" :class="star <= (product.review || 5) ? 'tw-text-yellow-400' : 'tw-text-gray-200'"></i>
                    </template>
                    <p class="text-sm text-gray-400"></p>
                  </div>
                  <div>
                    <button class="tw-my-5 tw-h-10 tw-w-full tw-bg-violet-900 tw-text-white" @click="addCart(product)">Add to cart</button>
                  </div> -->
                </div>
              </div>
            </template>
            <template v-if="!products.length && isBuilder">
              <empty-product v-for="i in createArrayFrom1ToN(setting.product?.productCount || 4)" :key="i" />
            </template>
            <template v-if="!products.length && !isBuilder">
              <div class="tw-text-xl">There are no products yet...</div>
            </template>
          </div>
          <button v-if="isBuilder" class="btn-add-item" @click.prevent="handleAddItem()">Add Product</button>
          <router-link
            v-if="!isBuilder && products.length > (setting.product?.productCount || 4)"
            :to="allPages.find(({ module_name }) => module_name === 'ecommerce')?.url || '/ecommerce'"
            class="btn-add-item"
          >
            Show More
          </router-link>
        </div>
      </bz-container>
    </bz-background>
  </div>
</template>

<style scoped lang="scss">
.btn-add-item {
  background-color: #0069d9;
  border: none;
  outline: none;
  color: white;
  border-radius: 4px;
  padding: 5px 10px;
  margin-top: 10px;
  position: absolute;
  left: calc(50% - 30px);
  z-index: 100;

  &:hover {
    background-color: #014fa5;
  }
}
</style>
