<script>
import { defineComponent } from 'vue'
import Modal from '@/public/Modal.vue'
import appMixin from '@/svg-editor/mixins/app-mixin'

export default defineComponent({
  name: 'SaveDesignModal',
  components: { Modal },
  mixins: [appMixin],
  props: {
    open: {
      type: Boolean,
      default: false
    }
  },
  data() {
    return {
      new_version: '',
      version_type: 'override',
      error: ''
    }
  },
  watch: {
    version_type() {
      this.error = ''
    }
  },
  mounted() {
    if (this.original_version === 'first_version') {
      this.current_version = 'default'
    } else {
      this.current_version = this.original_version
    }
  },
  methods: {
    handleSave() {
      if (this.version_type === 'override') {
        if (!this.designData.version) {
          this.error = 'The name of current version is required.'
          return
        }
        this.$emit('save', {
          version_type: 'override',
          version_name: this.designData.version
        })
      } else if (this.version_type === 'create') {
        if (!this.new_version) {
          this.error = 'The name of new version is required.'
          return
        }
        this.$emit('save', {
          version_type: 'create',
          version_name: this.new_version
        })
      }
    }
  }
})
</script>

<template>
  <modal :show="open">
    <div class="tw-bg-white tw-rounded tw-py-2 tw-px-4">
      <div class="tw-flex tw-justify-end">
        <span class="tw-cursor-pointer" @click="$emit('close')">
          <i class="mdi mdi-close tw-text-xl"></i>
        </span>
      </div>
      <div class="tw-w-80 tw-flex tw-flex-col tw-items-start tw-pb-5 tw-px-3">
        <div v-if="error" class="tw-text-red-500" v-text="error"></div>
        <div class="form-check tw-mt-3 tw-mb-1">
          <input id="version1" v-model="version_type" class="form-check-input" type="radio" value="override" />
          <label class="form-check-label h-cursor" for="version1"> Override Current Version </label>
        </div>
        <input v-model="designData.version" type="text" class="form-control" />
        <div class="form-check tw-mt-3 tw-mb-1">
          <input id="version2" v-model="version_type" class="form-check-input" type="radio" value="create" />
          <label class="form-check-label h-cursor" for="version2"> Create New Version </label>
        </div>
        <input v-model="new_version" type="text" class="form-control" placeholder="Name of new version" />
        <button class="button button-preview tw-mt-3" @click="handleSave">
          <i class="mdi mdi-content-save"></i>
          Save
        </button>
      </div>
    </div>
  </modal>
</template>
