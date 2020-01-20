<template>
  <div class="bz-section-container bz-sec--gallery2-root" :class="{ [breakPoint]: true }">
    <bz-background :setting="background" :size="sectionSize">
      <bz-container>
        <bz-title v-if="setting.elements.title" v-model="data.elements.title" />

        <bz-subtitle v-if="setting.elements.subtitle" v-model="data.elements.subtitle" />

        <bz-text v-if="setting.elements.description" v-model="data.elements.description" />
      </bz-container>
      <bz-container>
        <div class="tw-w-full">
          <bz-items v-model="data.items" :cols="setting.column" :shadow="setting.layouts.shadow" :spacing="setting.layouts.spacing" :enable-sort="edit">
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

import BzSubtitle from '../../components/section/BzSubtitle.vue'
import BzTitle from '../../components/section/BzTitle.vue'
import BzItems from '../../components/section/BzItems.vue'
import BzAspectView from '../../components/section/BzAspectView.vue'

export default {
  components: {
    BzAspectView,
    BzItems,
    BzTitle,
    BzSubtitle,

    BzContainer,
    BzBackground
  },
  mixins: [sectionMixin]
}
</script>

