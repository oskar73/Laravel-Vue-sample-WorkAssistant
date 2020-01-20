<template>
  <header class="bz-section-container bz-sec--header-8-root" :class="{ [breakPoint]: true }">
    <bz-background :setting="background">
      <bz-container v-if="setting.elements.topBar">
        <div class="tw-flex tw-items-center tw-justify-between max-lg:tw-hidden">
          <bz-logo v-if="setting.elements.logo" :title="setting.elements.title" />
        </div>
      </bz-container>
      <bz-container>
        <div class="tw-flex tw-justify-end max-lg:tw-hidden">
          <bz-navigation v-model="data.navigations">
            <template v-slot="{ item }">
              <div class="nav-item link-text">
                {{ item.name }}
                <div v-if="item.icon" class="nav-item-icon link-text">
                  <i v-if="item.icon" :class="item.icon" style="padding-bottom: 2px" class="mr-1"></i>
                </div>
              </div>
            </template>
          </bz-navigation>
          <template v-if="setting.elements.registerButton || setting.elements.loginButton">
            <div v-if="isLoggedIn" class="tw-flex tw-items-center">
              <a class="btn btn-outline-info ml-3" @click="auth('/home')">
                <span class="link-text">My Account</span>
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
        </div>

        <div class="lg:tw-hidden tw-flex tw-items-center tw-justify-between" :style="{color:textColor}">
          <bz-logo v-if="setting.elements.logo" :title="false" />
          <responsive-menu :setting="setting" />
        </div>
      </bz-container>
    </bz-background>
  </header>
</template>

<script>
import { defineComponent } from 'vue'
import BzBackground from '../../components/section/BzBackground.vue'
import headerMixin from '../../mixins/headerMixin'
import BzContainer from '../../components/section/BzContainer.vue'
import BzNavigation from '../../components/page/BzNavigation.vue'
import BzLogo from '../../components/section/BzLogo.vue'
import ResponsiveMenu from '@/section-builder/sections/header/ResponsiveMenu.vue'

export default defineComponent({
  name: 'Header8',
  components: {
    BzLogo,
    BzNavigation,
    BzContainer,
    BzBackground,
    ResponsiveMenu
  },
  mixins: [headerMixin]
})
</script>
<style lang="scss">
.bz-sec--header-8-root {
  @import 'style';
  .nav-item {
    padding: 0 10px;
    display: flex;
    align-items: center;

    .nav-item-icon {
      font-size: 14px;
      margin-left: 4px;
    }
  }
}
</style>
