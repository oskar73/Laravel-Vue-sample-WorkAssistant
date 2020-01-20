<script>
import { defineComponent } from 'vue'
import { Listbox, ListboxButton, ListboxLabel, ListboxOption, ListboxOptions } from '@headlessui/vue'

export default defineComponent({
  name: 'BzSelect',
  components: { ListboxOption, ListboxOptions, ListboxButton, ListboxLabel, Listbox },
  props: {
    label: {
      type: [String, null],
      default: null
    },
    modelValue: {
      required: true
    },
    options: {
      type: Array,
      required: true
    },
    autoSelect: {
      type: Boolean,
      default: false
    },
    disabled: {
      type: Boolean,
      default: false
    },
    inline: {
      type: Boolean,
      default: false
    },
    search: {
      type: [Array, String, Boolean],
      default: false
    },
    placeholder: {
      type: String,
      default: 'Select'
    }
  },
  data() {
    return {
      keyWord: ''
    }
  },
  computed: {
    value: {
      get() {
        return this.modelValue
      },
      set(val) {
        this.$emit('update:modelValue', val)
        this.$emit('input', val)
      }
    },
    filteredOptions() {
      if (this.search && this.keyWord) {
        if (this.search === true) {
          return this.options.filter((item) => JSON.stringify(item).includes(this.keyWord))
        } else if (typeof this.search === 'string') {
          return this.options.filter((item) => item[this.search]?.includes(this.keyWord))
        } else if (typeof this.search === 'object') {
          return this.options.filter((item) => {
            let filtered = false
            for (const key of this.search) {
              filtered ||= item[key]?.includes(this.keyWord)
            }
            return filtered
          })
        }
      }

      return this.options
    }
  },
  mounted() {
    if (this.autoSelect && !this.modelValue) {
      this.$emit('update:modelValue', this.options[0])
    }
  }
})
</script>

<template>
  <Listbox as="div" v-model="value" :disabled="disabled" :class="{ 'tw-flex tw-items-center': inline }">
    <slot v-if="label" name="label" :selected="value" :label="label">
      <ListboxLabel class="tw-block tw-text-sm tw-leading-6 tw-text-gray-900" :class="[inline ? 'tw-mr-2' : 'tw-mb-1']">
        {{ label }}
      </ListboxLabel>
    </slot>
    <div class="tw-relative tw-cursor-pointer">
      <ListboxButton
        class="tw-relative tw-w-full tw-cursor-default tw-rounded tw-bg-white tw-py-1.5 tw-pl-3 tw-pr-10 tw-text-left tw-text-gray-900 tw-shadow-sm tw-ring-1 tw-ring-inset tw-ring-gray-300 focus:tw-outline-none focus:tw-ring-2 focus:tw-ring-indigo-500 sm:tw-text-sm sm:tw-leading-6"
        :class="{ 'tw-text-gray-300': disabled }"
      >
        <span class="tw-flex tw-items-center">
          <slot name="selected" :selected="value" :placeholder="placeholder" />
        </span>
        <span class="tw-pointer-events-none tw-absolute tw-inset-y-0 tw-right-0 tw-ml-3 tw-flex tw-items-center tw-pr-2">
          <i class="mdi mdi-unfold-more-horizontal"></i>
        </span>
      </ListboxButton>
      <transition leave-active-class="tw-transition tw-ease-in tw-duration-100" leave-from-class="tw-opacity-100" leave-to-class="tw-opacity-0">
        <ListboxOptions
          class="tw-absolute tw-z-10 tw-mt-1 tw-max-h-56 tw-w-full tw-overflow-auto tw-rounded tw-bg-white tw-text-base tw-shadow tw-ring-1 tw-ring-black tw-ring-opacity-5 focus:tw-outline-none sm:tw-text-sm"
        >
          <ListboxOption as="template" v-if="search && options.length > 0" :disabled="true">
            <div class="tw-sticky tw-bg-white tw-select-none tw-py-2 tw-px-2 tw-top-0 tw-z-50">
              <div class="tw-border tw-rounded tw-bg-white">
                <input v-model="keyWord" class="tw-w-full focus:tw-outline-0 tw-h-8 tw-rounded tw-border-0 tw-px-1 tw-text-sm focus:tw-ring-0" placeholder="Search" />
                <span class="tw-text-indigo-600 tw-absolute tw-cursor-pointer tw-inset-y-0 tw-right-0 tw-flex tw-items-center tw-pr-3" @click="keyWord = ''">
                  <i class="mdi mdi-close"></i>
                </span>
              </div>
            </div>
          </ListboxOption>
          <ListboxOption as="template" v-for="(option, index) in filteredOptions" :key="index" :value="option" v-slot="{ active, selected }">
            <li :class="[active ? 'tw-bg-blue-500 tw-text-white' : 'tw-text-gray-900', 'tw-relative tw-cursor-default tw-select-none tw-py-2 tw-pl-3 tw-pr-9']">
              <div class="tw-flex tw-items-center">
                <slot name="option" :option="option" />
              </div>
              <span v-if="selected" :class="[active ? 'tw-text-white' : 'tw-text-indigo-600', 'tw-absolute tw-inset-y-0 tw-right-0 tw-flex tw-items-center tw-pr-4']">
                <i class="mdi mdi-check"></i>
              </span>
            </li>
          </ListboxOption>
        </ListboxOptions>
      </transition>
    </div>
  </Listbox>
</template>

<style scoped lang="scss">
</style>