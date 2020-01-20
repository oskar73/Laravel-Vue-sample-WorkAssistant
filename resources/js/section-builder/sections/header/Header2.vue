<template>
  <header class="bz-section-container bz-sec--header-2-root" :class="{ [breakPoint]: true }">
    <bz-background :setting="background">
      <header class="bz-header">
        <div class="max-md:tw-mx-auto">
          <bz-social-icons />
        </div>
        <div class="tw-flex max-md:tw-hidden">
          <bz-logo :title="setting.elements.siteTitle" :logo-size="setting.logoSize" resize-direction="left-bottom" @changeSize="handleLogoChange" />
          <div v-if="cart?.totalQuantity" class="d-flex bz-page-link-root">
            <a @click="$router.push('/cart')" class="nav-item ml-6 link-item-text header"><i class="mdi mdi-cart tw-text-lg"></i>Cart ({{ cart.totalQuantity }})</a>
          </div>
        </div>
      </header>
      <bz-divider :line="true" />
      <div class="menu-container tw-items-center tw-justify-center tw-flex tw-py-2.5 max-md:tw-hidden">
        <bz-nav-bar :viewOnly="viewOnly">
          <template v-slot="{ pageName, active }">
            <page-link class="menu-item link-text" :active="active" :name="pageName" />
          </template>
        </bz-nav-bar>
        <template v-if="setting.elements.registerButton || setting.elements.loginButton">
          <div class="d-flex align-items-center tw-pb-0.5 bz-page-link-root">
            <template v-if="isLoggedIn">
              <a @click="auth('/home')" class="link-item-text header tw-ml-6">
                <span class="link-text"> My Account </span>
              </a>
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
        </template>
      </div>

      <div class="md:tw-hidden tw-flex tw-items-center tw-justify-between tw-px-4" :style="{color:textColor}">
        <bz-logo :title="setting.elements.siteTitle" :logo-size="setting.logoSize" resize-direction="left-bottom" @changeSize="handleLogoChange" />
        <responsive-menu :setting="setting" />
      </div>
    </bz-background>
  </header>
</template>

<script>
import { defineComponent } from 'vue'
import BzButton from '../../components/section/BzButton.vue'
import BzBackground from '../../components/section/BzBackground.vue'
import BzSocialIcons from '../../components/section/BzSocialIcons.vue'
import BzDivider from '../../components/section/BzDivider.vue'
import BzLogo from '../../components/section/BzLogo.vue'
import BzNavBar from '../../components/section/BzNavBar.vue'
import headerMixin from '../../mixins/headerMixin'
import PageLink from '../../components/elements/PageLink.vue'
import ResponsiveMenu from '@/section-builder/sections/header/ResponsiveMenu.vue'

export default defineComponent({
  name: 'Header2',
  components: {
    PageLink,
    BzNavBar,
    BzLogo,
    BzDivider,
    BzSocialIcons,
    BzBackground,
    BzButton,
    ResponsiveMenu
  },
  mixins: [headerMixin]
})
</script>
<style lang="scss">
.bz-sec--header-2-root {
  @import 'style';

  .bz-header {
    justify-content: space-between;
    align-items: center;
  }

  .auth-group {
    right: 8px;
    bottom: 0;
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

  .ml-6 {
    margin-left: 24px;
  }
}
</style>
