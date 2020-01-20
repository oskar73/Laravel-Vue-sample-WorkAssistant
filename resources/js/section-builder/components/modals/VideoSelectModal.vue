<template>
  <modal :classes="['select-video']" width="90%" name="selectVideo" @closed="onClose">
    <div class="bz-modal p-4">
      <span class="cursor-pointer position-absolute" style="right: 15px; top: 15px" @click="onClose()">
        <i class="mdi mdi-close"></i>
      </span>
      <div class="bz-modal-body">
        <h2 class="bz-text-black bz-fw-700">Upload your video</h2>
        <div class="w-100 py-3">
          <div class="input-group" style="height: 40px">
            <label for="video-upload" class="btn bz-btn-default mr-4" data-current-page="upload" data-back-page="unsplash">
              <bz-loading v-if="uploading" :size="18" fill="white" />
              <UploadIcon v-else />
              Upload Video
              <input id="video-upload" hidden type="file" accept="video/*" @change="handleFileChange" />
            </label>
            <input v-model="videoUrl" type="text" class="form-control h-100" name="search" placeholder="Enter video url" />
            <div class="input-group-append">
              <button class="btn bz-btn-default" type="button" @click="handleSave">Save</button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </modal>
</template>

<script>
import UploadIcon from '../icons/Upload.vue'
import axios from 'axios'
import modalMixin from '../../mixins/modalMixin'
import BzLoading from '../elements/BzLoading.vue'
import { toast } from 'vue3-toastify'
import 'vue3-toastify/dist/index.css'

export default {
  name: 'VideoSelectModal',
  components: {
    BzLoading,
    UploadIcon
  },
  mixins: [modalMixin],
  data() {
    return {
      modalName: 'selectVideo',
      disabledClick: true,
      uploading: false,
      videoUrl: '',
      tab: 'tune',
      loading: false,
      myVideos: []
    }
  },
  mounted() {
    this.getMyFiles()
  },
  methods: {
    async getMyFiles() {
      const self = this
      this.loading = true
      axios.get('/account/getStockFiles').then(async ({ data }) => {
        if (data.status) {
          self.loading = false
        }
      })
    },
    async handleFileChange(event) {
      const files = event.target.files
      if (files.length === 0) {
        return toast.error('Choose a video file.')
      }
      if (Number(files[0].size) > 5242880) {
        return toast.error('Video is too large.')
      }
      this.uploading = true
      const formData = new FormData()
      formData.append('video', files[0])
      axios.post('/account/uploadStockVideoFiles', formData).then((res) => {
        if (res.data.status) {
          this.videoUrl = res.data.data.url
          this.uploading = false
        }
      })
    },
    updateImage(item) {
      if (this.deleteItems.length === 0) {
        if (!this.uploading) {
          this.onConfirm(item)
        }
      } else {
        this.deleteItems.toggle(item.id)
      }
    },
    handleSave() {
      if (this.videoUrl) {
        const regex = /^.*(youtu.be\/|v\/|e\/|u\/\w+\/|embed\/|v=)([^#&?]*).*/
        const match = this.videoUrl.match(regex)
        const url = match ? match[2] : this.videoUrl
        this.onConfirm({ url, isYoutube: Boolean(match), isS3: url.includes('amazonaws.com') })
      } else {
        toast.error('Please paste a video url')
      }
    }
  }
}
</script>

<style lang="scss">
@import 'style';

$activeColor: #0076df;
.vm--modal.select-image {
  max-width: 1480px !important;
  align-self: center;
  height: 80vh !important;
  overflow: hidden;

  .justified-container {
    width: 100%;
    height: 100% !important;
    background-color: white;
    overflow-y: auto;
    overflow-x: hidden;

    .justified-item {
      height: 200px;

      img {
        width: 100%;
        height: 100%;
        object-fit: cover;
      }
    }
  }

  .bz-modal {
    .bz-modal-body {
      width: 100%;
      height: calc(100% - 60px);
      padding: 20px;
    }
  }
}

.bz-btn-default {
  background-color: #0076df !important;
  border: solid 1px #0076df !important;
  border-radius: 4px;
  color: white !important;
  cursor: pointer;

  &:hover {
    background-color: white;
    color: #0076df;
  }
}
</style>
