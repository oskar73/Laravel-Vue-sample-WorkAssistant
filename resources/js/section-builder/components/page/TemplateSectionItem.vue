<template>
  <li
    class="template-section-item mb-1"
    :class="{ active: active, 'visible': visible || activePage.type === 'new-page' , '!tw-text-gray-400': !visible && activePage.type !== 'new-page' }"
    @click.prevent="$emit('click')"
  >
    <div class="template-section-name">{{ name }}</div>
    <div class="tw-flex tw-items-center">
      <div v-if="!hiddenVisible" class="template-section-visible tw-h-8 tw-w-6" :class="{ active: visible }" @click.prevent="$emit('toggle')">
        <i class="mdi" :class="{ 'tw-text-[#0076df] mdi-eye': visible, 'mdi-eye-off': !visible }"></i>
      </div>
    </div>
  </li>
</template>

<script>
import builderMixin from '@/section-builder/mixins/builderMixin'

export default {
  name: 'TemplateSectionItem',
  mixins: [builderMixin],
  props: {
    name: {
      type: String,
      required: true
    },
    active: {
      type: Boolean,
      required: true
    },
    visible: {
      type: Boolean,
      required: true
    },
    hiddenVisible: {
      type: Boolean,
      default: false
    }
  }
}
</script>

<style lang="scss">
.template-section-item {
  display: flex;
  align-items: center;
  justify-content: space-between;
  color: #000;
  padding: 5px;
  border-radius: 4px;
  cursor: pointer;

  &.visible {
    &:hover,
    &.active {
      text-decoration: none;
      background-color: #d9dee3;
    }
  }

  .template-section-name {
  }

  .template-section-visible {
    color: #000;
    padding: 5px;
    border-radius: 50%;
    cursor: pointer;

    &:hover {
      text-decoration: none;
      background-color: #d9dee3;
    }

    &.active {
      color: #0076df;
    }
  }
}
</style>
