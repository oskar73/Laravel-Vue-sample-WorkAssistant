<template>
  <modal :classes="['preset-theme-name-modal']" name="preset-theme-name-modal" @closed="onClose()">
    <div style="background-color: rgb(246, 246, 246)" class="d-flex p-3 justify-content-between">
      <h5>Add Theme.</h5>
      <div class="cursor-pointer" @click.prevent="onClose()">
        <i class="mdi mdi-close"></i>
      </div>
    </div>
    <div class="w-100 h-100">
      <div class="col-12 h-100">
        <p>what would you like to name this theme?</p>
        <div class="w-100 h-100 d-flex justify-content-center align-items-center">
          <bz-input v-model="themeName" label="Theme Name" :height="45" :required="true" />
        </div>
      </div>
    </div>
    <hr style="margin-top: auto" />
    <div class="w-100 d-flex justify-content-end pb-2">
      <button class="btn bz-btn-default mr-4 d-flex align-items-center" @click="onOverride">
        <b>Override</b>
      </button>
      <button class="btn bz-btn-default mr-4 d-flex align-items-center" @click="onConfirm">
        <b>Add Theme</b>
      </button>
    </div>
  </modal>
</template>

<script>
import BzInput from '../page/BzInput.vue'
import { toast } from 'vue3-toastify'
import 'vue3-toastify/dist/index.css'

export default {
  name: 'PresetThemeNameModal',
  components: { BzInput },
  data() {
    return {
      themeName: ''
    }
  },
  mounted() {
    this.$modal.show('preset-theme-name-modal')
    this.themeName = this.$store.state.modals.themeName.value.themeName
  },
  methods: {
    onClose() {
      this.$store.state.modals.themeName.open = false
    },
    onConfirm() {
      if (!this.themeName) {
        toast.error('Enter the theme name!')
        return
      }
      this.onClose()
      this.$store.state.modals.themeName.onConfirm({
        override: false,
        name: this.themeName
      })
    },
    onOverride() {
      if (!this.themeName) {
        toast.error('Enter the theme name!')
        return
      }
      this.onClose()
      this.$store.state.modals.themeName.onConfirm({
        override: true,
        name: this.themeName
      })
    }
  }
}
</script>
<style lang="scss">
.preset-theme-name-modal {
  display: flex;
  flex-direction: column;
  top: 150px !important;
  position: fixed;
}
</style>
