<template>
  <div class="tw-w-full" :style="{ backgroundColor: variant }">
    <div v-if="label" class="tw-flex tw-w-full tw-mb-1">
      <label>{{ label }}</label>
    </div>
    <Multiselect
      id="dropdown"
      v-model="selectedValue"
      :options="options"
      label="label"
      track-by="value"
      value-prop="value"
      :close-on-select="true"
      :can-deselect="false"
      :can-clear="false"
      :placeholder="placeHolder"
      mode="single"
      class="multiselect-middle"
      open-direction="bottom"
      :searchable="false"
    >
    </Multiselect>
  </div>
</template>
<script>
import Multiselect from '@vueform/multiselect'
export default {
  name: 'BzSelect',
  components: { Multiselect },
  props: {
    options: {
      type: Array,
      default: () => {
        return []
      }
    },
    label: {
      default: null,
      type: String
    },
    labelKey: {
      default: 'label',
      type: String
    },
    itemKey: {
      default: 'value',
      type: String
    },
    preSelected: {
      type: Boolean,
      default: true
    },
    modelValue: {
      type: [String, Number, Object, Boolean],
      default: (value) => {
        if (typeof value === 'string') {
          return value
        }
        return null
      }
    },
    minHeight: {
      default: 30,
      type: Number
    },
    variant: {
      type: String,
      default: '#00000000'
    },
    matchWidth: {
      type: Boolean,
      default: true
    },
    placeHolder: {
      type: String,
      default: 'Select'
    },
    optionStyle: {
      type: [Object, Function],
      default: undefined
    }
  },
  computed: {
    selectedValue: {
      get() {
        return this.modelValue || ''
      },
      set(v) {
        this.$emit('update:modelValue', v)
      }
    }
  },
  methods: {
    itemText(option) {
      if (typeof option === 'object') {
        return option[this.labelKey]
      } else return option
    },
    itemValue(option) {
      if (!option) {
        return null
      }
      if (this.options.length && typeof this.options[0] === 'object') {
        return option[this.itemKey]
      } else {
        return option
      }
    }
  }
}
</script>
