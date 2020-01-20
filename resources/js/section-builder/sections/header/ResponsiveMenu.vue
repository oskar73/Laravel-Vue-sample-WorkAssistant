<script>
import { defineComponent } from 'vue'
import { Menu, MenuButton, MenuItem, MenuItems } from '@headlessui/vue'
import appMixin from '@/section-builder/mixins/appMixin'

export default defineComponent({
  name: 'ResponsiveMenu',
  components: {
    Menu,
    MenuButton,
    MenuItem,
    MenuItems
  },
  props: ['setting'],
  data() {
    return {
      isLoggedIn: window.config.auth
    }
  },
  mixins: [appMixin],
  methods: {
    showMenuItem (page) {
      if (page.type === 'new-page') {
        return false
      }

      if (page.type === 'module') {
        return this.modules.activeModules.includes(page.module_name)
      }

      return true
    },
    auth(route) {
      if (window.location.href.includes('/website')) return
      window.location.href = route
    }
  }
})
</script>

<template>
  <Menu as="div" class="sm:tw-relative tw-inline-block tw-text-left">
    <div>
      <MenuButton
        class="tw-inline-flex tw-w-full tw-justify-center tw-rounded-md tw-bg-black/20 tw-px-4 py-2 tw-text-white hover:tw-bg-black/30 focus:tw-outline-none focus-visible:tw-ring-2 focus-visible:tw-ring-white/75"
      >
        <div class="tw-cursor-pointer">
          <i class="mdi mdi-menu tw-text-2xl"></i>
        </div>
      </MenuButton>
    </div>

    <transition
      enter-active-class="tw-transition tw-duration-100 tw-ease-out"
      enter-from-class="tw-transform tw-scale-95 tw-opacity-0"
      enter-to-class="tw-transform tw-scale-100 tw-opacity-100"
      leave-active-class="tw-transition tw-duration-75 tw-ease-in"
      leave-from-class="tw-transform tw-scale-100 tw-opacity-100"
      leave-to-class="tw-transform tw-scale-95 tw-opacity-0"
    >
      <MenuItems
        class="max-sm:tw-w-full tw-absolute tw-right-0 tw-w-56 tw-mt-1 tw-origin-top-right tw-divide-y tw-divide-gray-100 tw-rounded-md tw-bg-white tw-shadow-lg tw-ring-1 tw-ring-black/5 focus:tw-outline-none tw-z-50"
      >
        <div class="px-1 py-1">
          <div v-for="(page, index) of allPages" :key="index">
            <a v-if="showMenuItem(page)" :key="index" :href="pageUrl(page.url)" :class="{'tw-pointer-events-none': isBuilder}">
              <button
                class="hover:tw-bg-violet-500 hover:tw-text-white tw-text-gray-900 tw-group tw-flex tw-w-full tw-items-center tw-rounded-md tw-px-2 tw-py-2 tw-text-sm"
              >
                {{ page.name }}
              </button>
            </a>
          </div>
        </div>
        <div v-if="setting.elements.registerButton || setting.elements.loginButton" class="tw-px-1 tw-py-1">
          <MenuItem v-if="isLoggedIn">
            <button
              @click="auth('/home')"
              class="tw-group tw-flex tw-w-full tw-items-center tw-rounded-md tw-px-2 tw-py-2 tw-text-sm"
            >
              My Account
            </button>
          </MenuItem>
          <template v-else>
            <MenuItem v-if="setting.elements.loginButton">
              <button
                @click="auth('/login')"
                class="tw-group tw-flex tw-w-full tw-items-center tw-rounded-md tw-px-2 tw-py-2 tw-text-sm"
              >
                Login
              </button>
            </MenuItem>
            <MenuItem v-if="setting.elements.registerButton">
              <button
                @click="auth('/register')"
                class="tw-group tw-flex tw-w-full tw-items-center tw-rounded-md tw-px-2 tw-py-2 tw-text-sm"
              >
                Register
              </button>
            </MenuItem>
          </template>
        </div>
      </MenuItems>
    </transition>
  </Menu>
</template>

<style scoped lang="scss">

</style>
