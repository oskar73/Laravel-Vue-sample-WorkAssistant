<template>
  <modal :show="true" @closed="onClose()">
    <div class="update-theme-modal tw-bg-white" name="update-theme-modal">
      <div class="d-flex p-3 justify-content-between tw-bg-gray-100 tw-border-b">
        <h5 class="tw-text-xl">Update Theme</h5>
        <div class="cursor-pointer" @click.prevent="onClose()">
          <i class="mdi mdi-close"></i>
        </div>
      </div>
      <div class="tw-w-full tw-p-4">
        <p class="tw-text-left tw-py-3">what would you like to name this theme?</p>
        <bz-input v-model="themeName" label="Theme Name" :height="45" :required="true" />

        <div class="tw-mt-2">
          <bz-select v-model="categoryId" label="Theme Category" :options="themeCategories" />
        </div>
      </div>
      <hr />
      <div class="w-100 d-flex justify-content-end tw-py-2">
        <button class="btn btn-default mr-4 d-flex align-items-center" @click="onClose">
          <b>Cancel</b>
        </button>
        <button class="btn bz-btn-default mr-4 d-flex align-items-center" @click="updateTheme">
          <spinner v-if="loading" />
          Update Theme
        </button>
      </div>
    </div>
  </modal>
</template>

<script>
import BzInput from '../page/BzInput.vue'
import modalMixin from '../../mixins/modalMixin'
import BzSelect from '../page/BzSelect.vue'
import builderMixin from '../../mixins/builderMixin'
import Modal from '../../../public/Modal.vue'
import { toast } from 'vue3-toastify'
import 'vue3-toastify/dist/index.css'
import Spinner from '@/public/Spinner.vue'

export default {
  name: 'UpdateThemeModal',
  components: { Spinner, BzSelect, BzInput, Modal },
  mixins: [modalMixin, builderMixin],
  data() {
    return {
      themeName: '',
      categoryId: '',
      isUpdate: false,
      themeId: '',
      loading: false
    }
  },
  computed: {
    themeCategories() {
      if (this.isWebsite) {
        return this.$store.state.themeCategories
          .filter(({ user_id }) => user_id)
          .map((category) => ({
            label: category.name,
            value: category.id
          }))
      } else {
        return this.$store.state.themeCategories
          .filter(({ user_id }) => !user_id)
          .map((category) => ({
            label: category.name,
            value: category.id
          }))
      }
    }
  },
  mounted() {
    this.themeName = this.$store.state.modals.basic.value.themeName
    this.categoryId = this.$store.state.modals.basic.value.categoryId
    this.isUpdate = this.$store.state.modals.basic.value.isUpdate
    this.themeId = this.$store.state.modals.basic.value.themeId
    this.$modal.show('update-theme-modal')
  },
  methods: {
    updateTheme() {
      if (!this.themeName) {
        return toast.error('Theme name is required.')
      }

      // const isThemeNameTaken = this.$store.state.themes.some((t) => {
      //   if (this.isUpdate) {
      //     return t.id !== this.themeId && t.name === this.themeName
      //   } else {
      //     return t.name === this.themeName
      //   }
      // })
      //
      // if (isThemeNameTaken) {
      //   return toast.error('The theme name is taken. please choose another name.')
      // }
      this.loading = true
      this.onConfirm({
        themeName: this.themeName,
        categoryId: this.categoryId
      })
    }
  }
}
</script>
<style lang="scss">
.update-theme-modal {
  display: flex;
  flex-direction: column;
  top: 150px !important;
  width: 100%;
  max-width: 500px;
  position: fixed;
}
</style>
