<template>
  <header>
    <div class="logo tw-flex tw-items-center tw-content-between ml-3">
      <a href="/" class="tw-mr-5">
        <img :src="asset('assets/img/logo_sm.png')" style="max-width: 40px; object-fit: contain" alt="bizinabox logo" />
      </a>
      <div>
        <button class="quick-view-button" @click="handleQuickView">View Quick Video</button>
      </div>
    </div>
    <div class="steps">
      <button class="step-button" @click.prevent="chooseNewDesign">New Design</button>
      <button v-if="!liveView" class="step-button" @click.prevent="chooseLogo">Choose {{ graphic.title }}</button>
      <button v-if="!liveView" class="step-button" :disabled="isSavingDesign" @click.prevent="handleSaveDesign">
        <spinner v-if="isSavingDesign" />
        <span> Save {{ graphic.title }} </span>
      </button>
      <button v-if="!liveView" class="step-button" @click.prevent="viewLive">View Live</button>
      <button class="step-button" @click.prevent="dashboard">Dashboard</button>
    </div>
    <div v-click-outside="handleOutsideClick" class="tw-h-full tw-border-l tw-border-gray-100">
      <div v-if="user" class="tw-h-full tw-relative tw-inline-block tw-text-left tw-text-xl tw-px-5">
        <div class="tw-h-full tw-flex tw-items-center tw-text-white">
          <button type="button" class="tw-inline-flex tw-justify-center tw-gap-x-1.5 tw-rounded-md tw-px-3" @click="open = true">
            {{ user.name }}
            <i class="mdi mdi-chevron-down"></i>
          </button>
        </div>
        <div
          v-show="open"
          class="tw-absolute tw-right-0 tw-z-10 tw-mt-1 tw-w-40 tw-origin-top-right tw-divide-y tw-divide-gray-100 tw-rounded-md tw-bg-white tw-shadow-lg tw-ring-1 tw-ring-black tw-ring-opacity-5 tw-focus:outline-none"
        >
          <div v-if="isAdmin()" class="py-1 hover:tw-bg-gray-100" role="none">
            <a :href="url.admin" class="tw-text-gray-700 tw-block tw-px-4 tw-py-2 tw-text-sm"> <i class="el-icon-setting"></i> Admin Panel </a>
          </div>
          <div class="py-1 hover:tw-bg-gray-100" role="none">
            <a :href="url.user" class="tw-text-gray-700 tw-block tw-px-4 tw-py-2 tw-text-sm"> <i class="el-icon-setting"></i> My Panel </a>
          </div>
          <div class="py-1 hover:tw-bg-gray-100" role="none">
            <a :href="url.profile" class="tw-text-gray-700 tw-block tw-px-4 tw-py-2 tw-text-sm"> <i class="el-icon-user-solid"></i> Profile </a>
          </div>
          <div class="py-1 hover:tw-bg-gray-100" role="none" @click.prevent="logout">
            <span class="tw-text-gray-700 tw-block tw-px-4 tw-py-2 tw-text-sm"> <i class="el-icon-minus"></i> Logout </span>
            <form id="logout-form" :action="url.logout" method="POST" hidden="hidden">@csrf</form>
          </div>
        </div>
      </div>
      <div v-else class="tw-p-4 tw-flex tw-items-center tw-justify-center">
        <a :href="getUrlByRoute('login')" class="login-button"> Login </a>
        <a :href="getUrlByRoute('register')" class="create-account-button"> Create account </a>
      </div>
    </div>
    <choose-new-design-modal :show="showNewDesignModal" @close="showNewDesignModal = false" />
    <save-confirm-modal :open="openSaveConfirmModal" @close="handleCancelConfirm" @save="handleSaveConfirm" />
  </header>
</template>

<script>
import stepsNavigation from '../../mixins/steps-navigation'
import appMixin from '../../mixins/app-mixin'
import downloadProduct from '../../mixins/download-product'
import eventBus from '@/public/eventBus'
import ChooseNewDesignModal from '@/svg-editor/components/modals/ChooseNewDesignModal.vue'
import Spinner from '../../../public/Spinner.vue'
import editorMixin from '../../mixins/editor-mixin'
import SaveConfirmModal from '@/svg-editor/components/modals/save-confirm-modal.vue'

