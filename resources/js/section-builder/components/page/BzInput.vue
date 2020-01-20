<template>
  <div class="bz-input-root tw-w-full">
    <div class="tw-flex tw-flex-col tw-w-full tw-items-start" :class="{ [size]: true }">
      <label v-if="label">{{ label }} {{ required ? '*' : '' }}</label>
      <template v-if="multiple">
        <textarea v-model="data" :rows="rows" @focus="focused = true" @focusout="focused = false" class="tw-w-full tw-w-full tw-rounded tw-border tw-border-[#e5e7eb]"></textarea>
      </template>
      <template v-else>
        <div class="tw-flex tw-items-center tw-w-full tw-rounded tw-border">
          <input
            v-model="data"
            :class="`${prefix} ${inputClassName}`"
            :placeholder="prefix ? '' : placeholder || label"
            :disabled="disabled"
            class="tw-w-full tw-border-0 focus:tw-border-0 focus:tw-ring-0"
            @focus="focused = true"
            @focusout="focused = false"
            @blur="onBlur"
          />
        </div>
      </template>
    </div>
    <small v-show="required && label && !modelValue" style="text-transform: lowercase; color: #ff1744">{{ label }} is required</small>
  </div>
</template>

<script>
export default {
  name: 'BzInput',
  props: {
    inputClassName: {
      type: String,
      default: ''
    },
    onBlur: {
      type: Function,
      default: () => {}
    },
    modelValue: {
      type: [String, Number],
      default: ''
    },
    placeholder: {
      type: String,
      default: ''
    },
    label: {
      type: String,
      default: ''
    },
    multiple: {
      type: Boolean,
      default: false
    },
    rows: {
      type: Number,
      default: 10
    },
    height: {
      type: Number,
      default: 40
    },
    required: {
      type: Boolean,
      default: false
    },
    disabled: {
      type: Boolean,
      default: false
    },
    prefix: {
      type: String,
      default: ''
    },
    size: {
      type: String,
      default: 'normal'
    }
  },
  data() {
    return {
      focused: false
    }
  },
  computed: {
    data: {
      get() {
        return this.modelValue
      },
      set(value) {
        this.$emit('update:modelValue', value)
      }
    }
  }
}
</script>
