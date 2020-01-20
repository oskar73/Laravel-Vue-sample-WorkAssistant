<template>
  <modal :classes="['embed-link']" name="embed-link" @closed="onClose()">
    <div style="background-color: rgb(246, 246, 246)" class="p-3">
      <h5>Embed link</h5>
    </div>
    <div class="p-4">
      <p style="font-size: 12px">Fill in the URL of the page you want to embed in your site.</p>
      <Input v-model="embedLink" />
    </div>
    <hr />
    <div class="w-100 d-flex justify-content-end">
      <button class="btn bz-btn-default-outline mr-3" @click="onClose()">
        <b>Cancel</b>
      </button>
      <button class="btn btn-danger mr-4 d-flex align-items-center" @click="onConfirm()">
        <b>Save</b>
      </button>
    </div>
  </modal>
</template>

<script>
import { mapMutations } from 'vuex'
import Input from '../page/BzInput.vue'

export default {
  components: { Input },
  data() {
    return {
      loading: false,
      embedLink: ''
    }
  },
  computed: {
    position() {
      return this.$store.state.altText.position
    },
    path() {
      return this.$store.state.altText.path
    }
  },
  mounted() {
    this.$modal.show('embed-link')
    this.embedLink = this.getEmbedLink()
  },
  methods: {
    getEmbedLink() {
      return this.$store.state.modals.embedLink.value
    },
    onConfirm() {
      this.$store.state.embedLink.onChange(this.embedLink)
      this.closeEmbedLink()
    },
    onClose() {
      this.closeEmbedLink()
    },
    ...mapMutations(['closeEmbedLink'])
  }
}
</script>

<style lang="scss">
.embed-link {
  max-width: 400px;
  height: min-content !important;
  padding-bottom: 15px;
}
</style>
