<template>
  <div class="m-accordion__item">
    <div class="m-accordion__item-head">
      <span class="m-accordion__item-title">Layout Settings</span>
    </div>
    <div class="tw-p-4 tw-space-y-2">
      <div v-if="setting.layouts.hasOwnProperty('fullWidth')" class="tw-flex tw-justify-between">
        <span class="element_item_label">Full Width</span>
        <label class="custom_switch">
          <input v-model="setting.layouts.fullWidth" type="checkbox" />
          <span />
        </label>
      </div>

      <div v-if="setting.layouts.hasOwnProperty('headerButton')" class="tw-flex tw-justify-between">
        <span class="element_item_label">Header Button</span>
        <label class="custom_switch">
          <input v-model="setting.layouts.headerButton" type="checkbox" />
          <span />
        </label>
      </div>

      <div v-if="setting.layouts.hasOwnProperty('carousel')" class="tw-flex tw-justify-between">
        <span class="element_item_label">Carousel</span>
        <label class="custom_switch">
          <input v-model="setting.layouts.carousel" type="checkbox" />
          <span />
        </label>
      </div>

      <div v-if="setting.layouts.hasOwnProperty('fullPage')" class="tw-flex tw-justify-between">
        <span class="element_item_label">Full Page</span>
        <label class="custom_switch">
          <input v-model="setting.layouts.fullPage" type="checkbox" />
          <span />
        </label>
      </div>

      <!-- <div v-if="setting.layouts.hasOwnProperty('animation')" class="tw-flex tw-justify-between tw-items-center">
        <span class="element_item_label">Animation</span>
        <div class="tw-w-1/2">
          <bz-select v-model="setting.layouts.animation" :options="['bouncy', 'smooth', 'overshoot']" />
        </div>
      </div> -->

      <div v-if="setting.layouts.hasOwnProperty('interval')" class="tw-flex tw-justify-between tw-items-center">
        <span class="element_item_label">Interval (seconds)</span>
        <div class="tw-w-1/3">
          <bz-select v-model="setting.layouts.interval" :options="[2, 3, 4, 5]" />
        </div>
      </div>

      <div v-if="setting.layouts.hasOwnProperty('fullSize')" class="tw-flex tw-justify-between">
        <span class="element_item_label">Full size</span>
        <label class="custom_switch">
          <input v-model="setting.layouts.fullSize" type="checkbox" />
          <span />
        </label>
      </div>

      <div v-if="setting.layouts.hasOwnProperty('sectionSize')" class="tw-flex tw-justify-between tw-items-center">
        <span class="element_item_label">Section size</span>
        <div class="tw-w-24">
          <bz-select v-model="setting.layouts.sectionSize" :options="['s', 'm', 'l', 'xl']" />
        </div>
      </div>

      <div v-if="setting.layouts.hasOwnProperty('shape')" class="tw-flex tw-justify-between tw-mb-2">
        <span class="element_item_label">Shape</span>
        <div class="tw-w-32">
          <template v-if="setting.layouts.hasOwnProperty('shapes') && Array.isArray(setting.layouts.shapes)">
            <bz-select v-model="setting.layouts.shape" :options="setting.layouts.shapes" />
          </template>
          <template v-else>
            <bz-select v-model="setting.layouts.shape" :options="['circle', 'square']" />
          </template>
        </div>
      </div>

      <div v-if="setting.layouts.hasOwnProperty('valueSize')" class="tw-flex tw-justify-between tw-mb-2">
        <div class="row">
          <div class="col-6 d-flex align-items-center">
            <span class="element_item_label">Value Size</span>
          </div>
          <div class="col-6">
            <bz-select v-model="setting.layouts.valueSize" :options="['regular', 'medium', 'large']" />
          </div>
        </div>
      </div>

      <div v-if="setting.layouts.hasOwnProperty('shadow')" class="tw-flex tw-justify-between tw-mb-2">
        <span class="element_item_label">Shadow</span>
        <label class="custom_switch">
          <input v-model="setting.layouts.shadow" type="checkbox" />
          <span />
        </label>
      </div>

      <div v-if="setting.layouts.hasOwnProperty('spacing')" class="tw-flex tw-justify-between tw-mb-2">
        <span class="element_item_label" @dblclick="delete setting.layouts.spacing">Spacing</span>
        <label class="custom_switch">
          <input v-model="setting.layouts.spacing" type="checkbox" />
          <span />
        </label>
      </div>

      <div v-if="setting.layouts.hasOwnProperty('alignment')" class="tw-flex tw-justify-between tw-items-center tw-mb-2">
        <span class="element_item_label">Content Alignment</span>
        <div class="tw-w-32">
          <bz-select v-model="setting.layouts.alignment" :options="['left', 'center', 'right']" />
        </div>
      </div>

      <div v-if="setting.layouts.hasOwnProperty('aspectRatios')" class="tw-flex tw-justify-between tw-items-center tw-mb-2">
        <span>Aspect ratio</span>
        <div class="tw-w-32">
          <bz-select v-model="setting.layouts.aspectRatio" :options="setting.layouts.aspectRatios" />
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import SettingBase from './SettingBase.vue'
import BzSelect from './BzSelect.vue'
export default {
  name: 'SettingLayouts',
  components: { BzSelect },
  extends: SettingBase
}
</script>
