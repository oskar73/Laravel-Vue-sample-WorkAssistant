<template>
  <modal :classes="['youtube-video']" name="youtube-video" @closed="onClose()">
    <div style="background-color: rgb(246, 246, 246)" class="p-3">
      <h5>Youtube Video</h5>
    </div>
    <div class="p-4">
      <bz-input v-model="video.source" label="YouTube Video URL" :height="40" />
    </div>
    <hr />
    <div class="w-100 d-flex justify-content-end">
      <button class="btn bz-btn-default-outline mr-3" @click="onClose()">
        <b>Cancel</b>
      </button>
      <button class="btn bz-btn-default mr-4 d-flex align-items-center" @click="onConfirm()">
        <b>Save</b>
      </button>
    </div>
  </modal>
</template>

<script>
import { mapMutations } from 'vuex'
import BzInput from '../page/BzInput.vue'
import { toast } from 'vue3-toastify'
import 'vue3-toastify/dist/index.css'

export default {
  name: 'VideoModal',
  components: { BzInput },
  data() {
    return {
      loading: false,
      video: {
        source: '',
        autoplay: false,
        loop: false,
        mute: false,
        controls: true
      }
    }
  },
  mounted() {
    this.$modal.show('youtube-video')
  },
  methods: {
    getVideo() {
      return this.$store.state.modals.youtubeVideo.value
    },
    onConfirm() {
      if (this.video.source) {
        this.$store.state.modals.youtubeVideo.onChange(this.video)
        this.closeYoutubeVideo()
      } else {
        return toast.error('Youtube url is required.')
      }
    },
    onClose() {
      this.closeYoutubeVideo()
    },
    ...mapMutations(['closeYoutubeVideo'])
  }
}
</script>

<style lang="scss">
.youtube-video {
  max-width: 500px;
  height: min-content !important;
  padding-bottom: 15px;
}
</style>
