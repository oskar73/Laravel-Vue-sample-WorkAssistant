<template>
  <header class="bz-section-container bz-sec--header-5-root" :class="{ [breakPoint]: true }">
    <bz-background :setting="background">
      <header class="bz-header">
        <div class="tw-flex max-lg:tw-hidden">
          <bz-logo v-if="setting.elements.logo" :title="false" :logo-size="setting.logoSize" @changeSize="handleLogoChange" />
          <div v-if="cart?.totalQuantity" class="right-end bz-page-link-root">
            <a @click="$router.push('/cart')" class="link-item-text header tw-ml-6 link-item-text header"><i class="mdi mdi-cart tw-text-lg"></i>Cart ({{ cart.totalQuantity }})</a>
          </div>
        </div>
        <div class="menu-container tw-flex tw-items-center tw-py-2.5 max-lg:tw-hidden">
          <bz-nav-bar :viewOnly="viewOnly">
            <template v-slot="{ pageName, active }">
              <page-link :active="active" :name="pageName" class="ml-2" />
            </template>
          </bz-nav-bar>
          <div v-if="setting.elements.registerButton || setting.elements.loginButton" class="bz-page-link-root">
            <a @click="auth('/home')" class="btn-outline-info btn" v-if="isLoggedIn">
              <span class="link-text"> My Account </span>
            </a>
            <template v-else>
              <a v-if="setting.elements.loginButton" @click="auth('/login')" class="link-item-text header tw-ml-6">
                <span class="menu-item">Login</span>
              </a>
              <a v-if="setting.elements.registerButton" @click="auth('/register')" class="link-item-text header">
                <span class="menu-item">Register</span>
              </a>
            </template>
          </div>
        </div>

        <div class="lg:tw-hidden tw-flex tw-items-center tw-justify-between tw-px-4 tw-w-full" :style="{color:textColor}">
          <bz-logo v-if="setting.elements.logo" :title="false" :logo-size="setting.logoSize" @changeSize="handleLogoChange" />
          <responsive-menu :setting="setting" />
        </div>
      </header>
    </bz-background>
  </header>
</template>

<script>
import { defineComponent } from 'vue'
import headerMixin from '../../mixins/headerMixin'
import BzBackground from '../../components/section/BzBackground.vue'
import BzLogo from '../../components/section/BzLogo.vue'
import BzNavBar from '../../components/section/BzNavBar.vue'
import PageLink from '../../components/elements/PageLink.vue'
import ResponsiveMenu from '@/section-builder/sections/header/ResponsiveMenu.vue'

export default defineComponent({
  name: 'Header5',
  components: { PageLink, BzNavBar, BzLogo, BzBackground, ResponsiveMenu },
  mixins: [headerMixin]
})
</script>
<style lang="scss" scoped>
.bz-sec--header-5-root {
  @import 'style';

  .bz-header {
    display: flex;
    flex-direction: column;
    align-items: center;
  }

  .menu-container {
    justify-content: space-evenly;
    flex-direction: row;

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
  }
  .right-end {
    position: absolute;
    right: 0;
  }
}
</style>
