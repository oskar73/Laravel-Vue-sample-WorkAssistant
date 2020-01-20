<template>
  <header class="bz-section-container bz-sec--header-6-root" :class="{ [breakPoint]: true }">
    <div v-if="setting.elements.topBar" :style="{ backgroundColor: headingColor }" class="top-bar">
      <bz-text v-model="data.elements.description" />
    </div>
    <bz-background :setting="background">
      <header class="bz-header tw-px-4">
        <bz-logo v-if="setting.elements.logo" :title="false" :logo-size="setting.logoSize" @changeSize="handleLogoChange" />
        <div v-if="setting.elements.siteTitle" class="tw-flex" :class="{ 'justify-content-center': setting.elements.logo }">
          <bz-title v-model="template.data.name" />
        </div>
        <div class="tw-flex tw-items-center max-lg:tw-hidden">
          <div class="menu-container">
            <bz-nav-bar :view-only="viewOnly">
              <template v-slot="{ pageName, active }">
                <page-link :active="active" :name="pageName" class="ml-2" />
              </template>
            </bz-nav-bar>
          </div>
          <template v-if="setting.elements.registerButton || setting.elements.loginButton">
            <div v-if="isLoggedIn" class="tw-flex tw-items-center">
              <a class="btn-outline-info btn ml-3" @click="auth('/home')">
                <span class="link-text"> My Account </span>
              </a>
            </div>
            <div v-else class="tw-flex tw-items-center">
              <a v-if="setting.elements.loginButton" class="link-item-text header tw-ml-6" @click="auth('/login')">
                <span class="link-text"> Login </span>
              </a>
              <a v-if="setting.elements.registerButton" class="link-item-text header tw-ml-6" @click="auth('/register')">
                <span class="link-text">Register </span>
              </a>
            </div>
          </template>
          <div v-if="cart?.totalQuantity" class="tw-flex tw-items-center bz-page-link-root">
            <a class="link-item-text header tw-ml-6 tw-flex tw-items-center link-item-text header" @click="$router.push('/cart')">
              <i class="mdi mdi-cart tw-text-lg"></i>Cart ({{ cart.totalQuantity }})
            </a>
          </div>
        </div>

        <div class="lg:tw-hidden tw-flex tw-items-center tw-justify-end" :style="{color:textColor}">
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
import BzTitle from '../../components/section/BzTitle.vue'
import PageLink from '../../components/elements/PageLink.vue'
import ResponsiveMenu from '@/section-builder/sections/header/ResponsiveMenu.vue'

export default defineComponent({
  name: 'Header6',
  components: { ResponsiveMenu, PageLink, BzNavBar, BzTitle, BzLogo, BzBackground },
  mixins: [headerMixin]
})
</script>
<style lang="scss" scoped>
.bz-sec--header-6-root {
  @import 'style';

  .top-bar {
    background-color: var(--bz-primary-color);
    padding: 5px 0;
    display: flex;
    justify-content: center;
  }

  .bz-header {
    display: flex;
    justify-content: space-between;
    align-items: center;

    & > div {
      flex: 1;
    }
  }

  .menu-container {
    display: flex;
    justify-content: center;
    flex-direction: row;
    align-items: center;
    padding: 10px 0;
    margin-right: 20px;
    width: 100%;

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
</style>
