<script>
import { defineComponent } from 'vue'
import PageLayout from '@/section-builder/build/components/PageLayout.vue'
import { checkoutItems } from '@/section-builder/apis'
import Cart from '@/section-builder/utils/cart'
import { truncate } from '@/section-builder/utils/helper'

export default defineComponent({
  name: 'CheckoutPage',
  components: { PageLayout },
  data() {
    return {
      firstName: '',
      lastName: '',
      email: '',
      address: '',
      city: '',
      state: '',
      postcode: '',
      phone: '',
      saveInfo: false,
      notes: '',
      cart: null,
      formDisabled: false,
      type: '',
      gateway: window.config.gateway || []
    }
  },
  created() {
    this.cart = new Cart(window.websiteId)
    const { first_name, last_name, email, address, city, state, zip, phone, notes } = this.cart.shippingAddress
    this.firstName = first_name
    this.lastName = last_name
    this.email = email
    this.address = address
    this.city = city
    this.state = state
    this.postcode = zip
    this.phone = phone
    this.notes = notes
  },
  methods: {
    onSubmit(event) {
      event.preventDefault()

      this.formDisabled = true
      const { firstName, lastName, email, address, city, postcode, state, phone, notes, saveInfo, type } = this
      const shippingInfo = { email, first_name: firstName, last_name: lastName, address, city, zip: postcode, state, phone, notes }

      this.cart.updateShippingAddress(shippingInfo)

      checkoutItems(this.cart, type).then((res) => {
        if (res.data.success) {
          window.location.href = res.data.url
        } else {
          if (res.data.action === 'auth') {
            window.location.href = '/login'
          }
          this.formDisabled = false
        }
      })
    },
    truncate,
    deleteItem(id) {
      this.cart.deleteItem(id)
    }
  }
})
</script>

