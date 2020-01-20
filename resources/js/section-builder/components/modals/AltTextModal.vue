<template>
  <modal :classes="['delete-my-images']" name="altText" @closed="onClose">
    <div style="background-color: rgb(246, 246, 246)" class="p-3">
      <h5>Alt Text</h5>
    </div>
    <div class="p-4">
      <md-field>
        <label>Alt text</label>
        <md-textarea v-model="data.altText"></md-textarea>
      </md-field>
    </div>
    <p class="px-4" style="font-size: 14px">Alt text improves your search engine optimization(SEO) and describes your image for visitors with visual impairments.</p>
    <hr />
    <div class="w-100 d-flex justify-content-end">
      <button class="btn bz-btn-default-outline mr-3" @click="onClose">
        <b>Cancel</b>
      </button>
      <button class="btn btn-danger mr-4 d-flex align-items-center" @click="onConfirm(data)">
        <b>Apply</b>
      </button>
    </div>
  </modal>
</template>

<script>
import modalMixin from '../../mixins/modalMixin'

export default {
  mixins: [modalMixin],
  data() {
    return {
      loading: false
    }
  },
  computed: {
    position() {
      return this.$store.state.altText.position
    },
    path() {
      return this.$store.state.altText.path
    }
  }
}
</script>

<style lang="scss">
.delete-my-images {
  max-width: 450px;
  height: min-content !important;
  padding-bottom: 15px;
}
</style>
