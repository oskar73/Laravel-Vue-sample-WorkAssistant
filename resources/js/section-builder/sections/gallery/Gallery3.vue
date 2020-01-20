<template>
  <div class="bz-section-container bz-sec--gallery3-root" :class="{ [breakPoint]: true }">
    <bz-background :setting="background" :size="sectionSize">
      <bz-container>
        <bz-title v-if="setting.elements.title" v-model="data.elements.title" />

        <bz-subtitle v-if="setting.elements.subtitle" v-model="data.elements.subtitle" />

        <bz-text v-if="setting.elements.description" v-model="data.elements.description" />
        <div class="tw-w-full">
          <bz-items v-model="data.items" :grid="true" :shadow="setting.layouts.shadow" :spacing="setting.layouts.spacing" :enable-sort="edit">
            <template v-slot="{ item }">
              <bz-aspect-view>
                <bz-image v-model="item.image" :circle="setting.layouts.shape === 'circle'" />
              </bz-aspect-view>
            </template>
          </bz-items>
        </div>
      </bz-container>
    </bz-background>
  </div>
</template>

<script>
import sectionMixin from '../../mixins/sectionMixin'
import BzBackground from '../../components/section/BzBackground.vue'
import BzContainer from '../../components/section/BzContainer.vue'
import BzTitle from '../../components/section/BzTitle.vue'
import BzSubtitle from '../../components/section/BzSubtitle.vue'

import BzItems from '../../components/section/BzItems.vue'
import BzAspectView from '../../components/section/BzAspectView.vue'

export default {
  components: {
    BzAspectView,
    BzItems,

    BzSubtitle,
    BzTitle,
    BzContainer,
    BzBackground
  },
  mixins: [sectionMixin],
  props: {
    position: {
      type: Number,
      required: true
    }
  }
}
</script>

<style lang="scss">
.bz-sec--gallery3-root {
  .content {
    padding: 30px 0;
  }

  .bz-grid {
    .draggable {
      display: inline-grid;
      grid-template-columns: repeat(4, minmax(0, 1fr));

      div:nth-child(10n + 1),
      div:nth-child(10n) {
        grid-column-end: span 2;
        grid-row-end: span 2;
      }

      div:nth-child(10n) {
        grid-column: 3 / span 2;
      }

      div:nth-child(10) {
        grid-row-start: 3;
      }
      div:nth-child(20) {
        grid-row-start: 7;
      }
      div:nth-child(30) {
        grid-row-start: 11;
      }
      div:nth-child(40) {
        grid-row-start: 15;
      }
      div:nth-child(50) {
        grid-row-start: 19;
      }
      div:nth-child(60) {
        grid-row-start: 23;
      }
      div:nth-child(70) {
        grid-row-start: 27;
      }
      div:nth-child(80) {
        grid-row-start: 31;
      }
      div:nth-child(90) {
        grid-row-start: 35;
      }
      div:nth-child(100) {
        grid-row-start: 39;
      }

      div:nth-child(10n + 8) {
        grid-column: 1;
      }

      div:nth-child(10n + 9) {
        grid-column: 2;
      }

      &.gap {
        grid-gap: 10px;
      }

      @media (max-width: 991px) {
        grid-template-columns: repeat(3, minmax(0, 1fr));

        div:nth-child(10n + 1),
        div:nth-child(10n),
        div:nth-child(10n + 8),
        div:nth-child(10n + 9) {
          grid-column-end: auto !important;
          grid-row-end: auto !important;
          grid-column: auto !important;
          grid-row-start: auto !important;
        }
      }

      @media (max-width: 640px) {
        grid-template-columns: repeat(2, minmax(0, 1fr));
      }
    }
  }
}
</style>
