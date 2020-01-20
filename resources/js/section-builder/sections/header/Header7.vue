<template>
  <header class="bz-section-container bz-sec--header-7-root" :class="{ [breakPoint]: true }">
    <bz-background :setting="background">
      <header class="bz-header tw-px-4">
        <bz-logo :title="false" :logo-size="setting.logoSize" @changeSize="handleLogoChange" />
        <div class="menu-container tw-flex tw-justify-center tw-items-center tw-py-2.5 max-lg:tw-hidden">
          <bz-nav-bar :viewOnly="viewOnly">
            <template v-slot="{ pageName, active }">
              <page-link class="menu-item" :active="active" :name="pageName" />
            </template>
          </bz-nav-bar>
        </div>
        <div class="tw-flex tw-items-center max-lg:tw-hidden">
          <template v-if="setting.elements?.registerButton || setting.elements?.loginButton">
            <template v-if="isLoggedIn">
              <div class="mx-1">
                <a @click="auth('/home')" class="btn-outline-info btn ml-3">
                  <span class="link-text"> My Account </span>
                </a>
              </div>
            </template>
            <template v-else>
              <a v-if="setting.elements.loginButton" @click="auth('/login')" class="link-item-text header tw-ml-6">
                <span class="link-text"> Login </span>
              </a>
              <a v-if="setting.elements.registerButton" @click="auth('/register')" class="link-item-text header tw-ml-6">
                <span class="link-text">Register </span>
              </a>
            </template>
          </template>
          <div v-if="cart?.totalQuantity" class="tw-flex tw-items-center bz-page-link-root">
            <a @click="$router.push('/cart')" class="link-item-text header tw-ml-6 link-item-text header"><i class="mdi mdi-cart tw-text-lg"></i>Cart ({{ cart.totalQuantity }})</a>
          </div>
        </div>

        <div class="lg:tw-hidden tw-flex tw-items-center tw-justify-between" :style="{color:textColor}">
          <responsive-menu :setting="setting" />
        </div>
      </header>
    </bz-background>
  </header>
</template>

<script>
import { defineComponent } from 'vue'
import BzBackground from '../../components/section/BzBackground.vue'
import headerMixin from '../../mixins/headerMixin'
import BzLogo from '../../components/section/BzLogo.vue'
import BzNavBar from '../../components/section/BzNavBar.vue'
import PageLink from '../../components/elements/PageLink.vue'
import ResponsiveMenu from '@/section-builder/sections/header/ResponsiveMenu.vue'

export default defineComponent({
  name: 'Header7',
  components: {
    PageLink,
    BzNavBar,
    BzLogo,
    BzBackground,
    ResponsiveMenu
  },
  mixins: [headerMixin]
})
</script>
<style lang="scss">
.bz-sec--header-7-root {
  @import 'style';

  .bz-header {
    display: flex;
  }

  .menu-container {
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
        text-decoration: none;

        &:hover {
          text-decoration: none;
        }
      }
    }
  }
}
</style>
