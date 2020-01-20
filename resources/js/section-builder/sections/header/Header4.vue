<template>
  <header class="bz-section-container bz-sec--header-4-root" :class="{ [breakPoint]: true }">
    <bz-background :setting="background">
      <header class="bz-header">
        <bz-logo :title="setting.elements.siteTitle" :logo-size="setting.logoSize" @changeSize="handleLogoChange" />
        <div class="tw-flex tw-items-center">
          <div class="menu-container tw-flex tw-justify-center tw-items-center tw-px-6 tw-py-3 tw-rounded-full tw-shadow max-lg:tw-hidden">
            <bz-nav-bar :viewOnly="viewOnly">
              <template v-slot="{ pageName, active }">
                <page-link :active="active" :name="pageName" class="ml-2" />
              </template>
            </bz-nav-bar>
          </div>
          <div v-if="setting.elements.registerButton || setting.elements.loginButton" class="bz-page-link-root max-lg:tw-hidden">
            <div class="tw-flex tw-items-center" v-if="isLoggedIn">
              <a @click="auth('/home')" class="btn-outline-info btn ml-3">
                <span class="link-text"> My Account </span>
              </a>
            </div>
            <div class="tw-flex tw-items-center" v-else>
              <a v-if="setting.elements.loginButton" @click="auth('/login')" class="link-item-text header tw-ml-6">
                <span class="link-text"> Login </span>
              </a>
              <a v-if="setting.elements.registerButton" @click="auth('/register')" class="link-item-text header tw-ml-6">
                <span class="link-text">Register </span>
              </a>
            </div>
          </div>
          <div v-if="cart?.totalQuantity" class="tw-flex tw-items-center bz-page-link-root">
            <a @click="$router.push('/cart')" class="link-item-text header tw-ml-6 link-item-text header"><i class="mdi mdi-cart tw-text-lg"></i>Cart ({{ cart.totalQuantity }})</a>
          </div>
        </div>

        <div class="lg:tw-hidden tw-flex tw-items-center" :style="{color:textColor}">
          <responsive-menu :setting="setting" />
        </div>
      </header>
    </bz-background>
  </header>
</template>

<script>
import { defineComponent } from 'vue'
import BzBackground from '../../components/section/BzBackground.vue'
import BzLogo from '../../components/section/BzLogo.vue'
import BzNavBar from '../../components/section/BzNavBar.vue'
import headerMixin from '../../mixins/headerMixin'
import PageLink from '../../components/elements/PageLink.vue'
import ResponsiveMenu from '@/section-builder/sections/header/ResponsiveMenu.vue'

export default defineComponent({
  name: 'Header4',
  components: { ResponsiveMenu, PageLink, BzNavBar, BzLogo, BzBackground },
  mixins: [headerMixin]
})
</script>
<style lang="scss" scoped>
.bz-sec--header-4-root {
  @import 'style';

  .menu-container {
    background: rgba(255, 255, 255, 0.4);

    .menu-item {
      width: max-content;
      margin: 0 8px;
      padding-bottom: 2px;
      border-bottom: solid 2px transparent;
      cursor: pointer;

      &.active {
        border-bottom: solid 2px var(--bz-primary-color);
      }

      a {
        text-decoration: none !important;

        &:hover {
          text-decoration: none !important;
        }
      }
    }

    @media (max-width: 500px) {
      display: none;
    }
  }
}

.mobile_view {
  .menu-button {
    display: flex !important;
  }

  .menu-container {
    display: none !important;
  }
}
</style>
