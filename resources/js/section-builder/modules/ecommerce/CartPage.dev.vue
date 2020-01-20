<script>
import { defineComponent } from 'vue'
import PageLayout from '@/section-builder/components/page/PageLayout.vue'
import Cart from '@/section-builder/utils/cart'

export default defineComponent({
  name: 'CartPage',
  components: { PageLayout },
  data() {
    return {
      cart: null
    }
  },
  created() {
    this.cart = new Cart(window.websiteId)
  },
  methods: {
    updateItem(product, amount) {
      this.cart.updateItem(product, amount)
    },
    deleteItem(product) {
      this.cart.deleteItem(product.id)
    },
    gotoCheckout() {
      this.$router.push({
        path: '/checkout'
      })
    }
  }
})
</script>

<template>
  <page-layout>
    <main>
      <nav class="tw-mx-auto tw-mt-4 tw-max-w-[1200px] tw-px-5">
        <ul class="tw-flex tw-items-center">
          <li class="tw-cursor-pointer">
            <a href="{{route('home')}}">
              <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="h-5 w-5">
                <path d="M11.47 3.84a.75.75 0 011.06 0l8.69 8.69a.75.75 0 101.06-1.06l-8.689-8.69a2.25 2.25 0 00-3.182 0l-8.69 8.69a.75.75 0 001.061 1.06l8.69-8.69z" />
                <path
                  d="M12 5.432l8.159 8.159c.03.03.06.058.091.086v6.198c0 1.035-.84 1.875-1.875 1.875H15a.75.75 0 01-.75-.75v-4.5a.75.75 0 00-.75-.75h-3a.75.75 0 00-.75.75V21a.75.75 0 01-.75.75H5.625a1.875 1.875 0 01-1.875-1.875v-6.198a2.29 2.29 0 00.091-.086L12 5.43z"
                />
              </svg>
            </a>
          </li>
          <li>
            <span class="tw-mx-2 tw-text-gray-500">&gt;</span>
          </li>
          <li class="tw-text-gray-500">Cart</li>
        </ul>
      </nav>

      <section class="tw-container tw-mx-auto tw-min-h-screen tw-max-w-[1200px] tw-border-b tw-py-5 lg:tw-flex lg:tw-flex-row lg:tw-py-10">
        <!-- Mobile cart table  -->
        <section class="tw-container tw-mx-auto tw-my-3 tw-flex tw-w-full tw-flex-col tw-gap-3 tw-px-4 md:tw-hidden">
          <template v-for="(item, index) in cart.items" :key="index">
            <div class="tw-flex tw-w-full tw-border tw-px-4 tw-py-4">
              <img class="tw-self-start tw-object-contain" width="90px" :src="item.product.thumbnail" alt="bedroom image" />
              <div class="tw-ml-3 tw-flex tw-w-full tw-flex-col tw-justify-center">
                <div class="tw-flex tw-items-center tw-justify-between">
                  <p class="tw-text-xl tw-font-bold">{{ item.product.name }}</p>
                  <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="h-5 w-5">
                    <path d="M10 3a1.5 1.5 0 110 3 1.5 1.5 0 010-3zM10 8.5a1.5 1.5 0 110 3 1.5 1.5 0 010-3zM11.5 15.5a1.5 1.5 0 10-3 0 1.5 1.5 0 003 0z" />
                  </svg>
                </div>
                <p class="tw-text-xl tw-font-bold">{{ item.product.title }}</p>
                <p class="tw-text-sm tw-text-gray-400" v-if="item.size">Size: {{ item.size.name }}</p>
                <p class="tw-text-sm tw-text-gray-400" v-if="item.color">Color: {{ item.color.name }}</p>
                <p class="tw-text-sm tw-text-gray-400" v-if="item.variant">{{ item.variant.title }}: {{ item.variant.name }}</p>
                <p class="tw-py-3 tw-text-xl tw-font-bold tw-text-violet-900">${{ item.price }}</p>
                <div class="tw-mt-2 tw-flex tw-w-full tw-items-center tw-justify-between">
                  <div class="tw-flex tw-items-center tw-justify-center">
                    <button
                      class="tw-flex tw-h-8 tw-w-8 tw-cursor-pointer tw-items-center tw-justify-center tw-border tw-duration-100 hover:tw-bg-neutral-100 focus:tw-ring-2 focus:tw-ring-gray-500 active:tw-ring-2 active:tw-ring-gray-500"
                      @click="updateCart(item.product, item.quantity - 1)"
                    >
                      &minus;
                    </button>
                    <div class="tw-flex tw-h-8 tw-w-8 tw-cursor-text tw-items-center tw-justify-center tw-border-t tw-border-b active:tw-ring-gray-500">{{ item.quantity }}</div>
                    <button
                      class="tw-flex tw-h-8 tw-w-8 tw-cursor-pointer tw-items-center tw-justify-center tw-border tw-duration-100 hover:tw-bg-neutral-100 focus:tw-ring-2 focus:tw-ring-gray-500 active:ring-2 active:ring-gray-500"
                      @click="updateCart(item.product, item.quantity + 1)"
                    >
                      &#43;
                    </button>
                  </div>

                  <div @click="removeCart(item.product)">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="m-0 h-5 w-5 cursor-pointer">
                      <path
                        fill-rule="evenodd"
                        d="M8.75 1A2.75 2.75 0 006 3.75v.443c-.795.077-1.584.176-2.365.298a.75.75 0 10.23 1.482l.149-.022.841 10.518A2.75 2.75 0 007.596 19h4.807a2.75 2.75 0 002.742-2.53l.841-10.52.149.023a.75.75 0 00.23-1.482A41.03 41.03 0 0014 4.193V3.75A2.75 2.75 0 0011.25 1h-2.5zM10 4c.84 0 1.673.025 2.5.075V3.75c0-.69-.56-1.25-1.25-1.25h-2.5c-.69 0-1.25.56-1.25 1.25v.325C8.327 4.025 9.16 4 10 4zM8.58 7.72a.75.75 0 00-1.5.06l.3 7.5a.75.75 0 101.5-.06l-.3-7.5zm4.34.06a.75.75 0 10-1.5-.06l-.3 7.5a.75.75 0 101.5.06l.3-7.5z"
                        clip-rule="evenodd"
                      />
                    </svg>
                  </div>
                </div>
              </div>
            </div>
          </template>
        </section>
        <!-- /Mobile cart table  -->

        <!-- Desktop cart table  -->
        <section class="tw-hidden tw-h-[600px] tw-w-full tw-max-w-[1200px] tw-grid-cols-1 tw-gap-3 tw-px-5 tw-pb-10 md:tw-grid">
          <table class="tw-table-fixed tw-h-max">
            <thead class="tw-h-16 tw-bg-neutral-100">
              <tr>
                <th class="tw-px-2">ITEM</th>
                <th class="tw-px-2">PRICE</th>
                <th class="tw-px-2">QUANTITY</th>
                <th class="tw-px-2">TOTAL</th>
                <th class="tw-px-2"></th>
              </tr>
            </thead>
            <tbody>
              <template v-for="(item, index) in cart.items" :key="index">
                <tr class="tw-h-[100px] tw-border-b">
                  <td class="tw-align-middle tw-px-2">
                    <div class="tw-flex">
                      <img class="tw-w-[90px] tw-h-16 tw-object-cover" :src="item.product.thumbnail" alt="bedroom image" />
                      <div class="tw-ml-3 tw-flex tw-flex-col tw-justify-center">
                        <p class="tw-text-xl tw-font-bold">{{ item.product.title }}</p>
                        <p class="tw-text-sm tw-text-gray-400" v-if="item.size">Size: {{ item.size.name }}</p>
                        <p class="tw-text-sm tw-text-gray-400" v-if="item.color">Color: {{ item.color.name }}</p>
                        <p class="tw-text-sm tw-text-gray-400" v-if="item.variant">{{ item.variant.title }}: {{ item.variant.name }}</p>
                      </div>
                    </div>
                  </td>
                  <td class="tw-px-2">&#36; {{ item.price }}</td>
                  <td class="tw-px-2">
                    <div class="tw-flex tw-items-center">
                      <button
                        class="tw-flex tw-h-8 tw-w-8 tw-cursor-pointer tw-items-center tw-justify-center tw-border tw-duration-100 hover:tw-bg-neutral-100 focus:tw-ring-2 focus:tw-ring-gray-500 active:tw-ring-2 active:tw-ring-gray-500"
                        @click="updateItem(item.product, item.quantity - 1)"
                      >
                        &minus;
                      </button>
                      <div class="tw-flex tw-h-8 tw-w-8 tw-cursor-text tw-items-center tw-justify-center tw-border-t tw-border-b active:tw-ring-gray-500">
                        {{ item.quantity }}
                      </div>
                      <button
                        class="tw-flex tw-h-8 tw-w-8 tw-cursor-pointer tw-items-center tw-justify-center tw-border tw-duration-100 hover:tw-bg-neutral-100 focus:tw-ring-2 focus:tw-ring-gray-500 active:tw-ring-2 active:tw-ring-gray-500"
                        @click="updateItem(item.product, item.quantity + 1)"
                      >
                        &#43;
                      </button>
                    </div>
                  </td>
                  <td class="tw-px-2">&#36;{{ item.price * item.quantity }}</td>
                  <td class="tw-px-2">
                    <div @click="deleteItem(item.product)">
                      <i class="mdi mdi-delete"></i>
                    </div>
                  </td>
                </tr>
              </template>
            </tbody>
          </table>
        </section>
        <!-- /Desktop cart table  -->

        <!-- Summary  -->

        <section class="tw-mx-auto tw-w-full tw-px-4 md:tw-max-w-[400px]">
          <div class="">
            <div class="tw-border tw-py-5 tw-px-4 tw-shadow-md">
              <p class="font-bold">ORDER SUMMARY</p>

              <div class="tw-flex tw-justify-between tw-border-b tw-py-5">
                <p>Subtotal</p>
                <p>${{ cart.subtotal }}</p>
              </div>

              <div class="tw-flex tw-justify-between tw-border-b tw-py-5">
                <p>Shipping</p>
                <p>Free</p>
              </div>

              <div class="tw-flex tw-justify-between tw-py-5">
                <p>Total</p>
                <p>$ {{ cart.total }}</p>
              </div>

              <button class="tw-w-full tw-bg-violet-900 tw-px-5 tw-py-2 tw-text-white" @click="gotoCheckout">Proceed to checkout</button>
            </div>
          </div>
        </section>
      </section>
    </main>
  </page-layout>
</template>

<style scoped lang="scss">
</style>
