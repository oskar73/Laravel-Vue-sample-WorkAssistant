<template>
  <div class="bz-el--count-down-root" :style="{ fontFamily: theme.paragraphFont }">
    <slot />
    <div v-if="edit" class="count-down-editor" :class="{ openDateTimePicker }">
      <bz-datetime-picker v-model="countTime" :on-cancel="onCancel">
        <div class="icon" @click="openDateTimePicker = true">
          <setting-icon fill-color="#808080" />
        </div>
      </bz-datetime-picker>
    </div>
  </div>
</template>

<script>
import elementMixin from '../../mixins/elementMixin'
import SettingIcon from '../icons/Setting.vue'
import BzDatetimePicker from './BzDateTimePicker.vue'
import moment from 'moment'

export default {
  components: { BzDatetimePicker, SettingIcon },
  mixins: [elementMixin],
  data() {
    return {
      openDateTimePicker: false
    }
  },
  computed: {
    countTime: {
      get() {
        return this.data || moment().format('YYYY-MM-DD H:m:s')
      },
      set(val) {
        this.data = val
      }
    }
  },
  methods: {
    onCancel() {
      this.openDateTimePicker = false
    }
  }
}
</script>
<style lang="scss">
.bz-el--count-down-root {
  width: 100%;
  position: relative;

  .count-down-editor {
    position: absolute;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    display: flex;
    justify-content: center;
    align-items: center;
    border: solid 2px transparent;

    &.openDateTimePicker,
    &:hover {
      border: solid 2px var(--bz-section-edit-active-color);
    }

    .icon {
      background-color: white;
      box-shadow: 0 0 10px 2px #00000012;
      border-radius: 0.2em;
      padding: 0.3em;
      display: none;
      cursor: pointer;
    }

    .bz-date-time-picker {
      position: absolute;
      top: calc(100% + 20px);
      left: calc(50% - 250px);
      width: 300px;
      height: auto;
      display: flex;
      justify-content: center;
      align-items: center;
    }

    &.openDateTimePicker,
    &:hover {
      .icon {
        display: block;
      }
    }
  }
}
</style>
