<template>
  <modal :classes="['manage-markers']" :width="modalWidth" name="manage-markers" @closed="onClose()">
    <div class="modal-body">
      <div class="marker-list"></div>
      <div class="add-marker-panel">
        <div class="add-marker-header">
          <div class="w-100 p-2 d-flex justify-content-end">
            <div class="d-flex align-items-center mr-3">
              <button class="btn bz-btn-default mr-2" disabled>
                <duplicate-icon :size="20" />
                <span>Duplicate</span>
              </button>
              <button class="btn bz-btn-danger" disabled>
                <i class="mdi mdi-delete"></i>
                <span>Delete</span>
              </button>
            </div>
            <i class="mdi mdi-close"></i>
          </div>
        </div>
        <div class="add-marker-content">
          <bz-input :height="40" label="Title*" class="my-4" />

          <bz-input :height="40" label="Description" class="my-4" />

          <bz-select :min-height="40" variant="#ffffff" label="Marker type" class="my-4" :options="[1, 2, 3, 4, 5, 6, 7]" />
        </div>
      </div>
    </div>
    <hr style="margin-top: 0" />
    <div class="w-100 d-flex justify-content-between">
      <div>
        <button class="btn bz-btn-default ml-3 px-5 d-flex align-items-center" @click="onConfirm()">
          <b>Add marker</b>
        </button>
      </div>
      <div class="d-flex">
        <button class="btn bz-btn-default-outline mr-3" @click="onClose()">
          <b>Cancel</b>
        </button>
        <button class="btn bz-btn-default mr-3 d-flex align-items-center" @click="onConfirm()">
          <b>Save</b>
        </button>
      </div>
    </div>
  </modal>
</template>

<script>
import { mapMutations } from 'vuex'
import DuplicateIcon from '../icons/Duplicate.vue'
import BzInput from '../page/BzInput.vue'
import BzSelect from '../page/BzSelect.vue'

export default {
  name: 'ManageMarkersModal',
  components: { BzSelect, BzInput, DuplicateIcon },
  data() {
    return {
      loading: false,
      markers: [],
      modalWidth: '60%'
    }
  },
  methods: {
    getMarkers() {
      return this.$store.state.modals.manageMarkers.value
    },
    onConfirm() {
      this.$store.state.manageMarkers.onChange(this.markers)
      this.closeManageMarkers()
    },
    onClose() {
      this.closeManageMarkers()
    },
    ...mapMutations(['closeManageMarkers'])
  },
  mounted() {
    this.markers = this.getMarkers()

    const windowWidth = $(window).width()

    if (windowWidth <= 768) {
      this.modalWidth = '100%'
    } else if (windowWidth <= 1024) {
      this.modalWidth = '80%'
    } else {
      this.modalWidth = '60%'
    }

    this.$modal.show('manage-markers')
  }
}
</script>

<style lang="scss">
.manage-markers {
  height: min-content !important;
  padding-bottom: 10px;

  @media screen and (max-width: 768px) {
    height: 100vh;
  }

  .modal-body {
    width: 100%;
    height: 100%;
    display: flex;
    padding: 0;

    .marker-list {
      width: 240px;
      background-color: rgb(246, 246, 246);
    }

    .add-marker-panel {
      width: calc(100% - 240px);
      .add-marker-header {
        background-color: rgb(246, 246, 246);
      }

      .add-marker-content {
        padding: 20px;
      }
    }
  }
}
</style>
