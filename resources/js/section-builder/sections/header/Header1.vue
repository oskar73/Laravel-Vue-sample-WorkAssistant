<template>
  <header :data-layout="setting.layout" class="bz-section-container bz-sec--header-1-root" :class="{ [breakPoint]: true }">
    <bz-background :setting="background">
      <header class="bz-header tw-w-[90%]">
        <bz-logo :title="setting.elements.siteTitle" :logo-size="setting.logoSize" @changeSize="handleLogoChange" />
        <div class="tw-flex tw-items-center max-lg:tw-hidden">
          <bz-nav-bar :max-width="400" :view-only="viewOnly">
            <template v-slot="{ pageName, active }">
              <div class="link-item-text header tw-ml-6" :class="{ active }">
                <page-link :active="active" :name="pageName" />
              </div>
            </template>
          </bz-nav-bar>
        </div>

        <div v-if="setting.elements.registerButton || setting.elements.loginButton" class="tw-flex tw-items-center max-lg:tw-hidden">
          <a v-if="isLoggedIn" class="btn btn-outline-info ml-3" @click="auth('/home')">
            <span class="link-text">My Account</span>
          </a>
          <template v-else>
            <a v-if="setting.elements.loginButton" class="link-item-text header tw-ml-6" :style="{color: textColor}" @click="auth('/login')">
              <span class="link-text"> Login </span>
            </a>
            <a v-if="setting.elements.registerButton" class="link-item-text header tw-ml-6" :style="{color: textColor}" @click="auth('/register')">
              <span class="link-text">Register </span>
            </a>
          </template>
        </div>
        <div v-if="cart?.totalQuantity" class="d-flex align-items-center bz-page-link-root">
          <a class="link-item-text header tw-ml-6 link-item-text header" @click="$router.push('/cart')"><i class="mdi mdi-cart tw-text-lg tw-flex max-lg:tw-hidden"></i>Cart ({{ cart.totalQuantity }})</a>
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
  name: 'Header1',
  components: {
    ResponsiveMenu,
    PageLink,
    BzNavBar,
    BzLogo,
    BzBackground
  },
  mixins: [headerMixin]
})
</script>
<style lang="scss">
.bz-sec--header-1-root {
  @import 'style';

  .nav-item {
    color: var(--bz-theme-text-color);
    font-size: 16px;
    font-weight: bold;
    white-space: nowrap;
    cursor: pointer;

    &.active {
      color: blue;
    }
  }

  .dropdown {
    .nav-item {
      border-bottom: solid 1px #80808034;
      padding: 6px 20px;
      margin-left: 0 !important;

      &:hover {
        background-color: #55555512;
      }
    }
  }
}
</style>
