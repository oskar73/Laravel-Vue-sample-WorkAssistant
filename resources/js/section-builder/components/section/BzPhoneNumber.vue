<template>
  <div class="bz-el--business-phone-number-root" :style="{ fontFamily: theme.paragraphFont, color: color }" :class="{ edit }">
    <div class="text-phone-number">{{ business.contact.phoneNumber }}</div>
    <div class="business-phone-number-setting">
      <div class="icon" @click="openSettingSlider(1, 'tab-contact')">
        <setting-icon fill-color="#808080" />
      </div>
    </div>
  </div>
</template>

<script>
import SettingIcon from '../icons/Setting.vue'
import elementMixin from '../../mixins/elementMixin'
export default {
  name: 'BzPhoneNumber',
  components: { SettingIcon },
  mixins: [elementMixin],
  props: {
    location: {
      type: String,
      default: ''
    }
  },
  computed: {
    business() {
      let selectedBusiness = this.templateSetting.businesses.find((business) => business.companyName === this.location)

      if (typeof selectedBusiness === 'undefined') {
        selectedBusiness = this.templateSetting.businesses[0]
      }

      return selectedBusiness
    }
  }
}
</script>
<style lang="scss" scoped>
.bz-el--business-phone-number-root {
  position: relative;
  border: solid 2px transparent;

  .text-phone-number {
    text-decoration: underline;
    height: 2rem;
    display: flex;
    align-items: center;
  }

  .business-phone-number-setting {
    display: none;
    position: absolute;
    width: 100%;
    height: 100%;
    top: 0;
    left: 0;
    justify-content: center;
    align-items: center;

    .icon {
      box-shadow: 0 0 10px 5px #00000012;
      background-color: white;
      padding: 6px;
      border-radius: 4px;
      cursor: pointer;
    }
  }

  &.edit {
    &:hover {
      border: solid 2px var(--bz-section-edit-active-color);

      .business-phone-number-setting {
        display: flex;
      }
    }
  }
}
</style>