<template>
  <page-layout>
    <main class="tw-container tw-p-12 tw-mx-auto">
      <nav class="">
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

          <li class="tw-text-gray-500">Checkout</li>
        </ul>
      </nav>
      <div class="tw-container tw-py-12 tw-mx-auto">
        <div class="tw-flex tw-flex-col tw-w-full tw-px-0 tw-mx-auto md:tw-flex-row">
          <div class="tw-flex tw-flex-col md:tw-w-full">
            <h2 class="tw-mb-4 tw-font-bold md:tw-text-xl tw-text-heading">Shipping Address</h2>
            <form @submit="onSubmit" class="tw-justify-center tw-w-full tw-mx-auto" method="post" action>
              <div>
                <div class="tw-space-x-0 lg:tw-flex lg:tw-space-x-4">
                  <div class="tw-w-full lg:tw-w-1/2">
                    <label for="firstName" class="tw-block tw-mb-3 tw-text-sm tw-font-semibold tw-text-gray-500">First Name</label>
                    <input
                      v-model="firstName"
                      name="firstName"
                      type="text"
                      placeholder="First Name"
                      class="tw-w-full tw-px-4 tw-py-3 tw-text-sm tw-border tw-border-gray-300 tw-rounded lg:tw-text-sm focus:tw-outline-none focus:tw-ring-1 focus:tw-ring-blue-600"
                      required
                    />
                  </div>
                  <div class="tw-w-full lg:tw-w-1/2">
                    <label for="lastName" class="tw-block tw-mb-3 tw-text-sm tw-font-semibold tw-text-gray-500">Last Name</label>
                    <input
                      v-model="lastName"
                      name="lastName"
                      type="text"
                      placeholder="Last Name"
                      class="tw-w-full tw-px-4 tw-py-3 tw-text-sm tw-border tw-border-gray-300 tw-rounded lg:tw-text-sm focus:tw-outline-none focus:tw-ring-1 focus:tw-ring-blue-600"
                      required
                    />
                  </div>
                </div>
                <div class="tw-mt-4">
                  <div class="tw-w-full">
                    <label for="email" class="tw-block tw-mb-3 tw-text-sm tw-font-semibold tw-text-gray-500">Email</label>
                    <input
                      v-model="email"
                      name="email"
                      type="text"
                      placeholder="Email"
                      class="tw-w-full tw-px-4 tw-py-3 tw-text-sm tw-border tw-border-gray-300 tw-rounded lg:tw-text-sm focus:tw-outline-none focus:tw-ring-1 focus:tw-ring-blue-600"
                      required
                    />
                  </div>
                </div>
                <div class="tw-mt-4">
                  <div class="tw-w-full">
                    <label for="Address" class="tw-block tw-mb-3 tw-text-sm tw-font-semibold tw-text-gray-500">Address</label>
                    <textarea
                      v-model="address"
                      class="tw-w-full tw-px-4 tw-py-3 text-xs tw-border tw-border-gray-300 tw-rounded lg:tw-text-sm focus:tw-outline-none focus:tw-ring-1 focus:tw-ring-blue-600"
                      name="Address"
                      cols="20"
                      rows="4"
                      placeholder="Address"
                      required
                    ></textarea>
                  </div>
                </div>
                <div class="tw-mt-4 tw-space-x-0 lg:tw-flex lg:tw-space-x-4">
                  <div class="tw-w-full lg:tw-w-1/3">
                    <label for="city" class="tw-block tw-mb-3 tw-text-sm tw-font-semibold tw-text-gray-500">City</label>
                    <input
                      v-model="city"
                      name="city"
                      type="text"
                      placeholder="City"
                      class="tw-w-full tw-px-4 tw-py-3 tw-text-sm tw-border tw-border-gray-300 tw-rounded lg:tw-text-sm focus:tw-outline-none focus:tw-ring-1 focus:tw-ring-blue-600"
                      required
                    />
                  </div>
                  <div class="tw-w-full lg:tw-w-1/3">
                    <label for="state" class="tw-block tw-mb-3 tw-text-sm tw-font-semibold tw-text-gray-500">State</label>
                    <input
                      v-model="state"
                      name="state"
                      type="text"
                      placeholder="State"
                      class="tw-w-full tw-px-4 tw-py-3 tw-text-sm tw-border tw-border-gray-300 tw-rounded lg:tw-text-sm focus:tw-outline-none focus:tw-ring-1 focus:tw-ring-blue-600"
                      required
                    />
                  </div>
                  <div class="tw-w-full lg:tw-w-1/3">
                    <label for="postcode" class="tw-block tw-mb-3 tw-text-sm tw-font-semibold tw-text-gray-500"> Postcode</label>
                    <input
                      v-model="postcode"
                      name="postcode"
                      type="text"
                      placeholder="Post Code"
                      class="tw-w-full tw-px-4 tw-py-3 tw-text-sm tw-border tw-border-gray-300 tw-rounded lg:tw-text-sm focus:tw-outline-none focus:tw-ring-1 focus:tw-ring-blue-600"
                      required
                    />
                  </div>
                </div>
                <div class="tw-mt-4 tw-space-x-0 lg:tw-flex lg:tw-space-x-4">
                  <div class="tw-w-full lg:tw-w-1/2">
                    <label for="phone" class="tw-block tw-mb-3 tw-text-sm tw-font-semibold tw-text-gray-500">Phone</label>
                    <input
                      v-model="phone"
                      name="phone"
                      type="text"
                      placeholder="+1 (123) 456 7890"
                      class="tw-w-full tw-px-4 tw-py-3 tw-text-sm tw-border tw-border-gray-300 tw-rounded lg:tw-text-sm focus:tw-outline-none focus:tw-ring-1 focus:tw-ring-blue-600"
                      required
                    />
                  </div>
                </div>
                <div class="tw-flex tw-items-center mt-4">
                  <label class="tw-flex tw-items-center tw-text-sm group tw-text-heading">
                    <input v-model="saveInfo" type="checkbox" class="tw-w-5 tw-h-5 tw-border tw-border-gray-300 tw-rounded focus:tw-outline-none focus:tw-ring-1" />
                    <span class="tw-ml-2">Save this information for next time</span></label
                  >
                </div>
                <div class="tw-relative pt-3 xl:pt-6">
                  <label for="note" class="tw-block tw-mb-3 tw-text-sm tw-font-semibold tw-text-gray-500"> Notes (Optional)</label
                  ><textarea
                    name="notes"
                    class="tw-flex tw-items-center tw-w-full tw-px-4 tw-py-3 tw-text-sm tw-border tw-border-gray-300 tw-rounded focus:tw-outline-none focus:tw-ring-1 focus:tw-ring-blue-600"
                    rows="4"
                    placeholder="Notes for delivery"
                  ></textarea>
                </div>
                <div class="tw-mt-4 tw-flex tw-justify-center tw-gap-2">
                  <button
                    :disabled="!cart.totalQuantity || formDisabled"
                    type="submit"
                    @click="type = 'stripe'"
                    v-if="gateway.includes('stripe')"
                    class="tw-w-full tw-px-6 tw-py-2 text-blue-200 tw-bg-blue-600 hover:tw-bg-blue-900 tw-text-white disabled:tw-bg-gray-200"
                  >
                    Process with Stripe
                  </button>
                  <button
                    :disabled="!cart.totalQuantity || formDisabled"
                    type="submit"
                    @click="type = 'paypal'"
                    v-if="gateway.includes('paypal')"
                    class="tw-w-full tw-px-6 tw-py-2 text-blue-200 tw-bg-blue-500 hover:tw-bg-blue-800 tw-text-white disabled:tw-bg-gray-200"
                  >
                    Process with Paypal
                  </button>
                </div>
              </div>
            </form>
          </div>
          <div v-if="cart" class="tw-flex tw-flex-col tw-w-full ml-0 lg:tw-ml-12 lg:tw-w-2/5">
            <div class="tw-pt-12 md:tw-pt-0 2xl:tw-ps-4">
              <h2 class="tw-text-xl tw-font-bold">Order Summary</h2>
              <div class="tw-mt-8">
                <div class="tw-flex tw-flex-col tw-space-y-4">
                  <div v-for="(item, index) in cart.items" :key="index" class="tw-flex tw-space-x-4">
                    <div>
                      <img :src="item.product.media[0].preview_url || item.product.media[0].original_url" alt="image" class="tw-w-60" />
                    </div>
                    <div>
                      <h2 class="tw-text-xl tw-font-bold">{{ item.product.title }}</h2>
                      <p class="tw-text-sm">{{ truncate(item.product.description, 48, true) }}</p>
                      <span class="tw-text-red-600">Price</span> ${{ item.price }}
                      <p><span class="tw-text-red-600">Quantity</span> {{ item.quantity }}</p>
                    </div>
                    <div class="tw-cursor-pointer" @click="deleteItem(item.product.id)">
                      <svg xmlns="http://www.w3.org/2000/svg" class="tw-w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                      </svg>
                    </div>
                  </div>
                </div>
              </div>
              <div class="tw-flex tw-p-4 tw-mt-4">
                <h2 class="tw-text-xl tw-font-bold">ITEMS {{ cart.totalQuantity }}</h2>
              </div>
              <div
                class="tw-flex tw-items-center tw-w-full tw-py-4 tw-text-sm tw-font-semibold tw-border-b tw-border-gray-300 lg:tw-py-5 lg:tw-px-3 tw-text-heading last:tw-border-b-0 last:tw-text-base last:tw-pb-0"
              >
                Subtotal<span class="tw-ml-2">${{ cart.subtotal }}</span>
              </div>
              <!-- <div
                class="tw-flex tw-items-center tw-w-full tw-py-4 tw-text-sm tw-font-semibold tw-border-b tw-border-gray-300 lg:tw-py-5 lg:tw-px-3 tw-text-heading last:tw-border-b-0 last:tw-text-base last:tw-pb-0"
              >
                Shipping Tax<span class="tw-ml-2">$10</span>
              </div> -->
              <div
                class="tw-flex tw-items-center tw-w-full tw-py-4 tw-text-sm tw-font-semibold tw-border-b tw-border-gray-300 lg:tw-py-5 lg:tw-px-3 tw-text-heading last:tw-border-b-0 last:tw-text-base last:tw-pb-0"
              >
                Total<span class="tw-ml-2">${{ cart.total }}</span>
              </div>
            </div>
          </div>
        </div>
      </div>
    </main>
  </page-layout>
</template>
