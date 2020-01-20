<template>
  <header class="bz-section-container bz-sec--header-3-root" :class="{ [breakPoint]: true }">
    <bz-background :setting="background">
      <header class="bz-header">
        <div class="header-logo">
          <bz-logo :title="setting.elements.siteTitle" :logo-size="setting.logoSize" @changeSize="handleLogoChange" />
        </div>
        <div class="tw-justify-center align-items-center tw-flex max-lg:tw-hidden">
          <div class="menu-container">
            <bz-nav-bar :viewOnly="viewOnly">
              <template v-slot="{ pageName, active }">
                <page-link class="menu-item link-text" :active="active" :name="pageName" />
              </template>
            </bz-nav-bar>
          </div>
          <div v-if="setting.elements.registerButton || setting.elements.loginButton" class="mr-2 tw-flex bz-page-link-root tw-pb-1 max-lg:tw-hidden">
            <template v-if="isLoggedIn">
              <div class="mx-1">
                <a @click="auth('/home')" class="btn-outline-info btn">
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
          </div>
        </div>
        <div class="header-button">
          <bz-button v-if="setting.layouts?.headerButton" v-model="data.headerButton" />
        </div>
        <div v-if="cart?.totalQuantity" class="d-flex bz-page-link-root">
          <a @click="$router.push('/cart')" class="link-item-text header tw-ml-6 link-item-text header"><i class="mdi mdi-cart tw-text-lg"></i>Cart ({{ cart.totalQuantity }})</a>
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
import BzButton from '../../components/section/BzButton.vue'
import BzBackground from '../../components/section/BzBackground.vue'
import BzLogo from '../../components/section/BzLogo.vue'
import BzNavBar from '../../components/section/BzNavBar.vue'
import headerMixin from '../../mixins/headerMixin'
import PageLink from '../../components/elements/PageLink.vue'
import ResponsiveMenu from '@/section-builder/sections/header/ResponsiveMenu.vue'

export default defineComponent({
  name: 'Header3',
  components: { BzNavBar, BzLogo, BzBackground, BzButton, PageLink, ResponsiveMenu },
  mixins: [headerMixin]
})
</script>
<style lang="scss" scoped>
.bz-sec--header-3-root {
  @import 'style';

  .bz-header {
    display: flex;
    align-items: center;
  }

  .header-logo,
  .header-button {
    flex: 1;
  }

  .header-button {
    display: flex;
    justify-content: flex-end;
  }

  .menu-container {
    display: flex;
    justify-content: center;
    flex-direction: row;
    align-items: center;
    padding: 10px 0;
    margin: 0 10px;

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
