<script>
import { defineComponent } from 'vue'
import BzContainer from '@/section-builder/components/section/BzContainer.vue'
import BzButton from '@/section-builder/components/section/BzButton.vue'
import BzBackground from '@/section-builder/components/section/BzBackground.vue'
import BzAlignment from '@/section-builder/components/section/BzAlignment.vue'
import BzAspectView from '@/section-builder/components/section/BzAspectView.vue'

import appointmentMixin from './appointmentMixin'

export default defineComponent({
  name: 'Appointment1',
  components: {
    BzAlignment,
    BzAspectView,
    BzBackground,
    BzButton,
    BzContainer
  },
  mixins: [appointmentMixin]
})
</script>

<template>
  <div class="bz-section-container bz-sec--usp-1-root" :class="{ [breakPoint]: true }">
    <bz-background :setting="background" :size="sectionSize">
      <bz-container>
        <bz-alignment :alignment="setting.layouts.alignment">
          <bz-title v-if="setting.elements.title" v-model="data.elements.title" />
          <bz-subtitle v-if="setting.elements.subtitle" v-model="data.elements.subtitle" />
          <bz-text v-if="setting.elements.description" v-model="data.elements.description" />
          <div class="tw-mt-4 tw-flex tw-gap-3 tw-justify-center">
            <button v-if="setting.elements.actionButton" @click="schedule" class="tw-bg-blue-500 rounded tw-text-white tw-px-3 tw-py-2 tw-shadow-md tw-cursor-pointer">
              <bz-text v-model="data.elements.actionButtonText" className="tw-cursor-pointer !tw-text-white" />
            </button>
            <button v-if="setting.elements.viewButton" @click="view" class="tw-bg-white rounded tw-text-white tw-px-3 tw-py-2 tw-shadow-md tw-cursor-pointer">
              <bz-text v-model="data.elements.viewButtonText" className="tw-cursor-pointer" />
            </button>
          </div>
        </bz-alignment>
      </bz-container>
    </bz-background>
  </div>
</template>

<style scoped lang="scss">
.btn-show-more {
  background-color: #0069d9;
  border: none;
  outline: none;
  color: white;
  border-radius: 4px;
  padding: 5px 10px;
  margin-top: 10px;
  position: absolute;
  left: calc(50% - 30px);
  z-index: 100;
  cursor: pointer;
  &:hover {
    background-color: #014fa5;
  }
}
</style>
