<template>
  <div class="m-accordion__item">
    <div class="m-accordion__item-head" data-area="#setting_accordion_item_7_body">
      <span class="m-accordion__item-title">Card</span>
      <span class="m-accordion__item-mode" />
    </div>
    <div id="setting_accordion_item_7_body" class="m-accordion__item-body collapse show">
      <div v-if="setting.iframe.hasOwnProperty('url')" class="mb-2" @click.prevent.stop="openEmbedLinkModal">
        <label>URL</label>
        <Input v-model="setting.iframe.url" :height="40" />
      </div>
    </div>
  </div>
</template>

<script>
import SettingBase from './SettingBase.vue'
export default {
  name: 'SettingIframe',
  extends: SettingBase,
  methods: {
    openEmbedLinkModal() {
      this.$store.commit('openEmbedLink', {
        value: this.setting.iframe.url,
        onChange: (value) => {
          this.setting.iframe.url = value
        }
      })
    }
  }
}
</script>
