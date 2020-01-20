<template>
  <div class="bz-el--contact-form-root" :class="{ edit }">
    <form method="post" action="" autocomplete="off">
      <slot />
    </form>
    <div v-if="edit" class="contact-form-editor" @click="openContactFormSettingModal">
      <div class="icon">
        <setting-icon fill-color="#808080" />
      </div>
    </div>
  </div>
</template>

<script>
import elementMixin from '../../mixins/elementMixin'
import SettingIcon from '../icons/Setting.vue'

export default {
  components: { SettingIcon },
  mixins: [elementMixin],
  // eslint-disable-next-line vue/require-prop-types
  props: ['id'],
  methods: {
    openContactFormSettingModal() {
      const _this = this
      this.$store.commit('openContactFormSetting', {
        form: _this.data,
        onChange: (form) => {
          _this.data = form
        }
      })
    }
  }
}
</script>
<style lang="scss">
.bz-el--contact-form-root {
  width: 100%;
  position: relative;
  border: solid 2px transparent;

  .contact-form-editor {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    display: none;
    align-items: center;
    justify-content: center;
    cursor: pointer;

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

      .contact-form-editor {
        display: flex;
      }
    }
  }
}
</style>
