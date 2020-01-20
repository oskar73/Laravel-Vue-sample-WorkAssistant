<template>
  <div class="bz-el--form-group-root" :class="{ stretch, 'bz-d-lg-flex': inline }">
    <div :class="{ 'bz-w-lg-30': inline }">
      <label v-if="label" class="bz-form--label" :style="{ color: color, fontFamily: theme.paragraphFont }">
        {{ label }}
      </label>
    </div>

    <div :class="{ 'bz-w-lg-70': inline }">
      <input v-if="type === 'text'" v-model="data" class="bz-form-item bz-form--input" :style="{ borderColor: color, color: color, fontFamily: theme.paragraphFont }" />
      <textarea
        v-if="type === 'textarea'"
        v-model="data"
        class="bz-form-item bz-form--textarea"
        :style="{ borderColor: color, color: color, fontFamily: theme.paragraphFont }"
        :rows="rows"
        :class="{ stretch }"
      ></textarea>
      <datepicker
        v-if="type === 'date'"
        v-model="data"
        :style="{ borderColor: color, fontFamily: theme.paragraphFont, backgroundColor: backgroundColor, color: color }"
        class="bz-form-item bz-form--date-picker"
        placeholder="placeholder"
        :format="format"
      />
    </div>
  </div>
</template>

<script>
import elementMixin from '../../mixins/elementMixin'
import Datepicker from 'vue3-datepicker'

export default {
  name: 'BzFormGroup',
  components: {
    Datepicker
  },
  mixins: [elementMixin],
  props: {
    label: {
      type: String,
      default: ''
    },
    type: {
      type: String,
      default: 'text'
    },
    inline: {
      type: Boolean,
      default: false
    },
    rows: {
      type: Number,
      default: 5
    },
    stretch: {
      type: Boolean,
      default: false
    },
    placeholder: {
      type: String,
      default: ''
    },
    format: {
      type: String,
      default: 'MM/dd/yyyy'
    }
  },
  computed: {
    date: {
      get() {
        return this.data || new Date()
      },
      set(val) {
        this.data = val
      }
    }
  }
}
</script>
<style lang="scss">
.bz-el--form-group-root {
  width: 100%;
  margin: 0.5rem 0;

  &.stretch {
    display: flex;
    flex-flow: column;
    flex-direction: column;
    height: 100%;
  }

  .bz-form--label {
    width: 100%;
    margin-bottom: 0.25rem;
    display: block;
    padding-right: 1em;
  }

  .bz-form-item {
    padding: 0.5rem;
    border-radius: 0;
    border: solid 1px;
    height: auto;
    width: 100%;
    background-color: inherit;

    &.stretch {
      height: 100%;
    }
  }

  .vdp-datepicker {
    input {
      color: inherit;
    }

    .vdp-datepicker__calendar {
      background: inherit !important;
      background-color: inherit !important;
    }
  }
}
</style>
