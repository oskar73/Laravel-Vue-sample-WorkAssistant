<template>
  <div class="w-100 px-3 pb-3">
    <div v-if="setting.hasOwnProperty('alignment')" class="w-100 mt-3">
      <h6>Alignment</h6>
      <div class="alignment-wrapper">
        <div class="align-left tw-flex tw-items-center" :class="{ active: setting.alignment === 'left' }" @click="setting.alignment = 'left'">
          <align-left-icon />
        </div>
        <div
          v-if="(setting.alignments || []).includes('alternate')"
          class="align-alternate"
          :class="{ active: setting.alignment === 'alternate' }"
          @click="setting.alignment = 'alternate'"
        >
          <align-alternate-icon />
        </div>
        <div class="align-right" :class="{ active: setting.alignment === 'right' }" @click="setting.alignment = 'right'">
          <align-right-icon />
        </div>
      </div>
    </div>

    <div v-if="setting.hasOwnProperty('columns')" class="w-100 mt-3">
      <h6>Columns</h6>
      <div class="column-wrapper">
        <div v-for="column of setting.columns" :key="column" class="column-item" :class="{ active: setting.column === column }" @click="handleColumnChange(column)">
          {{ column }}
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import SettingBase from './SettingBase.vue'
import AlignLeftIcon from '../icons/AlignLeft.vue'
import AlignAlternateIcon from '../icons/AlignAlternate.vue'
import AlignRightIcon from '../icons/AlignRight.vue'
import eventBus from '@/public/eventBus'

export default {
  name: 'SettingAlignment',
  components: {
    AlignRightIcon,
    AlignAlternateIcon,
    AlignLeftIcon
  },
  extends: SettingBase,
  methods: {
    handleColumnChange (column) {
      this.setting.column = column
    }
  }
}
</script>
