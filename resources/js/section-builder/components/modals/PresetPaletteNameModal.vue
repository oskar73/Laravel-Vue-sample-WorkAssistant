<template>
  <modal :show="open" @closed="onClose()">
    <div class="preset-palette-name-modal tw-bg-white" name="preset-palette-name-modal">
      <div style="background-color: rgb(246, 246, 246)" class="d-flex p-3 justify-content-between">
        <h5>Add Palette.</h5>
        <div class="cursor-pointer" @click.prevent="onClose()">
          <i class="mdi mdi-close"></i>
        </div>
      </div>
      <div class="w-100 p-3">
        <div>
          <p>What would you like to name this palette?</p>
          <div class="w-100 h-100 d-flex justify-content-center align-items-center">
            <bz-input v-model="paletteName" label="Palette Name" :height="45" :required="true" />
          </div>
        </div>
        <div class="mt-1">
          <bz-select v-model="categoryId" label="Category" :options="paletteCategories" />
        </div>
      </div>
      <hr style="margin-top: auto" />
      <div class="w-100 d-flex justify-content-end pb-2">
        <button class="btn bz-btn-default mr-4 d-flex align-items-center" @click="onConfirm">
          <b>Add Palette</b>
        </button>
      </div>
    </div>
  </modal>
</template>

<script>
import BzInput from '../page/BzInput.vue'
import builderMixin from '../../mixins/builderMixin'
import BzSelect from '../page/BzSelect.vue'
import Modal from '../../../public/Modal.vue'
import { toast } from 'vue3-toastify'
import 'vue3-toastify/dist/index.css'

export default {
  name: 'PresetPaletteNameModal',
  components: { BzSelect, BzInput, Modal },
  mixins: [builderMixin],
  props: {
    open: {
      type: Boolean,
      default: false
    }
  },
  data() {
    return {
      paletteName: '',
      categoryId: ''
    }
  },
  computed: {
    paletteCategories() {
      if (this.isWebsite) {
        return this.userPalettes.advanced.map((category) => ({
          label: category.name,
          value: category.category_id
        }))
      } else {
        return this.systemPalettes.advanced.map((category) => ({
          label: category.name,
          value: category.category_id
        }))
      }
    }
  },
  mounted() {
    this.$modal.show('preset-palette-name-modal')
  },
  methods: {
    onClose() {
      this.$store.state.modals.paletteName.open = false
    },
    onConfirm() {
      if (this.paletteName) {
        // Check palette name is taken.
        const isNameTaken = this.systemPalettes.advanced.some((sp) => {
          return [...(sp.palettes.dark || []), ...(sp.palettes.light || [])].some((_k) => {
            return _k.name === this.paletteName
          })
        })
        if (isNameTaken) {
          return toast.error('The palette name is taken. please choose another name.')
        } else {
          this.$store.state.modals.paletteName.onConfirm({
            name: this.paletteName,
            categoryId: this.categoryId
          })
          this.onClose()
        }
      }
    }
  }
}
</script>
<style lang="scss">
.preset-palette-name-modal {
  display: flex;
  flex-direction: column;
  position: fixed;
}
</style>
