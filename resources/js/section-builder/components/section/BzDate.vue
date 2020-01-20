<template>
  <div class="bz-el--date-root" :class="{ edit }">
    <div class="bz-text-date">
      <template v-if="variant === 1">
        {{ date }}
      </template>
      <template v-if="variant === 2">
        <div class="bz-ee-date">{{ _date }}</div>
        <div class="bz-ee-month">{{ _month }}</div>
        <div class="bz-ee-year">{{ _year }}</div>
      </template>
    </div>
    <div v-if="edit" class="bz-date-editor">
      <div class="setting-icon">
        <setting-icon :fill-color="'#808080'" :size="20" />
      </div>
    </div>
  </div>
</template>

<script>
import elementMixin from '../../mixins/elementMixin'
import SettingIcon from '../icons/Setting.vue'
import moment from 'moment'

export default {
  components: { SettingIcon },
  mixins: [elementMixin],
  props: {
    format: {
      type: String,
      default: 'LL'
    },
    variant: {
      type: Number,
      default: 1
    }
  },
  computed: {
    date: {
      get() {
        if (this.data) {
          return moment(new Date(this.data)).format(this.format)
        }
        return moment().format(this.format)
      },
      set(val) {
        this.data = moment(new Date(val)).format(this.format)
      }
    },
    _year: {
      get() {
        if (this.date) {
          return new Date(this.date).getFullYear()
        }
        return new Date().getFullYear()
      },
      set(val) {}
    },
    _month: {
      get() {
        if (this.date) {
          return moment(new Date(this.date)).format('MMMM')
        }
        return new Date().getMonth()
      },
      set(val) {}
    },
    _date: {
      get() {
        if (this.date) {
          return new Date(this.date).getDate()
        }
        return new Date().getDate()
      },
      set(val) {}
    }
  }
}
</script>
<style lang="scss">
.bz-el--date-root {
  border: solid 2px transparent;
  color: inherit;
  position: relative;

  .bz-text-date {
    color: inherit;

    .bz-ee-date {
      font-size: 49px;
      margin-bottom: 6px;
      font-weight: bold;
      font-family: Lora;
    }
  }

  .bz-date-editor {
    position: absolute;
    width: 100%;
    height: 100%;
    display: none;
    justify-content: center;
    align-items: center;
    top: 0;
    left: 0;

    .setting-icon {
      background-color: white;
      border-radius: 2px;
      padding: 3px;
      box-shadow: 0 0 10px 2px #00000034;
      cursor: pointer;
    }
  }

  &.edit {
    &:hover {
      border: solid 2px var(--bz-section-edit-active-color);

      .bz-date-editor {
        display: flex;
      }
    }
  }
}
</style>