export default {
  name: 'HeaderPage',
  components: { SaveConfirmModal, Spinner, ChooseNewDesignModal },
  mixins: [stepsNavigation, appMixin, downloadProduct, editorMixin],
  data() {
    return {
      user: window.user,
      logotype: window.logotype,
      saved: true,
      open: false,
      showNewDesignModal: 0,
      openSaveConfirmModal: false,
      viewDashboard: false,
      url: {
        logout: window.route('ssoLogout'),
        login: window.route('login'),
        register: window.route('register'),
        profile: window.route('user.dashboard'),
        admin: window.route('admin.dashboard'),
        user: window.route('user.graphics.index')
      }
    }
  },
  mounted() {
    eventBus.$on('editor-design-changed', () => {
      this.saved = false
    })
  },
  methods: {
    handleOutsideClick() {
      this.open = false
    },
    handleSaveDesign() {
      this.isSavingDesign = true
      this.saveDesign(0).then(() => {
        this.isSavingDesign = false
        this.notification({
          title: 'Success!',
          type: 'success',
          message: 'Successfully saved!'
        })
      })
    },
    handleCancelConfirm() {
      this.openSaveConfirmModal = false
      this.viewDashboard = true
    },
    handleSaveConfirm() {
      this.openSaveConfirmModal = false
      if (this.viewDashboard) {
        window.location.href = '/account/graphics'
      } else {
        this.liveView = true
      }
    },
    isAdmin() {
      return Boolean(window.user.roles.length && window.user.roles[0].name === 'admin')
    },
    clickMenu(routeName) {
      window.location.href = routeName
    },
    handleQuickView() {
      const screenWidth = window.innerWidth
      const screenHeight = window.innerHeight

      const left = screenWidth * 0.2
      const top = screenHeight * 0.2
      const width = screenWidth * 0.6
      const height = screenHeight * 0.6

      window.open('/videos', '_blank ', `menubar=1,resizable=1,width=${width},height=${height},left=${left},top=${top}`)
    },
    viewLive() {
      if (this.saved) {
        this.liveView = true
      } else {
        this.openSaveConfirmModal = true
      }
    },
    chooseNewDesign() {
      this.showNewDesignModal = 1
    },
    chooseLogo() {
      this.showNewDesignModal = 2
    },
    dashboard() {
      if (this.saved) {
        window.location.href = '/account/graphics'
      } else {
        this.viewDashboard = true
        this.openSaveConfirmModal = true
      }
    },
    logout() {
      document.getElementById('logout-form').submit()
    }
  }
}
</script>

<style lang="scss" scoped>
header {
  width: 100%;
  position: fixed;
  top: 0;
  display: flex;
  height: 70px;
  background-color: #4d8ac9;
  align-items: center;
  z-index: 40;
  box-shadow: 0 3px 6px rgba(0, 0, 0, 0.16), 0 3px 6px rgba(0, 0, 0, 0.23);

  @media only screen and (max-width: 1198px) {
    display: none;
  }
}

a {
  text-decoration: none;
}

.logo {
  width: 280px;
  height: 80px;
  display: flex;
  border-right: 1px solid rgba(255, 255, 255, 0.1);

  a:first-child {
    margin: auto 10px;

    svg path {
      fill-opacity: 0.5;
      transition: all 0.5s ease;
    }

    &:hover svg path {
      fill-opacity: 1;
    }
  }

  img {
    cursor: pointer;
  }
}

.create-account-button,
.quick-view-button {
  display: inline-flex;
  justify-content: center;
  border: 2px solid #3a58f9;
  background-color: #fff;
  box-sizing: border-box;
  box-shadow: 0 4px 14px rgb(58 88 249 / 30%);
  border-radius: 6px;
  font-family: 'Poppins', sans-serif;
  font-style: normal;
  font-weight: 600;
  padding: 10px 15px;
  font-size: 14px;
  line-height: 14px;
  text-align: center;
  cursor: pointer;
  color: #3a58f9;
  text-decoration: none;
  transition: all 0.2s ease;

  &:hover {
    background: #2743de;
    box-shadow: 0 2px 4px rgb(58 88 249 / 50%);
    color: #fff;
    border-color: rgba(255, 255, 255, 0.2);
  }
}

.steps {
  display: flex;
  align-items: center;
  justify-content: space-between;
  flex-grow: 3;
  position: relative;
  margin: 0 auto;
  max-width: 720px;

  &:before {
    content: '';
    position: absolute;
    width: 100%;
    height: 1px;
    background-color: #4d68fa;
  }
}

.step-button,
.login-button {
  text-transform: capitalize;
  border: 1px solid #4d68fa;
  border-radius: 3px;
  background-color: #3a58f9;
  padding: 8px 15px;
  font-family: 'Poppins', sans-serif;
  font-style: normal;
  font-weight: normal;
  line-height: normal;
  font-size: 14px;
  color: #fff;
  display: flex;
  cursor: pointer;
  z-index: 1;
  align-items: center;
  margin-right: 10px;

  &:last-child {
    margin-right: 0;
  }

  &.disabled {
    background-color: #dbdbdb;
  }

  &:hover:not(.disabled) {
    background-color: #fff;
    color: #3a58f9;

    svg path {
      fill: #24d977;
    }
  }
}

.current {
  background-color: #fff;
  color: #3a58f9;
}

a svg {
  margin-right: 10px;

  path {
    fill: rgba(255, 255, 255, 0.3);
  }
}

.success svg path {
  fill: #24d977;
}

.current svg path {
  fill: rgba(41, 158, 231, 0.2);
}

.nav-menu {
  display: flex;

  .submenu {
    top: 65px;
    right: -18px;

    .submenu {
      right: calc(100% + 10px);
      top: 0;
    }
  }
}

@media only screen and (max-width: 1200px) {
  .steps {
    margin: auto 10%;
  }
}

@media only screen and (max-width: 920px) {
  .steps {
    margin: auto 5%;
  }
}

@media only screen and (max-width: 720px) {
  .logo {
    border-right: none;
  }
  .steps {
    display: none;
  }
}

.login-register-buttons {
  display: flex;
  flex-wrap: nowrap;
  align-items: center;
}
</style>
